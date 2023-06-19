<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
 <?$APPLICATION->IncludeComponent(
	"yenisite:feedback.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"IBLOCK" => $arParams['IBLOCK'],
		"AJAX_MODE" => $arParams['AJAX_MODE'],
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_NOTES" => "",
		"AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
		"AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
		"AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],
		"AJAX_OPTION_ADDITIONAL" => $arParams['AJAX_OPTION_ADDITIONAL'],
                "MESS_PER_PAGE" => $arParams['MESS_PER_PAGE'],
                "SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
                "SEF_MODE" => $arParams['SEF_MODE'],
                "SEF_FOLDER" => $arParams['SEF_FOLDER'],
                "ALLOW_RESPONSE" => $arParams['ALLOW_RESPONSE'],
                "COLOR_SCHEME" => $arParams['COLOR_SCHEME'],
                "ALWAYS_SHOW_PAGES" => $arParams['ALWAYS_SHOW_PAGES'],
	),
         $component
);?>
<br />
 <?$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"IBLOCK" => $arParams['IBLOCK'],
		"NAME_FIELD" => $arParams['NAME_FIELD'],
		"TITLE" => $arParams['TITLE'],
		"SUCCESS_TEXT" => $arParams['SUCCESS_TEXT'],
		"USE_CAPTCHA" => $arParams['USE_CAPTCHA'],
		"PRINT_FIELDS" => $arParams['PRINT_FIELDS'],
		"AJAX_MODE" => $arParams['AJAX_MODE'],
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
		"AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
		"AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],
		"NAME" => $arParams['NAME'],
		"EMAIL" => $arParams['EMAIL'],
		"PHONE" => $arParams['PHONE'],
		"MESSAGE" => $arParams['MESSAGE'],
		"ACTIVE" => $arParams['ACTIVE'],
		"EVENT_NAME" => $arParams['EVENT_NAME'],
		"TEXT_REQUIRED" => $arParams['TEXT_REQUIRED'],
		"TEXT_SHOW" => $arParams['TEXT_SHOW'],
                "SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
                "SHOW_SECTIONS" => "N",
                "ELEMENT_ID" => $arParams['ELEMENT_ID'],
                "COLOR_SCHEME" => $arParams['COLOR_SCHEME'],
	),
         $component
);?>
<br />
 