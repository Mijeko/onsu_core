<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
    $arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arPlay = array(
    'false' => GetMessage('NO_AUTOPLAY'),
    '2000' => '2',
    '3000' => '3',
    '4000' => '4',
    '5000' => '5',
    '6000' => '6',
    '7000' => '7',
    '8000' => '8',
    '9000' => '9'
);


$arTemplateParameters = array(
    'SET_BIG_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_BIG_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet,
        'DEFAULT' => '1'
    ),

    'SET_DETAIL' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('SET_DETAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arSet,
        'DEFAULT' => '2'
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
    
    'HEIGHT' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('HEIGHT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '470'
    ),
    
    'WIDTH' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('WIDTH'),
        'TYPE' => 'LIST',
        'VALUES' => array('-1' => GetMessage('AUTO')),
        'ADDITIONAL_VALUES' => 'Y',
        'DEFAULT' => '516'
    ),
    
    'LIGHTBOX' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('LIGHTBOX'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'SHOW_COUNTER' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('SHOW_COUNTER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'AUTOPLAY' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('AUTOPLAY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPlay,
        'DEFAULT' => 'false'
    )
);