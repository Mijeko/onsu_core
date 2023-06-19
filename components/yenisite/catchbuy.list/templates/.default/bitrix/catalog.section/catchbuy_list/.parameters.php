<?php
$dir = __DIR__ . '/css/themes/';
$arFiles = glob($dir . 'theme_*.css');
$debug = 1;
$arThemes = array();
$arTemplateParameters = array(
	'THEME' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage("RZ_CATCHBUY_THEME"),
		'TYPE' => 'LIST',
		'DEFAULT' => 'blue-flat',
		'VALUES' => $arThemes,
	),
);