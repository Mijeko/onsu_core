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
		
    'SHOW_CLOSE_BUTTON'	=> Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('SHOW_CLOSE_BUTTON'),
			'TYPE' => 'LIST',
			'VALUES' => array('true'=>GetMessage('YES'),'false'=>GetMessage('NO')),		
			'DEFAULT' => 'true'
		),
	'CLOSE_ON_CLICK'	=> Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('CLOSE_ON_CLICK'),
			'TYPE' => 'LIST',
			'VALUES' => array('true'=>GetMessage('YES'),'false'=>GetMessage('NO')),	
			'DEFAULT' => 'true'
		),
	'GALLERY_COUNTS'	=> Array(
			'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
			'NAME' => GetMessage('GALLERY_COUNTS'),
			'TYPE' => 'LIST',
			'VALUES' => array('true'=>GetMessage('YES'),'false'=>GetMessage('NO')),	
			'DEFAULT' => 'true'
		),
);
?>
