<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class RZHighloadblock extends CBitrixComponent
{
	protected $defaultUrlTemplates = array(
		'list' => '',
		'view' => '#ID#/'
	);

	protected $defaultVariableAliases = array(
		'ID' => 'ID'
	);

	public function onPrepareComponentParams($arParams)
	{
		$arParams = parent::onPrepareComponentParams($arParams);

		$arParams['ADD_ELEMENT_CHAIN'] = $arParams['ADD_ELEMENT_CHAIN'] === 'N' ? 'N' : 'Y';
        $arParams["SET_STATUS_404"] = 'Y';

		return $arParams;
	}

	public function executeComponent()
	{
		$this->setFrameMode(true);
		global $APPLICATION;

		$defaultUrlTemplates404 = &$this->defaultUrlTemplates;

		$componentVariables = array("ID", "XML_ID");
		$variables = array();

		if ($this->arParams["SEF_MODE"] == "Y") {
			$templatesUrls = CComponentEngine::makeComponentUrlTemplates($defaultUrlTemplates404, $this->arParams["SEF_URL_TEMPLATES"]);

			foreach ($templatesUrls as $url => $value) {
				$this->arResult["PATH_TO_".ToUpper($url)] = $this->arParams["SEF_FOLDER"].$value;
			}

			$variableAliases = CComponentEngine::makeComponentVariableAliases(array(), $this->arParams["VARIABLE_ALIASES"]);

			$componentPage = CComponentEngine::parseComponentPath(
				$this->arParams["SEF_FOLDER"],
				$templatesUrls,
				$variables
			);

			CComponentEngine::initComponentVariables($componentPage, $componentVariables, $variableAliases, $variables);

            $b404 = false;
			if (empty($componentPage)) {
				$componentPage = 'list';
                $b404 = true;
			}

            if($b404 && CModule::IncludeModule('iblock'))
            {
                $folder404 = str_replace("\\", "/", $this->arParams["SEF_FOLDER"]);
                if ($folder404 != "/")
                    $folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
                if (substr($folder404, -1) == "/")
                    $folder404 .= "index.php";

                if ($folder404 != $APPLICATION->GetCurPage(true))
                {
                    \Bitrix\Iblock\Component\Tools::process404(
                        ""
                        ,($this->arParams["SET_STATUS_404"] === "Y")
                        ,($this->arParams["SET_STATUS_404"] === "Y")
                        ,($this->arParams["SHOW_404"] === "Y")
                        ,$this->arParams["FILE_404"]
                    );
                }
            }

			$this->arResult = array_merge(
				Array(
					"SEF_FOLDER" => $this->arParams["SEF_FOLDER"],
					"URL_TEMPLATES" => $templatesUrls,
					"VARIABLES" => $variables,
					"ALIASES" => $variableAliases,
				),
				$this->arResult
			);
		} else {
			$variableAliases = CComponentEngine::makeComponentVariableAliases($this->defaultVariableAliases, $this->arParams["VARIABLE_ALIASES"]);
			CComponentEngine::initComponentVariables(false, $componentVariables, $variableAliases, $variables);

			$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

			$componentPage = ($request->get($variableAliases['ID']) || $request->get('XML_ID')) ? 'view' : 'list';

			$currentPage = $request->getRequestedPage();

			$this->arResult = array(
				"VARIABLES" => $variables,
				"ALIASES" => $variableAliases,
				"SEF_FOLDER" => $currentPage,
				"PATH_TO_LIST" => $currentPage,
				"PATH_TO_VIEW" => $currentPage . '?' . $variableAliases['ID'] . '=#ID#'
			);
		}

		$this->includeComponentTemplate($componentPage);
	}
}