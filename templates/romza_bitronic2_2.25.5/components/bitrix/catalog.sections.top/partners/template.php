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
				<div class="partners-wrap"><?

	foreach ($arSection["ITEMS"] as $arItem):

		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCST_ELEMENT_DELETE_CONFIRM')));
		?>

					<div class="partners-item-wrap">
						<div class="partners-item" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
							<div itemscope itemtype="http://schema.org/ImageObject" class="img"><?
		if (is_array($arItem['PICTURE'])): ?>

								<img itemprop="contentUrl" class="lazy" data-original="<?= $arItem["PICTURE"]["SRC"] ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?= $arItem["PICTURE"]["ALT"] ?>" title="<?= $arItem["PICTURE"]["ALT"] ?>"><?
		endif ?>

							</div>
							<div class="name-wrap"><?
		if (!empty($arItem['PROPERTIES']['LINK']['VALUE'])): ?>

								<a href="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ?>" class="name">
									<span><?= $arItem['NAME'] ?></span>
								</a><?
		else: ?>

								<span class="name"><?= $arItem['NAME'] ?></span><?
		endif ?>

							</div>
							<div class="desc">
								<?= $arItem['PREVIEW_TEXT'] ?>

								<div class="partners-item__contacts"><?

		foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty):
			switch ($arProperty['CODE']):
				case 'EMAIL': ?>

									<a href="mailto:<?= $arProperty['VALUE'] ?>" class="contact">
										<span><?= $arProperty['VALUE'] ?></span>
									</a><?
				break;
				case 'PHONE': ?>

									<a href="tel:<?= preg_replace('/[^\\d]+/', '', $arProperty['VALUE']) ?>" class="contact">
										<span><?= $arProperty['VALUE'] ?></span>
									</a><?
				break;
				default: ?>

									<span class="contact"><?= $arProperty['VALUE'] ?></span><?
				break;
			endswitch;
		endforeach ?>

							</div>

							</div>
						</div>
					</div><?
	endforeach ?>

				</div>
			</section>
<? endforeach ?>
