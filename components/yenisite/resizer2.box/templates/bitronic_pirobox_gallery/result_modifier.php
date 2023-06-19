<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['PIRO_SPEED'])) $arParams['PIRO_SPEED'] = '700';
if (!isset($arParams['BG_ALPHA'])) $arParams['BG_ALPHA'] = '0.5';
if (!isset($arParams['PIRO_SCROLL'])) $arParams['PIRO_SCROLL'] = 'false';
if (!isset($arParams['PIRO_STYLE'])) $arParams['PIRO_STYLE'] = 'style1';
if (!isset($arParams['PIRO_COLOR'])) $arParams['PIRO_COLOR'] = 'ffffff';

if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;

$arParams['PIRO_COLOR'] = ltrim($arParams['PIRO_COLOR'], '#');
?>