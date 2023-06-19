<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('yenisite.resizer2');
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
    $arSet[$arr['id']] = '['.$arr['id'].'] '.$arr['NAME'];
}

$arEffect = array(
    'FadeInOut' => GetMessage('FADEINOUT'),
    'Jalousie'  => GetMessage('JALOUSIE'),
    'Ladder'    => GetMessage('LADDER'),
    'Scroll'    => GetMessage('SCROLL'),
    'Deck'      => GetMessage('DECK'),
    'Jaw'       => GetMessage('JAW'),
    'DiagonalCells' => GetMessage('DIAGONALCELLS'),
    'RandomCells'   => GetMessage('RANDOMCELLS')
);

$arDirection = array(
    'Horizontal' => GetMessage('HORIZONTAL'),
    'Vertical'   => GetMessage('VERTICAL')
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
        'DEFAULT' => '4'
    ),
    
    'SHOW_DESCRIPTION' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('SHOW_DESCRIPTION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
    
    'EFFECT' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('EFFECT'),
        'TYPE' => 'LIST',
        'VALUES' => $arEffect,
        'DEFAULT' => 'FadeInOut'
    ),
    
    'DIRECTION' => Array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('DIRECTION'),
        'TYPE' => 'LIST',
        'VALUES' => $arDirection,
        'DEFAULT' => 'Horizontal'
    )
);
?>
