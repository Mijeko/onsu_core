<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<? if ($arResult["FILE"] <> '' && strlen($address = $APPLICATION->GetFileContent($arResult["FILE"])) > 0) : ?>
	<a href="javascript:;" class="address pseudolink with-icon" data-toggle="modal" data-target="#modal_address-on-map" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		<i class="flaticon-location4"></i>
		<? $arAddress = explode('#', $address) ?>
		<span class="link-text"><span itemprop="addressLocality"><?= $arAddress[0] ?></span><span
				itemprop="streetAddress"><?= (isset($arAddress[1]) ? $arAddress[1] : '') ?></span></span>
	</a>
<? endif ?>