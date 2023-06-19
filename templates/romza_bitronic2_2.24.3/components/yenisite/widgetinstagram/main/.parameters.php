<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (\Bitrix\Main\Loader::includeModule('yenisite.core')) {
    \Yenisite\Core\Resize::AddResizerParams(array('ITEM'), $arTemplateParameters);
}