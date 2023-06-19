<?php

/**
 * yenisite.bitronic2
 */
use Bitrix\Main\Loader;

//set constants with module name
include 'constants.php';

$commonClassName = RZ_B2_MODULE_SHORT_NAME_FIRST_CAPITAL;

Loader::registerAutoLoadClasses(
	RZ_B2_MODULE_FULL_NAME,
	array(
		"CRZ{$commonClassName}CatalogUtils" => "classes/general/CRZ{$commonClassName}CatalogUtils.php",
		"CRZ{$commonClassName}Handlers" => "classes/general/CRZ{$commonClassName}Handlers.php",
		"CRZ{$commonClassName}Settings" => "classes/general/CRZ{$commonClassName}Settings.php",
		"CRZ{$commonClassName}Composite" => "classes/general/CRZ{$commonClassName}Composite.php",
		"CacheProvider" => "classes/general/CRZ{$commonClassName}Composite.php",
		"iRZProp" => "classes/general/iRZProp.php",
		"ConsVar" => "classes/general/consVar.php",
		"Mobile_Detect" => "classes/general/Mobile_detect.php",
		"{$commonClassName}\Catalog\CookiesUtils" => "classes/general/catalogCookies.php",
		"{$commonClassName}\Mobile" => "classes/general/mobile.php"
	)
);
?>