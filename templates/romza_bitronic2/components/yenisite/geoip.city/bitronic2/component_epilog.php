<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$hasNotify = (int)$APPLICATION->get_cookie('YNS_IS_YOUR_CITY', 'RZ');
if($hasNotify == 0 && $arParams['YOURCITY_POPUP'] !== 'N') {
	$replace = 'data-state="shown"';
} else {
	$replace = '';
}
$arResult['TEMPLATE'] = str_replace("#SHOW_POPUP#", $replace, $arResult['TEMPLATE']);
?>

<?= $arResult['TEMPLATE'] ?>
<script>
		YS.GeoIP.AutoConfirm = <?if($arParams['AUTOCONFIRM'] == "Y"):?>true<?else:?>false<?endif?>;
</script><?
