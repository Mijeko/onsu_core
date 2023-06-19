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
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?$templateData['CNT_ELEMENTS'] = count($arResult['ITEMS'])?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<div class="comment-wrap" itemprop="review" itemscope itemtype="http://schema.org/Review">
		<header>
			<div class="date">
				<meta itemprop="datePublished" content="<?=$arItem['FIELDS']["DATE_CREATE"]?>">
				<?=$arItem['FIELDS']["DATE_CREATE"]?>
			</div><?

			if ($GLOBALS['USER']->IsAdmin()):?>

			<div class="date" style="margin-right: 20px"><?=$arItem['DISPLAY_PROPERTIES']['IP']['DISPLAY_VALUE']?></div><?

			endif?>
			<div class="user-info" itemprop="author" itemscope itemtype="http://schema.org/Person">
				<div itemscope itemtype="http://schema.org/ImageObject" class="avatar">
					<?if(!empty($arItem['AVATAR'])):?>
						<img itemprop="contentUrl" src="<?=$arItem['AVATAR']?>" alt="<?=$arItem['DISPLAY_PROPERTIES']['NAME']['DISPLAY_VALUE']?>">
					<?else:?>
						<i class="flaticon-user12"></i>
					<?endif?>
				</div>
				<div class="name" itemprop="name">
					<?=$arItem['DISPLAY_PROPERTIES']['NAME']['DISPLAY_VALUE']?>
				</div>
			</div>
		</header>
		<div class="content">
			<?
			/* TODO
			<div class="rating">
				..............
			</div>
			*/
			?>
			<?
			/* TODO
			<div class="pros">
				..............
			</div>
			<div class="cons">
				..............
			</div>
			*/
			?>
			<?// COMMENT TEXT
			$explode_pos = 400;
			if(strlen($arItem["PREVIEW_TEXT"]) > $explode_pos)
			{
				$explode_pos = strpos($arItem["PREVIEW_TEXT"], " ", $explode_pos);
				$arItem['TEXT_SHORT'] = substr($arItem["PREVIEW_TEXT"], 0, $explode_pos);
				$arItem['TEXT_FULL'] = substr($arItem["PREVIEW_TEXT"], $explode_pos);
			}
			else
			{
				$arItem['TEXT_SHORT'] = $arItem["PREVIEW_TEXT"];
			}
			?>
			<div class="comment-text" itemprop="reviewBody">
				<?=$arItem['TEXT_SHORT']?>
				<div class="hidden-block">
					<?=$arItem['TEXT_FULL']?>
				</div>
			</div>
		</div><!-- /.content -->
		<footer>
			<?if(strlen($arItem['TEXT_FULL']) > 0):?>
				<span class="link">
					<span class="text when-closed"><?=GetMessage('BITRONIC2_REVIEW_TEXT_SHOW')?></span>
					<span class="text when-opened"><?=GetMessage('BITRONIC2_REVIEW_TEXT_HIDE')?></span>
				</span>
			<?endif?>
			<?
			/* TODO
			<span class="usefulness">
				..................
			</span>
			*/
			?>
		</footer>
	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
