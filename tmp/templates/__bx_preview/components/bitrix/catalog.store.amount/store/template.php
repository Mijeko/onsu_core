<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
$arParams['MANY_VAL'] = 10;
$arParams['AVERAGE_VAL'] = 5;

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';

if(!function_exists('rzSwitchAmount')) {
	function rzSwitchAmount($amount, $arParams) {
		$amount = (float)$amount;
		switch (true) {
			case ($amount >= $arParams['MANY_VAL']):
				$class = 'many';
				$title = $arParams['MANY_NAME'];
				break;
			case ($amount >= $arParams['AVERAGE_VAL']):
				$class = 'average';
				$title = $arParams['AVERAGE_NAME'];
				break;
			case (($amount < $arParams['AVERAGE_VAL'] && $amount != 0)):
				$class = 'few';
				$title = $arParams['FEW_NAME'];
				break;
			default:
				$class = 'none';
				$title = $arParams['NONE_NAME'];
		}
		return array($class,$title);
	}
}
if(isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1 && isset($arResult["JS"]["SKU"])):
	return;?>
	<script type="text/javascript">
		jQuery(window).load(function(){
			require(['back-end/ajax/catalog_store'], function(){
				window.obStoreAmount = new JCCatalogStoreSKU({
					'AR_ALL_RESULT': <?=CUtil::PhpToJSObject($arResult["SKU"])?>,
					'PHONE_MESSAGE': <?=CUtil::PhpToJSObject(GetMessage('S_PHONE'))?>,
					'SCHEDULE_MESSAGE': <?=CUtil::PhpToJSObject(GetMessage('S_SCHEDULE'))?>,
					'AMOUNT_MESSAGE': <?=CUtil::PhpToJSObject(GetMessage('S_AMOUNT'))?>,
					'CONTAINER_ID': "catalog_store_amount_div_<?=$arParams["CONTAINER_ID_POSTFIX"]?>_<?=$arParams["ELEMENT_ID"]?>",
				});
			});
		});
	</script>
	<?unset($arResult["STORES"]);
endif;?>
<?if(!empty($arResult["STORES"])):?>
<span id="catalog_store_amount_div_<?=$arParams["CONTAINER_ID_POSTFIX"]?>_<?=$arParams["ELEMENT_ID"]?>" class="hidden-xs">
	<?foreach($arResult["STORES"] as $pid => $arItem):?>
        <? if ($arParams['SHOW_EMPTY_STORE'] == 'N' && isset($arItem['REAL_AMOUNT']) && $arItem['REAL_AMOUNT'] <= 0){
            $empty = true;
            continue;
        } else{
            $empty = false;
        } ;?>
        <div class="store">
			<span class="text"
				data-popup="^.store>.store-info"
				data-position="centered bottom"><?=$arItem["TITLE"]?></span>
			<div class="store-info notification-popup" data-loaded="true">
				<div class="content">
					<header><?= GetMessage('S_BLOCK_TITLE') ?></header>
					<div class="quantity"><?= $arItem["TITLE"] ?> <?= $arItem["DESCRIPTION"] ? "({$arItem["DESCRIPTION"]})" : '' ?>:
						<?switch ($arParams['STORE_DISPLAY_TYPE']):
							case 'graphic':
								?>
								<? $arAmount = rzSwitchAmount($arItem["AMOUNT"], $arParams); ?>
								<div class="store-indicator <?= $arAmount[0] ?>">
									<div class="many average few"></div>
									<div class="many average"></div>
									<div class="many"></div>
								</div>
								<?break;
							case 'numeric':
								?>
								<strong><?= (int)$arItem["AMOUNT"] ?></strong>
								<?break;
							default:
							case 'text': ?>
								<strong><?= getStringCatalogStoreAmount($arItem["AMOUNT"], $arParams['MIN_AMOUNT']) ?></strong>
								<? break ?>
							<? endswitch ?>
					</div>
					<div class="address"><?= $arItem["ADDRESS"] ?></div>
				</div>
			</div>
		</div>
	<?endforeach;?>
    <?if ($empty):?>
        <strong><?= GetMessage('ALL_STORES_EMPTY')?></strong>
    <?endif;?>
</span>
<?endif;

// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";