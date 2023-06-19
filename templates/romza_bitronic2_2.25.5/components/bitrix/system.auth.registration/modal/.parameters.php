<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arTemplateParameters = array(
	"URL_SHOP_RULES"=>array(
		"NAME" => GetMessage("URL_SHOP_RULES"),
		"TYPE" => "STRING",
		"DEFAULT" => "={SITE_DIR}about/",
	),
	"EMAIL_NOTICE" => array(
		"NAME" => GetMessage("EMAIL_NOTICE"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("EMAIL_NOTICE_TEXT"),
	),
);
?>