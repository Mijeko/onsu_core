<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->SetViewTarget('service_section_list');

if (0 < $arResult["SECTIONS_COUNT"]):
?>
			<div class="text-center">
				<div id="services__filters" class="isotope__button-group">
					<button class="button is-checked" data-filter="*"><?= GetMessage('BITRONIC2_SERVICES_ALL') ?></button> 
<?
	foreach ($arResult['SECTIONS'] as &$arSection): ?>
					<button class="button" data-filter=".js-<?= $arSection['ID'] ?>"><?= $arSection['NAME'] ?></button><?
	endforeach ?>

				</div>
			</div>
<?
	unset($arSection);
endif;

$this->EndViewTarget();
?>
