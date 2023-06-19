<?
if (!isset($arParams['SOFT_FOCUS'])) $arParams['SOFT_FOCUS'] = 'false';
if (!isset($arParams['ZOOM_WIDTH'])) $arParams['ZOOM_WIDTH'] = '350';
if (!isset($arParams['ZOOM_HEIGHT'])) $arParams['ZOOM_HEIGHT'] = '350';
if (!isset($arParams['POSITION'])) $arParams['POSITION'] = 'right';
if (!isset($arParams['ADJUST_X'])) $arParams['ADJUST_X'] = '0';
if (!isset($arParams['ADJUST_Y'])) $arParams['ADJUST_Y'] = '0';
if (!isset($arParams['TINT'])) $arParams['TINT'] = '000';
if (!isset($arParams['TINT_OPACITY'])) $arParams['TINT_OPACITY'] = '0.5';
if (!isset($arParams['LENS_OPACITY'])) $arParams['LENS_OPACITY'] = '0.5';
if (!isset($arParams['SMOOTH_MOVE'])) $arParams['SMOOTH_MOVE'] = '3';
if (!isset($arParams['SHOW_TITLE'])) $arParams['SHOW_TITLE'] = 'true';
if (!isset($arParams['TITLE_OPACITY'])) $arParams['TITLE_OPACITY'] = '0.5';

$arParams['TINT'] = ltrim($arParams['TINT'], '#');
?>