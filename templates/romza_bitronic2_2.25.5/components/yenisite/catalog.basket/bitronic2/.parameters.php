<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



$arTemplateParameters = array(
	"YENISITE_BS_FLY" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("BASKET_PHOTO"),
		"TYPE" => "LIST",
		"VALUES" => $list,
	),
	"EMPTY_URL" => array(
		"PARENT" => "URL_TEMPLATES",
		"NAME" => GetMessage("EMPTY_URL"),
		"TYPE" => "STRING",
		"DEFAULT" => SITE_DIR."personal/order/empty.php",
	),
    "CART_RESIZER_SET" => array(
		'PARENT' => 'BASE',
		'NAME' => GetMessage('CART_RESIZER_SET'),
		'TYPE' => 'LIST',
		"VALUES" => $resizer_sets_list,
        "DEFAULT" => "1",
    )
);



?>
