<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
// echo "<pre style='text-align:left;'>"; print_r($arResult); echo "</pre>";

$curPage = $APPLICATION->GetCurPage();
?>

<div id="popup_account-menu" class="top-line-popup popup_account-menu" data-darken >
	<ul>
	<? foreach ($arResult as $key => $arItem):
		$liClass = $arItem['PARAMS']['ITEM_CLASS'];
		?>

		<li<?= empty($liClass) ? '' : ' class="' . $liClass . '"' ?>>
			<a href="<?=$arItem['LINK']?>">
				<span class="svg-wrap">
					<svg><use xlink:href="#<?= $arItem['PARAMS']['ICON_SVG'] ?>"></use></svg>
				</span>
				<span class="text"><?=$arItem['TEXT']?></span>
			</a>
		</li>
	<? endforeach ?>

	</ul>	
</div>

