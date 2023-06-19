<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(false);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
?>
<aside class="account-menu col-sm-3 col-xl-2" id="account-menu">
	<div class="profile clearfix">
		<a href="">
			<div class="avatar">
				<img src="<?=$arResult['USER']['PERSONAL_PHOTO']?>" alt="<?=GetMessage('BITRONIC2_AVATAR')?>" title="<?=GetMessage('BITRONIC2_AVATAR')?>">
			</div>
			<span class="name"><?=$arResult['USER']['PRINT_NAME']?></span>
		</a>
		<? if (!empty($arResult['USER']['EMAIL'])): ?>
			<div>
				<span class="login">[<?=$arResult['USER']['EMAIL']?>]</span>
			</div>
		<? endif ?>
	</div><!-- /profile -->
	<ul>
	<? foreach ($arResult as $key => $arItem):
		if ($key === 'USER') continue;
		$arItem['LINK_DATA'] = ($arItem['LINK'] == '#favorite' ? ' data-popup="#popup_favorites"' : '') ?>

		<li>
			<a<?= ($arItem['SELECTED'] ? ' class="active"' : '') ?> href="<?= $arItem['LINK'] ?>"<?= $arItem['LINK_DATA'] ?>><?

			if (!empty($arItem['PARAMS']['ICON_SVG'])): ?>

				<span class="svg-wrap">
					<svg><use xlink:href="#<?= $arItem['PARAMS']['ICON_SVG'] ?>"></use></svg>
				</span><?

			endif ?>

				<span class="text"><?= $arItem['TEXT'] ?></span>
			</a>
		</li>
	<? endforeach ?>

	</ul>
</aside><!-- /account-menu -->