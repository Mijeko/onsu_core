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
use Yenisite\Core\Tools;
$this->setFrameMode(true);
global $rz_b2_options;
?>
<div class="big-slider container drag-section sBigSlider <?=$rz_b2_options["big-slider-width"]?>" id="big-slider-wrap" data-big-slider-width="<?=$rz_b2_options["big-slider-width"]?>"
     data-order="<?=$rz_b2_options["order-sBigSlider"]?>">
    <div id="catalog-at-side" class="catalog-at-side full">
        <?if($rz_b2_options["menu-catalog"]=="side"):?>
            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php", "PATH" => SITE_DIR."include_areas/header/menu_catalog.php"), false, array("HIDE_ICONS"=>"Y"));?>
        <?endif?>
    </div>
    <?include $arResult['FILE']?>
</div>