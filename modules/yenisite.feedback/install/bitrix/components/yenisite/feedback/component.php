<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 300;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$IBLOCK_ID = $arParams['IBLOCK'];

//CModule::IncludeModule("yenisite.feedback");

if (!CModule::IncludeModule("yenisite.feedback"))
{
    $this->AbortResultCache();
    ShowError(GetMessage("FEEDBACK_MODULE_NOT_INSTALLED"));
    return;
}
else
{
    //if($this->StartResultCache(false, $USER->GetGroups()))
    //{
        if(!CModule::IncludeModule("iblock"))
        {
            $this->AbortResultCache();
            ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return;
        }

        $arDefaultUrlTemplates404 = array(
            "list" => "index.php",
            "section" => "#SECTION_CODE#/"
        );

        $arDefaultVariableAliases404 = array();

        $arDefaultVariableAliases = array();

        $arComponentVariables = array("IBLOCK_ID", "SECTION_CODE");


        $SEF_FOLDER = "";
        $arUrlTemplates = array();

        if ($arParams["SEF_MODE"] == "Y")
        {
            $arVariables = array();

            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

            $componentPage = CComponentEngine::ParseComponentPath(
                    $arParams["SEF_FOLDER"],
                    $arUrlTemplates,
                    $arVariables
            );

            if (StrLen($componentPage) <= 0)
                $componentPage = "list";

            CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

            $SEF_FOLDER = $arParams["SEF_FOLDER"];
            
        }
        else
        {
            $arVariables = array();

            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
            CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

            $componentPage = "";
        }

        $arResult = array(
            "FOLDER" => $SEF_FOLDER,
            "URL_TEMPLATES" => $arUrlTemplates,
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        );
        

            //var_dump($arResult);
            CYSFeedBack::$_IBLOCK_ID = $IBLOCK_ID;

    //}
    $this->IncludeComponentTemplate();
}
?>
