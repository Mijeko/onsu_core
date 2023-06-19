<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}


$arOpacity = array('0'=>'100%','0.1'=>'90%','0.2'=>'80%','0.3'=>'70%','0.4'=>'60%','0.5'=>'50%','0.6'=>'40%','0.7'=>'30%','0.8'=>'20%','0.9'=>'10%','1'=>'0%');
$arOverlay = array('true' => 'Y', 'false' => 'N');

global $arComponentParameters;

$arComponentParameters['GROUPS']['YENISITE_RESIZER2_PARAMETERS']= array(
	'NAME' => GetMessage('YENISITE_RESIZER2_PARAMETERS'),
	'SORT' => 2000,
);


$arTemplateParameters = array(
    'SET_BIG_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_BIG_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet,        
	'DEFAULT' => '1'
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
			'DEFAULT' => 'Y',			
		),
	'PIRO_SPEED' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('PIRO_SPEED'),
			'TYPE' => 'STRING',
			'DEFAULT' => '700'			
		),
	
	'BG_ALPHA' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('BG_ALPHA'),
			'TYPE' => 'LIST',
			'VALUES' => $arOpacity,	
			'DEFAULT' => '0.5',
		),	
	'PIRO_SCROLL'	=> Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('PIRO_SCROLL'),
			'TYPE' => 'LIST',
			'VALUES' => array('true'=>GetMessage('YES'),'false'=>GetMessage('NO')),
			'DEFAULT' => 'false',
	),
	'PIRO_STYLE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('PIRO_STYLE'),
			'TYPE' => 'LIST',
			'VALUES' => array(	'style1'=>GetMessage('STYLE1'),
								'style2'=>GetMessage('STYLE2'),),	
			'DEFAULT' => 'style1',
		),
	'PIRO_COLOR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('PIRO_COLOR'),
			'TYPE' => 'COLORPICKER',
			'DEFAULT' => 'FFFFFF'			
		),
);
?>
