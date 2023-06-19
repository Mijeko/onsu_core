<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

global $rz_b2_options;

$arHTMLEntities = array(
	'RUB' => '<span class="b-rub">'.GetMessage('BITRONIC2_RUB_CHAR').'</span>',
	'USD' => '&#36;',
	'EUR' => '&euro;',
	'UAH' => '&#8372;',
	'BYR' => 'Br'
);
$id = 'currency-switch';
?>
<?if ($rz_b2_options['currency-switcher'] === 'Y'):?>
<div id="<?=$id?>" class="currency-switch" data-popup=">.currency-list" data-currency-switch-enabled="true">
<?
$frame = $this->createFrame($id, false)->begin('');
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';
?>
				<span class="desc"><?=GetMessage('RZ_CURRENCY')?>:</span>
				<span class="value" data-value="<?=$arResult['ACTIVE_CURRENCY']?>">
					<span class="text"><?if(array_key_exists($arResult['ACTIVE_CURRENCY'], $arHTMLEntities)):?><?=$arHTMLEntities[$arResult['ACTIVE_CURRENCY']]?> <?endif?>(<?=$arResult['ACTIVE_CURRENCY']?>)</span>
				</span>
				<ul class="currency-list um_popup"><?

					foreach ($arResult['CURRENCIES'] as $currency):
						$active = ($currency === $arResult['ACTIVE_CURRENCY']) ? ' active' : '';
						?>

					<li class="value<?=$active?>" data-value="<?=$currency?>"><span class="text"><?if(array_key_exists($currency, $arHTMLEntities)):?><?=$arHTMLEntities[$currency]?> <?endif?>(<?=$currency?>)</span></li><?

					endforeach?>

				</ul>
				<form action="#" method="post" style="display:none">
					<input type="hidden" name="RZ_CURRENCY_NEW" value="<?=$arResult['ACTIVE_CURRENCY']?>">
				</form>
<?$frame->end()?>
			</div>
<? endif ?>
