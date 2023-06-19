<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!empty($templateData['NAME'])){
    $APPLICATION->SetTitle($templateData['NAME']);
    $APPLICATION->SetPageProperty("title",$templateData['NAME']);
}
