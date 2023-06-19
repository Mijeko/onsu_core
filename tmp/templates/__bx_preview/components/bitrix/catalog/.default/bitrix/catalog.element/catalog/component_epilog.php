<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Page\AssetLocation;
use \Yenisite\Core\Page as YCPage;
use \Yenisite\Core\Tools;
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
if (!empty($templateData['CNT_ELEMENTS']) && !empty($this->__parent->arResult)){
    $this->__parent->arResult['CNT_ELEMENTS'] = $templateData['CNT_ELEMENTS'];
}

if ($arParams['SET_CANONICAL_URL'] === 'Y' && $templateData["CANONICAL_PAGE_URL"])
{
    $APPLICATION->SetPageProperty('canonical',$templateData["CANONICAL_PAGE_URL"]);
}

//$arResult['PROPERTIES'] = $templateData['PROPERTIES'];
if (is_array($templateData['OG'])) {
	foreach ($templateData['OG'] as $property => $content) {
		if (empty($content)) continue;

		YCPage::setOGProperty($property, $content);
	}
}

if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Bitrix\Main\Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		$frame = new \Bitrix\Main\Page\FrameBuffered("element_epilog_currency");
		$frame->begin('');
	?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
<?
		$frame->end();
	}
}

if($arParams['DETAIL_YM_API_USE'] == 'Y')
{
	$this->__parent->arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"] = $templateData['TURBO_YANDEX_LINK'];
}

//Needs for iblock.vote to not break composite
IncludeAJAX();

$frame = new \Bitrix\Main\Page\FrameBuffered("element_epilog_JCCatalogItem");
$frame->begin('');?>
<?
// Set active offer from $_GET parameter
do {
	if (empty($templateData['OFFERS_KEYS'])) break;
	if (empty($arParams['OFFER_VAR_NAME']))  break;

	$pid = $_GET[$arParams['OFFER_VAR_NAME']];
	if (empty($pid)) break;
	if (!array_key_exists($pid, $templateData['OFFERS_KEYS'])) break;

	$keySelected = $templateData['OFFERS_KEYS'][$pid];

} while (0);
if (empty($_SESSION['RZ_DETAIL_JS_FILE']) && isset($keySelected)) {
	$templateData['arJSParams']['OFFER_SELECTED'] = $keySelected;
}
$jsString = 'var '. $templateData['strObName'] .' = new JCCatalogItem('. CUtil::PhpToJSObject($templateData['arJSParams'], false, true) .');';
if (!empty($_SESSION['RZ_DETAIL_JS_FILE'])) {
	$bytes = fwrite($_SESSION['RZ_DETAIL_JS_FILE'], $jsString);
	if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
		$templateData['jsFile'] = false;
		if (file_exists($templateData['jsFullPath'])) {
			unlink($templateData['jsFullPath']);
		}
	}
	fclose($_SESSION['RZ_DETAIL_JS_FILE']);
	unset($_SESSION['RZ_DETAIL_JS_FILE']);
}

if (!empty($templateData['jsFile']) && file_exists($templateData['jsFullPath']) && !Tools::isAjax() && !Tools::isComposite() && Tools::isComponentCacheOn($arParams)):
?>
<script type="text/javascript" src="<?=$templateData['jsFile']?>?<?echo @filemtime($templateData['jsFullPath'])?>"></script><?
	if (isset($keySelected)):?>
	<script>
		<?=$templateData['strObName']?>.offerNum = <?=$keySelected?>;
		if(!!<?=$templateData['strObName']?>.bInit)<?=$templateData['strObName']?>.SetCurrent();
	</script><?
	endif;?>
<? else: ?>
<script type="text/javascript">
	<?=$jsString?>
	<? if (isset($keySelected) && $keySelected != $templateData['arJSParams']['OFFER_SELECTED']): ?>
	<?=$templateData['strObName']?>.offerNum = <?=$keySelected?>;
	if(!!<?=$templateData['strObName']?>.bInit)<?=$templateData['strObName']?>.SetCurrent();
	<? endif ?>
</script>
<?
endif;

// update view counter
if (isset($templateData['strObName'])):?>

	<script type="text/javascript">
		if(typeof <?=$templateData['strObName']?> != 'undefined')
		{
			<?=$templateData['strObName']?>.allowViewedCount(true);
		}
	</script>
<?
endif;
$frame->end();
?>
