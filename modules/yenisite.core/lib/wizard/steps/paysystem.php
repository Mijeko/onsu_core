<?php

namespace Yenisite\Core\Wizard\Steps;


use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PaySystem extends \CWizardStep
{
	public $arTypes;

	function InitStep()
	{
		$this->BeforeInit();
		$wizard = &$this->GetWizard();
		$arDef = array();
		if ("ru" == LANGUAGE_ID) {
			if ($this->arTypes) {
				if (isset($this->arTypes['paysystem'])) {
					$arDef['paysystem'] = array(
						"cash" => "Y",
						"sber" => "Y",
						"bill" => "Y",
						"collect" => "Y"
					);
				}
				if (isset($this->arTypes['delivery'])) {
					$arDef['delivery'] = array(
						"courier" => "Y",
						"self" => "Y",
						"russianpost" => "N",
						"rus_post" => "N",
						"rus_post_first" => "N",
						"ua_post" => "N",
						"kaz_post" => "N"
					);
				}
			}
		} else {
			if (isset($this->arTypes['paysystem'])) {
				$arDef['paysystem'] = array(
					"cash" => "Y",
					"paypal" => "Y",
				);
			}
			if (isset($this->arTypes['delivery'])) {
				$arDef['delivery'] = array(
					"courier" => "Y",
					"self" => "Y",
					"dhl" => "Y",
					"ups" => "Y",
				);
			}
		}
		$wizard->SetDefaultVars($arDef);
		$this->AfterInit();
	}

	public function AfterInit()
	{
		$this->SetStepID("pay_system");
		$this->SetTitle(GetMessage("WIZ_STEP_PS"));
		$this->SetNextStep("data_install");
		$this->SetPrevStep("person_type");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
	}

	public function BeforeInit()
	{
		$this->arTypes = array(
			'paysystem' => '1',
			'delivery' => '1',
			'location' => '1',
		);
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$ps = $wizard->GetVar("paysystem");

		if (
			empty($ps["cash"])
			&& empty($ps["sber"])
			&& empty($ps["bill"])
			&& empty($ps["paypal"])
			&& empty($ps["oshad"])
			&& empty($ps["collect"])
		) {
			$this->SetError(GetMessage('WIZ_NO_PS'));
		}
	}

	function ShowStep()
	{
		if (!isset($this->arTypes['paysystem']) && !isset($this->arTypes['delivery']) && !isset($this->arTypes['location'])) {
			return '';
		}

		$wizard = &$this->GetWizard();

		$personType = $wizard->GetVar("personType");
		if (isset($this->arTypes['delivery'])) {
			$arAutoDeliveries = array();
			if (Loader::IncludeModule("sale")) {
				$dbDelivery = \CSaleDeliveryHandler::GetList();
				while ($arDelivery = $dbDelivery->Fetch()) {
					$arAutoDeliveries[$arDelivery["SID"]] = $arDelivery["ACTIVE"];
				}
			}
		}


		$this->content .= '<div class="wizard-input-form">';
		if (isset($this->arTypes['paysystem'])) {
			$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">' . GetMessage("WIZ_PAY_SYSTEM_TITLE") . '</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))) .
				' <label for="paysystemC">' . GetMessage("WIZ_PAY_SYSTEM_C") . '</label>
					</div>';

			if (LANGUAGE_ID == "ru") {
				if ($personType["fiz"] == "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))) .
						' <label for="paysystemS">' . GetMessage("WIZ_PAY_SYSTEM_S") . '</label>
								</div>';
				}
				if ($personType["fiz"] == "Y" || $personType["ur"] == "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('paysystem[collect]', 'Y', (array("id" => "paysystemCOL"))) .
						' <label for="paysystemCOL">' . GetMessage("WIZ_PAY_SYSTEM_COL") . '</label>
								</div>';
				}

				if ($personType["ur"] == "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))) .
						' <label for="paysystemB">';

					$this->content .= GetMessage("WIZ_PAY_SYSTEM_B");
					$this->content .= '</label>
							</div>';
				}
			} else {
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))) .
					' <label for="paysystemP">PayPal</label>
						</div>';
			}
			$this->content .= '</div>
			</div>
			<div class="wizard-catalog-form-item">' . GetMessage("WIZ_PAY_SYSTEM") . '</div>
		</div>';
		}
		if (isset($this->arTypes['delivery'])) {
			$this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-catalog-title">' . GetMessage("WIZ_DELIVERY_TITLE") . '</div>
				<div>
					<div class="wizard-input-form-field wizard-input-form-field-checkbox">';

			$this->content .= '<div class="wizard-catalog-form-item">
								' . $this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))) .
				' <label for="deliveryC">' . GetMessage("WIZ_DELIVERY_C") . '</label>
							</div>
							<div class="wizard-catalog-form-item">
								' . $this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))) .
				' <label for="deliveryS">' . GetMessage("WIZ_DELIVERY_S") . '</label>
							</div>';
			if (LANGUAGE_ID == "ru") {
				if ($arAutoDeliveries["russianpost"] != "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('delivery[russianpost]', 'Y', (array("id" => "deliveryR"))) .
						' <label for="deliveryR">' . GetMessage("WIZ_DELIVERY_R") . '</label>
										</div>';
				}
				if ($arAutoDeliveries["rus_post"] != "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('delivery[rus_post]', 'Y', (array("id" => "deliveryR2"))) .
						' <label for="deliveryR2">' . GetMessage("WIZ_DELIVERY_R2") . '</label>
										</div>';
				}
				if ($arAutoDeliveries["rus_post_first"] != "Y") {
					$this->content .=
						'<div class="wizard-catalog-form-item">' .
						$this->ShowCheckboxField('delivery[rus_post_first]', 'Y', (array("id" => "deliveryRF"))) .
						' <label for="deliveryRF">' . GetMessage("WIZ_DELIVERY_RF") . '</label>
										</div>';
				}
			} else {
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))) .
					' <label for="deliveryD">DHL</label>
								</div>';
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))) .
					' <label for="deliveryU">UPS</label>
								</div>';
			}
			$this->content .= '
					</div>
				</div>
				<div class="wizard-catalog-form-item">' . GetMessage("WIZ_DELIVERY") . '</div>
			</div>';
		}
		if (isset($this->arTypes['location'])) {
			$this->content .= '
		<div>
			<div class="wizard-catalog-title">' . GetMessage("WIZ_LOCATION_TITLE") . '</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
			if (in_array(LANGUAGE_ID, array("ru", "ua"))) {
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
					. " <label for=\"loc_ussr\">" . GetMessage('WSL_STEP2_GFILE_USSR') . "</label>
				</div>";
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowRadioField("locations_csv", "loc_ua.csv", array("id" => "loc_ua"))
					. " <label for=\"loc_ua\">" . GetMessage('WSL_STEP2_GFILE_UA') . "</label>
				</div>";
				$this->content .=
					'<div class="wizard-catalog-form-item">' .
					$this->ShowRadioField("locations_csv", "loc_kz.csv", array("id" => "loc_kz"))
					. " <label for=\"loc_kz\">" . GetMessage('WSL_STEP2_GFILE_KZ') . "</label>
				</div>";
			}
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
				. " <label for=\"loc_usa\">" . GetMessage('WSL_STEP2_GFILE_USA') . "</label>
			</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
				. " <label for=\"loc_cntr\">" . GetMessage('WSL_STEP2_GFILE_CNTR') . "</label>
			</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "", array("id" => "none"))
				. " <label for=\"none\">" . GetMessage('WSL_STEP2_GFILE_NONE') . "</label>
			</div>";

			$this->content .= '
				</div>
			</div>
		</div>';

			$this->content .= '<div class="wizard-catalog-form-item">' . GetMessage("WIZ_DELIVERY_HINT") . '</div>';
		}
		$this->content .= '</div>';
	}
}