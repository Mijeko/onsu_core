<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

global $arComponentParameters;

$arComponentParameters['GROUPS']['YENISITE_RESIZER2_PARAMETERS']= array(
	'NAME' => GetMessage('YENISITE_RESIZER2_PARAMETERS'),
	'SORT' => 2000,
);


$arTemplateParameters = array(
    'SET_SMALL_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_SMALL_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet, 
	'DEFAULT' => '3'       
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
		
	'AUTO_SCROLL' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('AUTO_SCROLL'),
			'TYPE' => 'STRING',
			'DEFAULT' => '0'			
		),	
	'SCROLL_MORE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('SCROLL_MORE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '1'			
		),
	'MOUSE_WHEEL' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('MOUSE_WHEEL'),
			'TYPE' => 'LIST',
			'VALUES' => array(	'true'=>GetMessage('YES'),
								'false'=>GetMessage('NO'),),	
			'DEFAULT' => 'true',
		),
	'EFFECTS' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('EFFECTS'),
			'TYPE' => 'LIST',
			'VALUES' => array(	'bounceout'=>'bounceout',
								'backout'=>'backout',
								'easeinout'=>'easeinout',
								''=>GetMessage('NO'),),	
			'DEFAULT' => '',
		),
	'SPEED' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('SPEED'),
			'TYPE' => 'STRING',
			'DEFAULT' => '400'			
		),
	'VISIBLE' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('VISIBLE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '3'			
		),
	'VERTICAL' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('VERTICAL'),
			'TYPE' => 'LIST',
			'VALUES' => array(	'true'=>GetMessage('YES'),
								'false'=>GetMessage('NO'),),	
			'DEFAULT' => 'false',
		),
	'CIRCULAR' => Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('CIRCULAR'),
			'TYPE' => 'LIST',
			'VALUES' => array(	'true'=>GetMessage('YES'),
								'false'=>GetMessage('NO'),),	
			'DEFAULT' => 'true',
		),
		

);
?>
