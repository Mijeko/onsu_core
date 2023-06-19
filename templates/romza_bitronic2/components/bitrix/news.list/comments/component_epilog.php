<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!empty($templateData['CNT_ELEMENTS']) && !empty($this->__parent->arResult)){
    $this->__parent->arResult['CNT_ELEMENTS'] = $templateData['CNT_ELEMENTS'];
}