<?php

namespace Yenisite\Core\Wizard\Steps;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class InstallType extends \CWizardStep
{

	function InitStep()
	{
		parent::InitStep();
		$this->SetStepID('install_type');
		$this->SetTitle(GetMessage('RZ_WIZ_INSTALL_TYPE'));
		$this->SetPrevStep('select_site');
		$this->SetNextStep('select_theme');
	}

	function ShowStep()
	{
		$wizard = &$this->GetWizard();

		$bInstalled = 'Y' == \COption::GetOptionString($wizard->GetVar('solutionName'), 'wizard_installed', 'N', $wizard->GetVar('siteID'));

		$wizard->SetVar("wizard_installed", $bInstalled);

		$onChange = "changeNextStep()";
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="install_type">' . GetMessage("WIZ_INSTALL_TYPE") . '</label></h4>';
		if (!$bInstalled) {
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>' . $this->ShowRadioField("install_type",
					"install",
					array("checked" => "checked", "onchange" => $onChange)) . GetMessage("RZ_WIZ_INSTALL_FIRST") . '</label></div>';
		} else {
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>' . $this->ShowRadioField("install_type",
					"update", array(
						"checked" => "checked",
						"onchange" => $onChange
					)) . GetMessage("RZ_WIZ_UPDATE") . '</label><p>' . GetMessage("RZ_WIZ_UPDATE_DESC") . '</p></div>';
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>' . $this->ShowRadioField("install_type",
					"install",
					array("onchange" => $onChange)) . GetMessage("RZ_WIZ_REINSTALL") . '</label><p>' . GetMessage("RZ_WIZ_REINSTALL_DESC") . '</p></div>';
		}
		$this->content .= '</div></div>';

		$this->content .= '<script type="text/javascript">
			function changeNextStep() {
				var input = document.getElementsByName("NextStepID")[0];
				var install_type = document.getElementsByName("__wiz_install_type");
				for (var i = 0; i < install_type.length; i++) {
					if (install_type[i].checked) {
							var value = install_type[i].value;
					}
				}

				if(value == "install") {
					input.value = "select_theme";
				} else if(value == "update") {
					input.value = "data_install";
				}
			}
			changeNextStep();
		</script>';
	}
}