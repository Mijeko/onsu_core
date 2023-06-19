<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;
$arComponentParameters["GROUPS"]["STICKERS"]= array(
	"NAME" => GetMessage("STICKER_GROUP"),
	"SORT" => '10000',
);
$arComponentParameters["GROUPS"]["IBLOCKVOTE"]= array(
	"NAME" => GetMessage("IBLOCKVOTE_GROUP"),
	"SORT" => '4500',
);	
$arTemplateParameters = array(
	"STICKER_NEW" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_NEW'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '14',
	),
	"STICKER_HIT" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_HIT'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '100',
	),
	"STICKER_BESTSELLER" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '3',
	),
	"SHOW_ELEMENT" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SHOW_ELEMENT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	"VIEW_MODE" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("VIEW_MODE"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"popup" => "popup",
			"nopopup" => "nopopup",
		),
		"DEFAULT" => "popup",
	),
	
	"IBLOCK_MAX_VOTE" => array(
		"PARENT" => "IBLOCKVOTE",
		"NAME" => GetMessage("IBLOCK_MAX_VOTE"),
		"TYPE" => "STRING",
		"DEFAULT" => "5",
	),
	"IBLOCK_VOTE_NAMES" => array(
		"PARENT" => "IBLOCKVOTE",
		"NAME" => GetMessage("IBLOCK_VOTE_NAMES"),
		"TYPE" => "STRING",
		"VALUES" => array(),
		"MULTIPLE" => "Y",
		"DEFAULT" => array("1","2","3","4","5"),
		"ADDITIONAL_VALUES" => "Y",
	),
	"IBLOCK_SET_STATUS_404" => Array(
		"PARENT" => "IBLOCKVOTE",
		"NAME" => GetMessage("CP_BIV_SET_STATUS_404"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"DISPLAY_AS_RATING" => Array(
		"PARENT" => "IBLOCKVOTE",
		"NAME" => GetMessage("TP_CBIV_DISPLAY_AS_RATING"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"rating" => GetMessage("TP_CBIV_RATING"),
			"vote_avg" => GetMessage("TP_CBIV_AVERAGE"),
		),
		"DEFAULT" => "rating",
	),
	'HIDE_BUY_IF_PROPS' => array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("HIDE_BUY_IF_PROPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	),
);
?>