<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
    $arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arSize = array(
    '[50, 50]' => '50 x 50',
    '[75, 75]' => '75 x 75',
    '[100, 100]' => '100 x 100',
    '[120, 120]' => '120 x 120',
    '[150, 150]' => '150 x 150'
);
$arZoom = array(
    '[2, 2]' => 'x2',
    '[2, 3]' => 'x3',
    '[2, 4]' => 'x4',
    '[2, 5]' => 'x5',
    '[2, 6]' => 'x6',
    '[2, 7]' => 'x7',
    '[2, 8]' => 'x8',
    '[2, 9]' => 'x9'
);

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
    
    'MAGNIFIER_SIZE' => array(
        'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
        'NAME' => GetMessage('MAGNIFIER_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => $arSize,
        'DEFAULT' => '[75, 75]'
    ),
    
    'POWER_RANGE' => array(
        'PARENT' => 'YENISITE_RESIZER2_PARAMETERS',
        'NAME' => GetMessage('POWER_RANGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arZoom,
        'DEFAULT' => '[2, 7]'
    ),
    
    'SHOW_DESCRIPTION' => Array(
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
	)
);
?>
