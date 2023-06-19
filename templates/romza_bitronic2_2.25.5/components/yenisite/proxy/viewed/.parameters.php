<?
if (\Bitrix\Main\Loader::includeModule('yenisite.core')) {
    \Yenisite\Core\Resize::AddResizerParams(array('ITEM'), $arTemplateParameters);
}