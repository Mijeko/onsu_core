<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
				<div class="content cert-wrap"><?

	foreach ($arSection["ITEMS"] as $arItem):

		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCST_ELEMENT_DELETE_CONFIRM')));
		?>

					<div class="cert-item-wrap">
						<div class="cert-item" id="<?= $this->GetEditAreaId($arItem['ID']) ?>"><?
		if (is_array($arItem['PREVIEW_PICTURE'])): ?>

							<div itemscope itemtype="http://schema.org/ImageObject" class="img">
								<img itemprop="contentUrl" class="lazy" data-original="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"><?
			if (is_array($arItem['DETAIL_PICTURE'])): ?>

								<div class="zoom flaticon-zoom62"
									data-toggle="modal"
									data-target="#modal-big-cert"
									data-bigimg="<?= $arItem['DETAIL_PICTURE']['SRC'] ?>"
									data-name="<?= $arItem['NAME'] ?>">
									<span class="hide"><?= $arItem['DETAIL_TEXT'] ?: $arItem['PREVIEW_TEXT'] ?></span>
								</div><?
			endif ?>

							</div><?
		endif ?>

							<div class="name"><?= $arItem['NAME'] ?></div>
							<div class="desc"><?= $arItem['PREVIEW_TEXT'] ?></div>
						</div>
					</div><?
	endforeach ?>

				</div>
			</section>
<? endforeach ?>
<? $this->SetViewTarget('bitronic2_modal_license') ?>
	<div class="modal fade modal-form modal-big-cert" id="modal-big-cert" tabindex="-1">
		<div class="modal-dialog big-cert-wrap">
			<button class="btn-close" data-toggle="modal" data-target="#modal-big-cert">
				<span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
				<i class="flaticon-close47"></i>
			</button>
			<span class="img-placeholder"></span>
			<div class="div desc">
			    <span class="value"></span>
			</div>
		</div>
	</div>
<? $this->EndViewTarget() ?>