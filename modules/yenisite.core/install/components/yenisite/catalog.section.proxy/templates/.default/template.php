<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global ${$arParams['FILTER_NAME']} ;
${$arParams['FILTER_NAME']} = array('!PROPERTY_SHOW_MAIN' => false);

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"",
	$arParams,
	$component,
	array('HIDE_ICONS' => 'N')
);
?>
