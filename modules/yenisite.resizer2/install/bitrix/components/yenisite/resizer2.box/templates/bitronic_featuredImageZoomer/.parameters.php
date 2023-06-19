<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arOpacity = array('0' => '0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5', '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1' => '1');
$arOverlay = array('true' => 'Y', 'false' => 'N');
$arPosition = array('left' => GetMessage('POSITION_LEFT'), 'right' => GetMessage('POSITION_RIGHT'));
$arSize = array(
	'[100, 100]' => '100 x 100',
	'[150, 150]' => '150 x 150',
	'[200, 200]' => '200 x 200',
	'[250, 250]' => '250 x 250',
	'[300, 300]' => '300 x 300',
	'[400, 400]' => '400 x 400'
);
$arZoom = array('undefined' => 'x2', '[2, 3]' => 'x3', '[2, 4]' => 'x4', '[2, 5]' => 'x5', '[2, 6]' => 'x6', '[2, 7]' => 'x7', '[2, 8]' => 'x8', '[2, 9]' => 'x9');


$arTemplateParameters = array(
	'SET_DETAIL' => array(
		'PARENT' => 'BASE',
		'NAME' => GetMessage('SET_DETAIL'),
		'TYPE' => 'LIST',
		'VALUES' => $arSet,
		'DEFAULT' => '2'
	),
    
	'SET_BIG_DETAIL' => array(
		'PARENT' => 'BASE',
		'NAME' => GetMessage('SET_BIG_DETAIL'),
		'TYPE' => 'LIST',
		'VALUES' => $arSet,
		'DEFAULT' => '7'
	),

	'SET_SMALL_DETAIL' => array(
		'PARENT' => 'BASE',
		'NAME' => GetMessage('SET_SMALL_DETAIL'),
		'TYPE' => 'LIST',
		'VALUES' => $arSet,
		'DEFAULT' => '6'
	),
	
	'SHOW_DESCRIPTION' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('SHOW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
	
	'CURSOR_SHADE' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CURSOR_SHADE'),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'Y',
		'DEFAULT' => 'Y'
	),
	
	'MAGNIFIER_VERT_CENTER' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('MAGNIFIER_VERT_CENTER'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	),
	
	'MAGNIFIER_POSITION' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('MAGNIFIER_POSITION'),
		'TYPE' => 'LIST',
		'VALUES' => $arPosition,
		'DEFAULT' => 'right'
	),
	
	'MAGNIFIER_SIZE' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('MAGNIFIER_SIZE'),
		'TYPE' => 'LIST',
		'VALUES' => $arSize,
		'DEFAULT' => '[200, 200]'
	),
	
	'ZOOM_RANGE' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('ZOOM_RANGE'),
		'TYPE' => 'LIST',
		'VALUES' => $arZoom,
		'REFRESH' => 'Y',
		'DEFAULT' => '[2, 5]'
	),
	
	'ZOOM_ABLE_FADE' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('ZOOM_ABLE_FADE'),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'Y',
		'DEFAULT' => 'Y'
	),
	
	'Z_INDEX' => Array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('Z_INDEX'),
		'TYPE' => 'LIST',
		'VALUES' => array('' => GetMessage('Z_INDEX_OPTIMAL')),
		'ADDITIONAL_VALUES' => 'Y',
		'DEFAULT' => ''
	)
);

function InsertAfter(&$array, $value, $key)
{
	$offset = array_search($key, array_keys($array)) + 1;
	$array = array_slice($array, 0, $offset, true)
	       + $value
	       + array_slice($array, $offset, NULL, true);
}

$bCursorShade = (empty($arCurrentValues['CURSOR_SHADE']))? ($arTemplateParameters['CURSOR_SHADE']['DEFAULT'] == 'Y')
                                                         : ($arCurrentValues['CURSOR_SHADE'] == 'Y');

$bZoomRange = (empty($arCurrentValues['ZOOM_RANGE']))? ($arTemplateParameters['ZOOM_RANGE']['DEFAULT'] == 'undefined')
                                                     : ($arCurrentValues['ZOOM_RANGE'] == 'undefined');

$bZoomFade = (empty($arCurrentValues['ZOOM_ABLE_FADE']))? ($arTemplateParameters['ZOOM_ABLE_FADE']['DEFAULT'] == 'Y')
                                                        : ($arCurrentValues['ZOOM_ABLE_FADE'] == 'Y');

if ($bCursorShade) {
	$arNewParam = array(
		'CURSOR_SHADE_OPACITY' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CURSOR_SHADE_OPACITY'),
			'TYPE' => 'LIST',
			'VALUES' => $arOpacity,
			'DEFAULT' => '0.3'
		),
		
		'CURSOR_SHADE_COLOR' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CURSOR_SHADE_COLOR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => 'FFFFFF'
		)
	);
	InsertAfter($arTemplateParameters, $arNewParam, 'CURSOR_SHADE');
}

if ($bZoomRange) {
	$arNewParam = array(
		'DISABLE_WHEEL' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('DISABLE_WHEEL'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		)
	);
	InsertAfter($arTemplateParameters, $arNewParam, 'ZOOM_RANGE');
}

if ($bZoomFade) {
	$arSpeed = array(
		'100' => '100', '200' => '200', '300' => '300',
		'400' => '400', '500' => '500', '600' => '600',
		'700' => '700', '800' => '800', '900' => '900'
	);
	$arNewParam = array(
		'FADE_SPEED' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('FADE_SPEED'),
			'TYPE' => 'LIST',
			'VALUES' => $arSpeed,
			'DEFAULT' => '600'
		)
	);
	InsertAfter($arTemplateParameters, $arNewParam, 'ZOOM_ABLE_FADE');
}
?>
