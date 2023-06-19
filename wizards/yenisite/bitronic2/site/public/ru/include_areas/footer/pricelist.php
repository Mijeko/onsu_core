<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $rz_b2_options;

$path = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'pricelist/price' . ($rz_b2_options['GEOIP']['ITEM']['ID'] ?: '');
$arExt = array('xls', 'doc', 'pdf');

foreach ($arExt as $ext) {
    $bExists = @file_exists($path.'.'.$ext);
    if (!$bExists) continue;

    $path .= '.' . $ext;
    break;
}

if ($bExists && CModule::IncludeModule('yenisite.core')) {
    $fileSize = \Yenisite\Core\Tools::rusFilesize(fileSize($path));
} else {
    $ext = 'xls';
}

?>
<div class="pricelist-download">
    <a href="#SITE_DIR#pricelist/" class="link-bd link-std flaticon-link49">
       Скачать прайс-лист<?
        if(!empty($rz_b2_options['GEOIP']['ITEM'])):?> (<?=$rz_b2_options['GEOIP']['ITEM']['NAME']?>)<?endif?></a>
    <?if($bExists):?>
        <span class="pricelist-info">(.<?=$ext?>, <?=$fileSize?>)</span>
        <div class="small">(от <?=date ("d.m.Y", filemtime($path))?>)</div>
    <?endif?>
</div>
