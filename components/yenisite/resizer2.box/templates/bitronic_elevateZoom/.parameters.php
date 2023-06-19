<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arFade = array(
	'100' => '100', '200' => '200', '300' => '300',
	'400' => '400', '500' => '500', '600' => '600',
	'700' => '700', '800' => '800', '900' => '900',
	'1000' => '1000', '1200' => '1200', '1400' => '1400',
	'1600' => '1600', '1800' => '1800', '2000' => '2000',
	'2200' => '2200', '2500' => '2500', '3000' => '3000',
	'false' => GetMessage('NO_FADE')
);
$arPosition = array(
	'1' => GetMessage('POS1'),
	'2' => GetMessage('POS2'),
	'3' => GetMessage('POS3'),
	'4' => GetMessage('POS4'),
	'5' => GetMessage('POS5'),
	'6' => GetMessage('POS6'),
	'7' => GetMessage('POS7'),
	'8' => GetMessage('POS8'),
	'9' => GetMessage('POS9'),
	'10' => GetMessage('POS10'),
	'11' => GetMessage('POS11'),
	'12' => GetMessage('POS12'),
	'13' => GetMessage('POS13'),
	'14' => GetMessage('POS14'),
	'15' => GetMessage('POS15'),
	'16' => GetMessage('POS16')
);
$arOpacity    = array('0' => '0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5', '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1' => '1');
$arZoomTypes  = array('window' => GetMessage('WINDOW'), 'inner' => GetMessage('INNER'), 'lens' => GetMessage('LENS'));
$arLensShapes = array('square' => GetMessage('SQUARE'), 'round' => GetMessage('ROUND'));

global $arComponentParameters;

$arComponentParameters['GROUPS']['YENISITE_RESIZER2_PARAMETERS']= array(
	'NAME' => GetMessage('YENISITE_RESIZER2_PARAMETERS'),
	'SORT' => 2000,
);

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
		
    'SHOW_DESCRIPTION'	=> Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('SHOW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
    
    'SHOW_DELAY_DETAIL' => Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('SHOW_DELAY_DETAIL'),
		'TYPE' => 'STRING',
		'DEFAULT' => '300'
	),
		
    'HIDE_DELAY_DETAIL' => Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('HIDE_DELAY_DETAIL'),
		'TYPE' => 'STRING',
		'DEFAULT' => '600'
	),
	
    'SCROLL_ZOOM' => Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('SCROLL_ZOOM'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	),
	
    'EASING' => Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('EASING'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	),
	
    'ZOOM_TYPE' => Array(
		'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
		'NAME' => GetMessage('ZOOM_TYPE'),
		'TYPE' => 'LIST',
		'VALUES' => $arZoomTypes,
		'REFRESH' => 'Y',
		'DEFAULT' => 'window'
	)
);


if (empty($arCurrentValues['ZOOM_TYPE'])) {
	$bWindow = ($arTemplateParameters['ZOOM_TYPE']['DEFAULT'] == 'window');
	$bLens   = ($arTemplateParameters['ZOOM_TYPE']['DEFAULT'] == 'lens');
} else {
	$bWindow = ($arCurrentValues['ZOOM_TYPE'] == 'window');
	$bLens   = ($arCurrentValues['ZOOM_TYPE'] == 'lens');
}

if ($bWindow || $bLens) {
	$arTemplateParameters += array(
	    'BORDER_COLOUR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => '888888'
		),
		
		'BORDER_SIZE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'TYPE' => 'STRING',
			'DEFAULT' => '4'
		)
	);
}

if ($bWindow) {
	$arTemplateParameters['BORDER_COLOUR']['NAME'] = GetMessage('BORDER_COLOUR');
	$arTemplateParameters['BORDER_SIZE']['NAME'] = GetMessage('BORDER_SIZE');
	
	$arTemplateParameters += array(
		'WINDOW_POSITION' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_POSITION'),
			'TYPE' => 'LIST',
			'VALUES' => $arPosition,
			'DEFAULT' => '1'
		),
	
		'WINDOW_BG_COLOUR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_BG_COLOUR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => 'FFFFFF'
		),
	
		'WINDOW_WIDTH' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_WIDTH'),
			'TYPE' => 'STRING',
			'DEFAULT' => '400'
		),
	
		'WINDOW_HEIGHT' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_HEIGHT'),
			'TYPE' => 'STRING',
			'DEFAULT' => '400'
		),
	
		'WINDOW_OFFSET_X' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_OFFSET_X'),
			'TYPE' => 'STRING',
			'DEFAULT' => '0'
		),
	
		'WINDOW_OFFSET_Y' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_OFFSET_Y'),
			'TYPE' => 'STRING',
			'DEFAULT' => '0'
		),
	
		'WINDOW_FADE_IN' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_FADE_IN'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => '500'
		),
		
		'WINDOW_FADE_OUT' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('WINDOW_FADE_OUT'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => '700'
		),
	
	    'SHOW_LENS' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('SHOW_LENS'),
			'TYPE' => 'CHECKBOX',
			'REFRESH' => 'Y',
			'DEFAULT' => 'Y'
		)
	);
}

if (empty($arCurrentValues['SHOW_LENS'])) {
	$bShowLens = ($arTemplateParameters['SHOW_LENS']['DEFAULT'] == 'Y');
} else {
	$bShowLens = ($arCurrentValues['SHOW_LENS'] == 'Y');
}
$bShowLens &= $bWindow;

if ($bLens) {
	$arTemplateParameters['BORDER_COLOUR']['NAME'] = GetMessage('LENS_BORDER_COLOUR');
	$arTemplateParameters['BORDER_SIZE']['NAME'] = GetMessage('LENS_BORDER_SIZE');
	
	$arTemplateParameters += array(
	    'CONTAIN_LENS_ZOOM' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('CONTAIN_LENS_ZOOM'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),
		
		'LENS_SIZE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_SIZE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '200'
		)
	);
}

if ($bLens || $bShowLens) {
	$arTemplateParameters += array(
	    'LENS_FADE_IN' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_FADE_IN'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => 'false'
		),
		
		'LENS_FADE_OUT' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_FADE_OUT'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => 'false'
		),
	
		'LENS_SHAPE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_SHAPE'),
			'TYPE' => 'LIST',
			'VALUES' => $arLensShapes,
			'REFRESH' => 'Y',
			'DEFAULT' => 'square'
		)
	);
}

if (empty($arCurrentValues['LENS_SHAPE'])) {
	$bLensRound = ($arTemplateParameters['LENS_SHAPE']['DEFAULT'] == 'round');
} else {
	$bLensRound = ($arCurrentValues['LENS_SHAPE'] == 'round');
}

if ($bShowLens && $bLensRound) {
	$arTemplateParameters += array(
		'LENS_SIZE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_SIZE_WINDOW'),
			'TYPE' => 'STRING',
			'DEFAULT' => '200'
		)
	);
}


if ($bShowLens) {
	$arTemplateParameters += array(
	    'LENS_COLOUR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_COLOUR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => 'FFFFFF'
		),
		
	    'LENS_OPACITY' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_OPACITY'),
			'TYPE' => 'LIST',
			'VALUES' => $arOpacity,
			'DEFAULT' => '0.4'
		),
		
	    'LENS_BORDER_COLOUR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_BORDER_COLOUR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => '000000'
		),
		
		'LENS_BORDER_SIZE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('LENS_BORDER_SIZE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '1'
		),

	    'TINT' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('TINT'),
			'TYPE' => 'CHECKBOX',
			'REFRESH' => 'Y',
			'DEFAULT' => 'N'
		)
	);
}

if (empty($arCurrentValues['TINT'])) {
	$bTint = ($arTemplateParameters['TINT']['DEFAULT'] == 'Y');
} else {
	$bTint = ($arCurrentValues['TINT'] == 'Y');
}


if ($bTint) { 
	$arTemplateParameters += array(
	    'TINT_COLOUR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('TINT_COLOUR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => '333333'
		),
		
	    'TINT_OPACITY' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('TINT_OPACITY'),
			'TYPE' => 'LIST',
			'VALUES' => $arOpacity,
			'DEFAULT' => '0.4'
		),
	
		'TINT_FADE_IN' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('TINT_FADE_IN'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => 'false'
		),
		
		'TINT_FADE_OUT' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('TINT_FADE_OUT'),
			'TYPE' => 'LIST',
			'VALUES' => $arFade,
			'DEFAULT' => 'false'
		)
	);
}
?>
