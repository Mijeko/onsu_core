<?php
CJSCore::Init('jquery');
$Asset = \Bitrix\Main\Page\Asset::getInstance();
$Asset->addJs($templateFolder .'/js/modernizr.custom.min.js');
$Asset->addJs($templateFolder .'/js/bootstrap/tooltip.js');
$Asset->addJs($templateFolder .'/js/UmSlider.js');
$Asset->addJs($templateFolder .'/js/jquery.countdown.2.0.2/jquery.plugin.min.js');
$Asset->addJs($templateFolder .'/js/jquery.countdown.2.0.2/jquery.countdown.min.js');
$Asset->addJs($templateFolder .'/js/jquery.countdown.2.0.2/jquery.countdown-ru.js');
$Asset->addJs($templateFolder .'/js/init.js');

$Asset->addCss($templateFolder . '/css/bs.css');
$Asset->addCss($templateFolder . '/css/s.css');
if(empty($arParams['THEME'])) {
	$arParams['THEME'] = 'mint-flat';
}
$Asset->addCss($templateFolder . '/css/themes/theme_' . $arParams['THEME'] . '.css');
$Asset->addCss($templateFolder . '/font/flaticon.css');