<?php

namespace Yenisite\Core\Wizard\Steps;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Finish extends \CFinishWizardStep
{
	function InitStep()
	{
		$this->SetStepID('finish');
		$this->SetNextStep('finish');
		$this->SetTitle(GetMessage('FINISH_STEP_TITLE'));
		$this->SetNextCaption(GetMessage('WIZ_GO'));
	}

	function ShowStep()
	{
		$wizard = &$this->GetWizard();
		if ($wizard->GetVar('proactive') == 'Y') {
			\COption::SetOptionString('statistic', 'DEFENCE_ON', 'Y');
		}

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$siteID = \WizardServices::GetCurrentSiteID($wizard->GetVar('siteID'));
		/** @noinspection PhpUndefinedClassInspection */
		$rsSites = \CSite::GetByID($siteID);
		$siteDir = '/';
		if ($arSite = $rsSites->Fetch()) {
			$siteDir = $arSite['DIR'];
		}

		$wizard->SetFormActionScript(str_replace('//', '/', $siteDir . '/?finish'));

		$update = $wizard->GetVar('install_type') == 'update';
		if (!$update) {
			$this->CreateNewIndex();
		}

		\COption::SetOptionString($wizard->GetVar('solutionName'), 'wizard_installed', 'Y', false, $siteID);

		\CAdminNotify::DeleteByTag('RZ_' . strtoupper(str_replace('.', '_', $wizard->solutionName)) . '_UPDATE_WIZARD');

		$this->content .=
			'<table class="wizard-completion-table">
				<tr>
					<td class="wizard-completion-cell">'
			. GetMessage('FINISH_STEP_CONTENT') .
			'		</td>
				</tr>
			</table>';

		if ($wizard->GetVar('installDemoData') == 'Y') {
			$this->content .= GetMessage('FINISH_STEP_REINDEX');
		}
	}
}