<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$component = $this->__component;
//01*
//To hide parameters from evil people we should save it into session
//and give just key to the public side.
//02*
//These parameters (just one for now) are accessible through URL anyway
//there is no sense in protecting it (only makes our session bigger)
$arSessionParams = array(
	"PAGE_PARAMS" => array("ELEMENT_ID"),
);
//03*
//Iterate parameters and put them in storage
foreach($arParams as $k=>$v)
	if(strncmp("~", $k, 1) && !in_array($k, $arSessionParams["PAGE_PARAMS"]))
		$arSessionParams[$k] = $v;
//04*
//We need these "parameters" to include proper component on AJAX call
$arSessionParams["COMPONENT_NAME"] = $component->GetName();
$arSessionParams["TEMPLATE_NAME"] = 'stars';
if($parent = $component->GetParent())
{
	$arSessionParams["PARENT_NAME"] = $parent->GetName();
	$arSessionParams["PARENT_TEMPLATE_NAME"] = $parent->GetTemplateName();
	$arSessionParams["PARENT_TEMPLATE_PAGE"] = $parent->GetTemplatePage();
}
//05*
//and here is our key!
$idSessionParams = md5(serialize($arSessionParams));

//06*
//Modify arResult of component.
//These data will be extracted from cache
//and will be written into session
$component->arResult["AJAX"] = array(
	"SESSION_KEY" => $idSessionParams,
	"SESSION_PARAMS" => $arSessionParams,
);

//07*
//This variable is for using in template
$arResult["~AJAX_PARAMS"] = array(
	"SESSION_PARAMS" => $idSessionParams,
	"PAGE_PARAMS" => array(
		"ELEMENT_ID" => $arParams["ELEMENT_ID"],
	),
	"sessid" => bitrix_sessid(),
	"AJAX_CALL" => "Y",
);
$component->arResult['~AJAX_PARAMS'] = $arResult['~AJAX_PARAMS'];
$component->setResultCacheKeys(array('~AJAX_PARAMS'));
//08*
//It will be passed transparently into AJAX Post.
$arResult["AJAX_PARAMS"] = CUtil::PhpToJSObject($arResult["~AJAX_PARAMS"]);
//09*
//There is more to see in template.php
?>
