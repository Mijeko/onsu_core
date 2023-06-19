<?php

namespace Yenisite\Core\Wizard\Steps;

use Bitrix\Main\Localization\Loc;
use Yenisite\Core\Wizard\Main;

Loc::loadMessages(__FILE__);

abstract class SiteSettings extends \CSiteSettingsWizardStep
{
	public $arFields;
	private $arFiles;

	abstract public function InitFields();

	function InitStep()
	{
		$wizard = &$this->GetWizard();
		parent::InitStep();

		$this->InitFields();
		if (empty($this->arFields)) {
			$this->arFields = array();
		}

		$arDef = array();
		foreach ($this->arFields as $key => $ar) {
			$arDef[$ar['ID']] = $ar['DEFAULT'] ?: '';
			if ($ar['TYPE'] == 'file') {
				$this->arFiles[] = $ar;
			}
		}
		$wizard->SetDefaultVars($arDef);
		$this->AfterInit();
	}

	public function AfterInit()
	{
		//If you want redeclare StepID, Title, etc use this method
		$this->SetStepID("site_settings");
		$this->SetTitle(GetMessage('RZ_WIZ_SITE_SETTINGS'));
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetNextStep("shop_settings");
	}

	function ShowStep()
	{
		$this->content .= '<div class="wizard-input-form">';
		foreach ($this->arFields as $arField) {
			$this->content .= Main::showFormField($arField, $this);
		}
		$this->content .= '</div>';
	}

	function OnPostForm()
	{
		if (!empty($this->arFiles)) {
			foreach ($this->arFiles as $arFile) {
				$this->SaveFile($arFile['ID'], array(
					"extensions" => "gif,jpg,jpeg,png",
					"max_height" => $arFile['HEIGHT'],
					"max_width" => $arFile['WIDTH'],
					"make_preview" => "Y"
				));
			}
		}
	}
}