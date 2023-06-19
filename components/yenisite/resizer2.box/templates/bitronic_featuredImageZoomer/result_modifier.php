<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;

if(empty($arParams['MAGNIFIER_POSITION'])) $arParams['MAGNIFIER_POSITION'] = 'right';
if(empty($arParams['MAGNIFIER_SIZE'])) $arParams['MAGNIFIER_SIZE'] = '[200, 200]';
if(empty($arParams['ZOOM_RANGE'])) $arParams['ZOOM_RANGE'] = '[2, 5]';
if(empty($arParams['FADE_SPEED'])) $arParams['FADE_SPEED'] = '600';
if(!isset($arParams['Z_INDEX'])) $arParams['Z_INDEX'] = '';

$arParams['CURSOR_SHADE'] = ($arParams['CURSOR_SHADE'] == 'N')? 'false' : 'true';
$arParams['MAGNIFIER_VERT_CENTER'] = ($arParams['MAGNIFIER_VERT_CENTER'] == 'Y')? 'true' : 'false';
$arParams['ZOOM_ABLE_FADE'] = ($arParams['ZOOM_ABLE_FADE'] == 'N')? 'false' : 'true';

if ($arParams['CURSOR_SHADE'] == 'true') {
	if (!isset($arParams['CURSOR_SHADE_OPACITY'])) $arParams['CURSOR_SHADE_OPACITY'] = '0.3';
	if (!isset($arParams['CURSOR_SHADE_COLOR'])) $arParams['CURSOR_SHADE_COLOR'] = 'FFFFFF';
}

if ($arParams['ZOOM_RANGE'] == 'undefined') {
	$arParams['DISABLE_WHEEL'] = ($arParams['DISABLE_WHEEL'] == 'Y')? 'true' : 'false';
} else {
	$arParams['DISABLE_WHEEL'] = 'false';
}

$arParams['CURSOR_SHADE_COLOR'] = ltrim($arParams['CURSOR_SHADE_COLOR'], '#');
?>