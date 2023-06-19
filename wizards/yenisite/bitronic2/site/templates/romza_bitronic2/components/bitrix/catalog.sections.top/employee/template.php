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
?>
<?
foreach ($arResult["SECTIONS"] as $arSection): ?>
			<section>
				<div class="title-h2 text-center"><?= $arSection["NAME"] ?></div>
				<div class="staff-wrap"><?

	foreach ($arSection["ITEMS"] as $arItem):

		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCST_ELEMENT_DELETE_CONFIRM')));
		?>

					<div class="staff-item-wrap">
						<div class="staff-item" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
							<div itemscope itemtype="http://schema.org/ImageObject" class="img"><?
		if (is_array($arItem['PICTURE'])): ?>

								<img itemprop="contentUrl" class="lazy" data-original="<?= $arItem["PICTURE"]["SRC"] ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>"
                                     title="<?= $arItem["PICTURE"]["ALT"] ?>" alt="<?= $arItem["PICTURE"]["ALT"] ?>"><?
		endif ?>

							</div>
							<div class="name-wrap"><span class="name"><?= $arItem['NAME'] ?></span></div>
							<div class="state"><?= $arItem['PREVIEW_TEXT'] ?></div>
							<div class="staff-item__contacts"><?

							foreach ($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty): ?>

								<div class="<?= $pid ?>">
									<i class="<?= $arProperty['XML_ID'] ?> btn-silver btn-toggle"></i>
									<div class="text"><?= $arProperty['VALUE'] ?></div>
								</div><?
							
							endforeach ?>

							</div>
						</div>
					</div><?
	endforeach ?>

				</div>
			</section>
<? endforeach ?>
