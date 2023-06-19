<?php

namespace Yenisite\Core\Wizard;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use MongoDB\Driver\Exception\UnexpectedValueException;
use Yenisite\Core\Tools;

Loc::loadMessages(__FILE__);

class Main
{
	/**
	 * Check modules and return array of errors or empty array
	 *
	 * @param array $arNeed
	 * $arNeed example:
	 *        array('yenisite.core' => '') // check only install module
	 *        array('yenisite.core' => 'vX.X.X') // check version of installed module
	 * @param \CWizardStep $Step
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function checkNeedModules($arNeed, \CWizardStep $Step = null)
	{
		$arErrors = array();
		foreach ($arNeed as $moduleID => $check) {
			$bModuleInstalled = Loader::includeModule($moduleID);
			/* todo: add recommend module links\install
			if ($moduleID[0] == '@') {

			}
			*/
			$moduleName = GetMessage('RZ_MODULE_' . strtoupper(str_replace('.', '_', $moduleID)) . '_NAME');
			if (empty($moduleName)) {
				$moduleName = $moduleID;
			}
			if (!$bModuleInstalled) {
				$arErrors[] = GetMessage('RZ_MODULE_NOT_INSTALLED', array('#MODULE_NAME#' => $moduleName, '#MODULE_ID#' => $moduleID));
			} else {
				if (!empty($check)) {
					switch (true) {
						case is_string($check) && $check[0] = 'v':
							// version check
							$needVersion = substr($check, 1);
							$curVersion = Tools::getModuleVersion($moduleID);
							if (!$curVersion) {
								$arErrors[] = GetMessage('RZ_MODULE_VERSION_ERROR', array('#MODULE_ID#' => $moduleID));
							}
							if (version_compare($curVersion, $needVersion) < 0) {
								$arErrors[] = GetMessage('RZ_MODULE_VERSION_MINIMAL',
									array('#MODULE_ID#' => $moduleID, '#VERSION_NEED#' => $needVersion, '#VERSION_HAS#' => $curVersion));
							}
							break;
					}
				}
			}
		}
		if (isset($Step) && method_exists($Step, 'SetError')) {
			$bHasErr = 0;
			foreach ($arErrors as $err) {
				$bHasErr = 1;
				$Step->SetError($err);
				$Step->SetNextCaption(GetMessage('RZ_WIZARD_ERR_NEXT_CAPTION'));
				$Step->SetNextStep($Step->GetStepID());
			}
			return $bHasErr;
		} else {
			return $arErrors;
		}
	}

	/**
	 * @param array $arField
	 * $arField req keys:
	 *     'NAME' => Localized name of field
	 *     'ID' => ID and name attr value
	 *     'DEFAULT' => Default value of field
	 *     'TYPE' => type of field supported types:
	 *            - file
	 *            - checkbox
	 *            - textarea
	 *            - text
	 *            - password
	 *
	 * @param \CWizardStep $wizard
	 * @return string
	 */
	public static function showFormField($arField = array(), \CWizardStep $wizard)
	{
		$return = '';
		if (empty($arField)) return $return;
		$return .= '<div class="wizard-input-form-block">';
		switch ($arField['TYPE']) {
			case 'file':
				$return .= '<label for="' . $arField['ID'] . '" class="wizard-input-title">' . $arField['NAME'] . '</label><br>';
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$return .= $wizard->ShowFileField($arField['ID'],
						array('show_file_info' => 'N', 'id' => $arField['ID'])) . '<br />' . \CFile::ShowImage($arField['DEFAULT'], 220, 70,
						'border=0 vspace=5', false, false);
				break;
			case 'checkbox':
				$arAttr = array('id' => $arField['ID']);
				if ($arField['DEFAULT'] == 'Y') {
					$arAttr['checked'] = 'checked';
				}
				$return .= $wizard->ShowCheckboxField($arField['ID'], 'Y', $arAttr);
				$return .= '<label for="' . $arField['ID'] . '" class="wizard-input-title">' . $arField['NAME'] . '</label>';
				break;
			case 'textarea':
			case 'text':
			case 'password':
				$return .= '<label for="' . $arField['ID'] . '" class="wizard-input-title">' . $arField['NAME'] . '</label><br>';
				$return .= $wizard->ShowInputField($arField['TYPE'], $arField['ID'],
					Array('id' => $arField['ID'], 'rows' => '3', 'class' => 'wizard-field'));
				break;
			default:
				$return .= 'not support field';
		}
		if (!empty($arField['DESC'])) {
			$return .= '<p>' . $arField['DESC'] . '</p>';
		}
		$return .= '</div>';
		return $return;
	}

	/**
	 * Install main Mail Events
	 * @param string $SITE_ID
	 */
	public static function installEvents($SITE_ID = 's1')
	{
		$arSupportedLang = array(
			'ru',
			'en',
		);
		$arEventNames = array(
			'NEW_USER' => array(
				'EMAIL_TO' => '#DEFAULT_EMAIL_FROM#',
			),
			'USER_INFO' => array(),
			'NEW_USER_CONFIRM' => array(),
			'USER_PASS_REQUEST' => array(),
			'USER_PASS_CHANGED' => array(),
			'USER_INVITE' => array(),
			'FEEDBACK_FORM' => array(),
		);

		$arEventTypes = array();
		foreach ($arSupportedLang as $LID) {
			$arEventTypes[$LID] = $arEventNames;
		}

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rs = \CEventType::GetList();
		while ($ar = $rs->Fetch()) {
			if (isset($arEventTypes[$ar['LID']][$ar['EVENT_NAME']])) {
				unset($arEventTypes[$ar['LID']][$ar['EVENT_NAME']]);
				if (empty($arEventTypes[$ar['LID']])) {
					unset($arEventTypes[$ar['LID']]);
				}
			}
		}

		foreach ($arEventTypes as $lang => $arEvents) {
			IncludeModuleLangFile(__FILE__, $lang);

			$i = 1;
			$arCreateTypes = array();
			foreach ($arEvents as $eventName => $id) {
				$arCreateTypes[] = array(
					"LID" => $lang,
					"EVENT_NAME" => $eventName,
					"NAME" => GetMessage('MAIN_' . $eventName . '_TYPE_NAME'),
					"DESCRIPTION" => GetMessage("MAIN_' . $eventName . '_TYPE_DESC"),
					"SORT" => $i,
				);
				++$i;
			}
		}

		if (!empty($arCreateTypes)) {
			$type = new \CEventType;
			foreach ($arCreateTypes as $arEventType) {
				$type->Add($arEventType);
			}
		}

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rs = \CEventMessage::GetList($by = "id", $order = "asc");
		while ($ar = $rs->Fetch()) {
			$arTest[] = $ar;
			if (isset($arEventNames[$ar['EVENT_NAME']])) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$rsSites = \CEventMessage::GetSite($ar['ID']);
				while ($arSite = $rsSites->Fetch()) {
					$arEventNames[$ar['EVENT_NAME']]['SITES'][$arSite['LID']] = $arSite['LID'];
				}
				$arEventNames[$ar['EVENT_NAME']]['ID'] = $ar['ID'];
			}
		}
		$arNewMessages = array();
		$arUpdateMessages = array();

		foreach ($arEventNames as $eventName => $arEvent) {
			if (empty($arEvent['SITES'])) {
				$arNewMessages[] = array(
					'EVENT_NAME' => $eventName,
					'LID' => $SITE_ID,
					'EMAIL_FROM' => $arEvent['EMAIL_FROM'] ?: '#DEFAULT_EMAIL_FROM#',
					'EMAIL_TO' => $arEvent['EMAIL_TO'] ?: '#EMAIL#',
					'SUBJECT' => GetMessage('MAIN_' . $eventName . '_EVENT_NAME'),
					'MESSAGE' => GetMessage('MAIN_' . $eventName . '_EVENT_DESC'),
				);
			} else {
				if (!isset($arEvent['SITES'][$SITE_ID])) {
					$arEvent['SITES'][$SITE_ID] = $SITE_ID;
					$arUpdateMessages[$arEvent['ID']] = array(
						'ID' => $arEvent['ID'],
						'SITES' => array_values($arEvent['SITES']),
					);
				}
			}
		}

		if (!empty($arNewMessages)) {
			$message = new \CEventMessage;
			foreach ($arNewMessages as $arMessage) {
				$message->Add($arMessage);
			}
		}
		if (!empty($arUpdateMessages)) {
			$message = new \CEventMessage;
			foreach ($arUpdateMessages as $arMessage) {
				$message->Update($arMessage['ID'], array('LID' => $arMessage['SITES']));
			}
		}
	}

	/**
	 * Get object for \CWizardBase from current data GetVar
	 * @return \CWizardBase
	 * @throws \UnexpectedValueException
	 */
	public static function getCurrentWizard()
	{
		global $arWizardName;
		if (empty($arWizardName)) {
			if (empty($_REQUEST["wizardName"])) {
				$_REQUEST["wizardName"] = $GLOBALS['wizard']->name;
			}
			$arWizardNameTmp = explode(":", $_REQUEST["wizardName"]);
			$arWizardName = array();
			foreach ($arWizardNameTmp as $a) {
				$a = preg_replace("#[^a-z0-9_.-]+#i", "", $a);
				if (strlen($a) > 0) {
					$arWizardName[] = $a;
				}
			}
			if (count($arWizardName) > 2) {

				$arWizardName = array($arWizardName[1], $arWizardName[2]);
			}
		}
		if (count($arWizardName)) {
			$installer = new \CWizard($arWizardName[0] . (count($arWizardName) > 1 ? ":" . $arWizardName[1] : ""));
			$wizardName = (array_key_exists("NAME", $installer->arDescription) ? $installer->arDescription["NAME"] : "");
			return new \CWizardBase($wizardName, $installer);
		} else {
			throw new \UnexpectedValueException('variable $arWizard must not be empty');
		}
	}
}