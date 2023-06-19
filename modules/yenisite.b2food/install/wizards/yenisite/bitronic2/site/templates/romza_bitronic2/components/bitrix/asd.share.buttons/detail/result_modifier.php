<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
if (\CMain::IsHTTPS()){
    $arResult["ASD_PICTURE"] = str_replace('http','https',$arResult["ASD_PICTURE"]);
    $arResult["ASD_PICTURE_NOT_ENCODE"] = str_replace('http://','https://',$arResult["ASD_PICTURE_NOT_ENCODE"]);
    $arResult["ASD_URL"] = str_replace('http','https',$arResult["ASD_URL"]);
    $arResult["ASD_URL_NOT_ENCODE"] = str_replace('http://','https://',$arResult["ASD_URL_NOT_ENCODE"]);
}