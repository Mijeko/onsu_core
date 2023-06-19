<?php
namespace Yenisite\Core;

use Bitrix\Main\Web\HttpClient;

/**
 * Class Ajax
 * @package Yenisite\Core
 */
class Ajax
{
	const CACHE_TIME = 1209600;
	const CACHE_DIR = 'romza_settings';
	public static $UPDATE_PARAM = 'rz_update_catalog_parameters_cache';
	private static $SITE_ID = null;

	/**
	 * Saves component parameters into CPHPCache for use on AJAX requests and other pages
	 * @param string|\CBitrixComponent|\CBitrixComponentTemplate $componentName - need to know name of component which parameters are being save
	 * @param array $arParams - array of values to save in cache
	 * @param string $ID - additional ID of cache if needed
	 * @param string|bool $SITE_ID
	 */
	public static function saveParams($componentName, array $arParams = array(), $ID = '', $SITE_ID = false, $redirectEnable = false)
	{
		$clean_check = false;
	
		if (!empty($SITE_ID)) {
			self::$SITE_ID = $SITE_ID;
		}
		if ($componentName instanceof \CBitrixComponent) {
			$componentName = $componentName->getName();
		} elseif ($componentName instanceof \CBitrixComponentTemplate) {
			$componentName = $componentName->__component->getName();
		}
		$_cachePath = self::getCachePath($componentName);
		$_cacheString = self::getUniqString($componentName, $ID);

		$arCacheParams = self::_getParams($componentName, $ID, '', $SITE_ID);

		if ($arParams != $arCacheParams) {
				// if not - clean cache
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$redirectPage = $redirectEnable;
				$cache = new \CPHPCache();
				$cache->Clean($_cacheString, $_cachePath, self::CACHE_DIR);
			}
		$ob = new \CPHPCache();

		// write down params if there is no cache
		if ($ob->StartDataCache(self::CACHE_TIME, $_cacheString, $_cachePath, array(),
			self::CACHE_DIR)
		) { //LOL, bitrix don't use $basedir from InitCache() here
			$ob->EndDataCache($arParams);
		}
		unset($ob);

		if ($redirectEnable == true && $redirectPage==true) LocalRedirect();
	}

	/**
	 * Load component parameters from CPHPCache for use on AJAX requests and other pages
	 * an empty result tries to appeal to the $paramsPage, and receive data again
	 * @param string|\CBitrixComponent|\CBitrixComponentTemplate $componentName - need to know name of component which parameters are being save
	 * @param string $ID - additional ID of cache if needed
	 * @param string $paramsPage - page to extra request if params is empty
	 * @param string|bool $SITE_ID
	 * @return array - all saved parameters
	 */
	public static function getAllParams($componentName, $ID = '', $paramsPage = '', $SITE_ID = false)
	{
		return self::_getParams($componentName, $ID, $paramsPage, $SITE_ID);
	}

	/**
	 * Load component parameters from CPHPCache for use on AJAX requests and other pages
	 * an empty result tries to appeal to the $paramsPage, and receive data again
	 * @param string|\CBitrixComponent|\CBitrixComponentTemplate $componentName - need to know name of component which parameters are being save
	 * @param string $ID - additional ID of cache if needed
	 * @param string $paramsPage - page to extra request if params is empty
	 * @param string|bool $SITE_ID
	 * @return array - all parameters except starting with tilde
	 */
	public static function getParams($componentName, $ID = '', $paramsPage = '', $SITE_ID = false)
	{
		$arParams = self::_getParams($componentName, $ID, $paramsPage, $SITE_ID);

		foreach ($arParams as $k => $v) {
			if (strncmp('~', $k, 1)) continue;
			unset($arParams[$k]);
		}
		return $arParams;
	}

	/**
	 * Load component parameters from CPHPCache for use on AJAX requests and other pages
	 * an empty result tries to appeal to the $paramsPage, and receive data again
	 * @param string|\CBitrixComponent|\CBitrixComponentTemplate $componentName - need to know name of component which parameters are being save
	 * @param string $ID - additional ID of cache if needed
	 * @param string $paramsPage - page to extra request if params is empty
	 * @param string|bool $SITE_ID
	 * @return array - only parameters starting with tilde
	 */
	public static function getTildeParams($componentName, $ID = '', $paramsPage = '', $SITE_ID = false)
	{
		$arParams = self::_getParams($componentName, $ID, $paramsPage, $SITE_ID);

		foreach ($arParams as $k => $v) {
			if (!strncmp('~', $k, 1)) continue;
			unset($arParams[$k]);
		}
		return $arParams;
	}

	/**
	 * Load component parameters from CPHPCache for use on AJAX requests and other pages
	 * an empty result tries to appeal to the $paramsPage, and receive data again
	 * @param string|\CBitrixComponent|\CBitrixComponentTemplate $componentName - need to know name of component which parameters are being save
	 * @param string $ID - additional ID of cache if needed
	 * @param string $paramsPage - page to extra request if params is empty
	 * @param string|bool $SITE_ID
	 * @return array
	 */
	protected static function _getParams($componentName, $ID = '', $paramsPage = '', $SITE_ID = false)
	{
		if (!empty($SITE_ID)) {
			self::$SITE_ID = $SITE_ID;
		}

		if ($componentName instanceof \CBitrixComponent) {
			$componentName = $componentName->getName();
		} elseif ($componentName instanceof \CBitrixComponentTemplate) {
			$componentName = $componentName->__component->getName();
		}

		$_cachePath = self::getCachePath($componentName);
		$_cacheString = self::getUniqString($componentName, $ID);

		$arParams = array();
		$retry = false;
        $countRequest = 0;

        $ob = new \Yenisite\Core\Cache();
        do {
            if ($ob->InitCache(self::CACHE_TIME, $_cacheString, $_cachePath, self::CACHE_DIR)) {
                $arParams = $ob->GetVars();
                if (!($ob->getClearCache() || $ob->getClearCacheSession())) break;
            }

            if ($retry) break;
            if (empty($paramsPage)) break;
            if (!empty($_GET[self::$UPDATE_PARAM]) || !empty($_REQUEST[self::$UPDATE_PARAM])) break;

            $protocol = \CMain::IsHTTPS() ? 'https://' : 'http://';

            $obHTTP = new HttpClient();
            $obHTTP->get($protocol . $_SERVER['HTTP_HOST'] . $paramsPage);

        } while ($retry = true);


		unset($ob);
		return $arParams;
	}

	protected static function checkSiteID()
	{
		if (empty(self::$SITE_ID)) {
			if (!defined('SITE_ID')) {
				throw new \DomainException('SITE_ID must be defined before save or load arParams');
			} else {
				self::$SITE_ID = SITE_ID;
			}
		}
	}

	protected static function getCachePath($componentName)
	{
		self::checkSiteID();
		return '/' . self::$SITE_ID . '/' . str_replace(':', '_', $componentName);
	}

	protected static function getUniqString($componentName, $ID = '')
	{
		self::checkSiteID();
		return self::$SITE_ID . '_' . $componentName . (empty($ID) ? '' : '_' . $ID);
	}

	public static function getAjaxBase(\CBitrixComponentTemplate $cmp, $id)
	{
		global $APPLICATION;
		$curURL = $APPLICATION->GetCurPageParam('', array(
			'show_page_exec_time',
			'bitrix_include_areas',
			'clear_cache'
		));
		$arParams = array(
			'CMP' => $cmp->__component->__name,
			'PAGE' => $curURL,
			'TMPL' => $cmp->__name,
		);
		if (!empty($id)) {
			$arParams['ID'] = $id;
		}
		return $arParams;
	}

	public static function printAjaxDataAttr(\CBitrixComponentTemplate $cmp, $id = '', $attrName = 'ajax')
	{
		$arParams = self::getAjaxBase($cmp, $id);
		$sParams = Tools::GetEncodedArParams($arParams);
		echo ' data-' . $attrName . '="' . $sParams . '"';
	}

	public static function isAjax()
	{
		return Tools::isAjax();
	}
}