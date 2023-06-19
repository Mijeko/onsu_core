<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;

require_once 'class.php';

$arComponentParametersMerged['PARAMETERS'] = array(
	"COMPONENT_LIST" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("COMPONENT_LIST"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"REFRESH" => "Y",
		"SIZE" => "4",
	),
	"REMOVE_POSTFIX_IN_NAMES" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("REMOVE_POSTFIX_IN_NAMES"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);

if (!empty($arCurrentValues["COMPONENT_LIST"])) {
	$componentFolder = BX_ROOT . '/components';

	if (\Bitrix\Main\Application::GetInstance()->GetContext()->GetRequest()->IsAjaxRequest()
		&& !is_array($arCurrentValues["COMPONENT_LIST"])
	) {
		$arCurrentValues["COMPONENT_LIST"] = explode(',', $arCurrentValues["COMPONENT_LIST"]);
	}
	foreach ($arCurrentValues["COMPONENT_LIST"] as $componentName) {
		$clearCMPName = \YenisiteProxy::getComponentName($componentName);
		$path = $componentFolder . CComponentEngine::makeComponentPath($clearCMPName);

		if (CComponentUtil::isComponent($path)) {

			$arMainCurrentValues = $arCurrentValues;
			$arCurrentValues = \YenisiteProxy::unpackParamsForComponent($arCurrentValues, $componentName);

			$arTemplatesOfInclCMP = \YenisiteProxy::getTemplatesForInclComponents($componentName,$_GET["template_id"]);


			Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . $path . '/.parameters.php');
			include $_SERVER['DOCUMENT_ROOT'] . $path . '/.parameters.php';

			foreach ($arTemplatesOfInclCMP as $templateData){
                $arTemplateParams['VALUES'][$templateData['NAME']] = $templateData['NAME'];
            }
            $arTemplateParams['PARENT'] = 'BASE';
            $arTemplateParams['NAME'] = GetMessage('TEMPLATE_LIST');
            $arTemplateParams['TYPE'] = 'LIST';
            $arTemplateParams['DEFAULT'] = 0;
            $arTemplateParams['REFRESH'] = 'Y';
            $arComponentParameters['PARAMETERS'] = array('TEMPLATE' => $arTemplateParams) + $arComponentParameters['PARAMETERS'];

            $curTemplate = $arCurrentValues['TEMPLATE'] ? : $arTemplatesOfInclCMP[0]['NAME'];

            $arMainCMPParams = $arComponentParameters;

           Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . $path . '/templates/'.$curTemplate.'/.parameters.php');
           include $_SERVER['DOCUMENT_ROOT'] . $path . '/templates/'.$curTemplate.'/.parameters.php';

            $arCurrentValues = $arMainCurrentValues;

            $arComponentParameters['GROUPS'] = $arComponentParameters['GROUPS'] ? array_merge($arComponentParameters['GROUPS'],$arMainCMPParams['GROUPS']) : $arMainCMPParams['GROUPS'];
            $arComponentParameters['PARAMETERS'] = $arComponentParameters['PARAMETERS'] ? array_merge($arComponentParameters['PARAMETERS'],$arMainCMPParams['PARAMETERS']) : $arMainCMPParams['PARAMETERS'];

            $cmpKey = \YenisiteProxy::getComponentKey($componentName);
			foreach ($arComponentParameters['PARAMETERS'] as $oldKey => $arParam) {
				$newKey = $cmpKey . strtoupper($oldKey);
				if ($arCurrentValues['REMOVE_POSTFIX_IN_NAMES'] != 'Y') {
					$arParam['NAME'] = $arParam['NAME'] . "({$clearCMPName})";
				}
				$arComponentParameters['PARAMETERS'][$newKey] = $arParam;
				unset($arComponentParameters['PARAMETERS'][$oldKey]);
			}
			foreach ($arTemplateParameters as $keyParam => $arParam) {
				$newKey = $cmpKey . strtoupper($keyParam);
				if ($arCurrentValues['REMOVE_POSTFIX_IN_NAMES'] != 'Y') {
					$arParam['NAME'] = $arParam['NAME'] . "({$clearCMPName})";
				}
				$arComponentParameters['PARAMETERS'][$newKey] = $arParam;
				unset($arTemplateParameters[$keyParam]);
			}
			$arComponentParametersMerged = array_merge_recursive($arComponentParametersMerged, $arComponentParameters);
		}
	}
}

$arComponentParameters = $arComponentParametersMerged;