<?
include_once "include_stop_statistic.php";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "include_module.php";

CBitrixComponent::includeComponentClass('yenisite:settings.panel');

function YNS_getCacheProvider()
{
	foreach (GetModuleEvents("main", "OnGetStaticCacheProvider", true) as $arEvent) {
		$provider = ExecuteModuleEventEx($arEvent);
		if (is_object($provider) && $provider instanceof \Bitrix\Main\Data\StaticCacheProvider) {
			return $provider;
		}
	}

	return null;
}

include_once 'include_options.php';
include 'log_composite.php';

$arOptions = \CHTMLPagesCache::getOptions();
$cookieName = $arOptions['COOKIE_PK'];
$cacheProvider = YNS_getCacheProvider();
$privateKey = $cacheProvider !== null ? $cacheProvider->setUserPrivateKey() : null;


$GLOBALS['APPLICATION']->RestartBuffer();

echo json_encode(array('name' => $cookieName, 'value' => $privateKey));
die();