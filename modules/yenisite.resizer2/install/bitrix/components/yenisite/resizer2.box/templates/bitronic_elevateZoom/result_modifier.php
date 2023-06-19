<?php

function is_fade(&$param)
{
	if ($param === 'false') return true;
	if (!is_numeric($param)) return false;
	$param = intval($param);
	
	if ($param > 0) return true;
	return false;
}

$arParams['ZOOM_TYPE'] = (!isset($arParams['ZOOM_TYPE']))? 'window' : $arParams['ZOOM_TYPE'];
$arParams['LENS_SHAPE'] = (!isset($arParams['LENS_SHAPE']))? 'square' : $arParams['LENS_SHAPE'];

$arParams['CONTAIN_LENS_ZOOM'] = ($arParams['CONTAIN_LENS_ZOOM'] == 'Y')? 'true' : 'false';
$arParams['SCROLL_ZOOM'] = ($arParams['SCROLL_ZOOM'] == 'Y')? 'true' : 'false';
$arParams['SHOW_LENS'] = ($arParams['SHOW_LENS'] == 'N')? 'false' : 'true';
$arParams['EASING'] = ($arParams['EASING'] == 'Y')? 'true' : 'false';
$arParams['TINT'] = ($arParams['TINT'] == 'Y')? 'true' : 'false';

if(!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if(!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';

if (empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if (empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if (empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;

if (empty($arParams['LENS_BORDER_COLOUR'])) $arParams['LENS_BORDER_COLOUR'] = '000';
if (empty($arParams['WINDOW_BG_COLOUR'])) $arParams['WINDOW_BG_COLOUR'] = 'FFF';
if (empty($arParams['BORDER_COLOUR'])) $arParams['BORDER_COLOUR'] = '888';
if (empty($arParams['LENS_COLOUR'])) $arParams['LENS_COLOUR'] = 'FFF';
if (empty($arParams['TINT_COLOUR'])) $arParams['TINT_COLOUR'] = '333';

if(!is_numeric($arParams['LENS_BORDER_SIZE'])) $arParams['LENS_BORDER_SIZE'] = '1';
if(!is_numeric($arParams['WINDOW_POSITION'])) $arParams['WINDOW_POSITION'] = '1';
if(!is_numeric($arParams['WINDOW_OFFSET_X'])) $arParams['WINDOW_OFFSET_X'] = '0';
if(!is_numeric($arParams['WINDOW_OFFSET_Y'])) $arParams['WINDOW_OFFSET_Y'] = '0';
if(!is_numeric($arParams['WINDOW_HEIGHT'])) $arParams['WINDOW_HEIGHT'] = '400';
if(!is_numeric($arParams['WINDOW_WIDTH'])) $arParams['WINDOW_WIDTH'] = '400';
if(!is_numeric($arParams['LENS_OPACITY'])) $arParams['LENS_OPACITY'] = '0.4';
if(!is_numeric($arParams['TINT_OPACITY'])) $arParams['TINT_OPACITY'] = '0.4';
if(!is_numeric($arParams['BORDER_SIZE'])) $arParams['BORDER_SIZE'] = '4';
if(!is_numeric($arParams['LENS_SIZE'])) $arParams['LENS_SIZE'] = '200';

if(!is_fade($arParams['WINDOW_FADE_OUT'])) $arParams['WINDOW_FADE_OUT'] = 700;
if(!is_fade($arParams['WINDOW_FADE_IN'])) $arParams['WINDOW_FADE_IN'] = 500;
if(!is_fade($arParams['LENS_FADE_OUT'])) $arParams['LENS_FADE_OUT'] = 'false';
if(!is_fade($arParams['TINT_FADE_OUT'])) $arParams['TINT_FADE_OUT'] = 'false';
if(!is_fade($arParams['LENS_FADE_IN'])) $arParams['LENS_FADE_IN'] = 'false';
if(!is_fade($arParams['TINT_FADE_IN'])) $arParams['TINT_FADE_IN'] = 'false';

$arParams['LENS_BORDER_COLOUR'] = ltrim($arParams['LENS_BORDER_COLOUR'], '#');
$arParams['WINDOW_BG_COLOUR'] = ltrim($arParams['WINDOW_BG_COLOUR'], '#');
$arParams['BORDER_COLOUR'] = ltrim($arParams['BORDER_COLOUR'], '#');
$arParams['LENS_COLOUR'] = ltrim($arParams['LENS_COLOUR'], '#');
$arParams['TINT_COLOUR'] = ltrim($arParams['TINT_COLOUR'], '#');
?>