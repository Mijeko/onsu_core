<?php
namespace Bitronic2\Catalog;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class CookiesUtils {

	static private $_cookiePrefix = '';
	static public $_arDefVal = array(
		'view' => "blocks",
		'page_count' => 12,
		'sort' => 'name',
		'by' => 'asc',
	);
	
	static public $_arPageCount = array(12, 24, 36, 48, 60, 100);
	static public $_arSort = array('name', 'created', 'shows', 'price'/*, 'sales'*/);
	

	public static function setPrefix($prefix)
	{
		self::$_cookiePrefix = $prefix;
	}
	
	public static function getCookieName($name = false)
	{
		$arNames = array(
			'view' => self::$_cookiePrefix.'view',
			'page_count' => self::$_cookiePrefix.'page_count',
			'sort' => self::$_cookiePrefix.'sort',
			'by' => self::$_cookiePrefix.'by',
		);
		
		if($name !== false && array_key_exists($name, $arNames))
		{
			return $arNames[$name];
		}
		else
		{
			return $arNames;
		}
	}
	
	public static function getView($default = false)
	{
		global $APPLICATION;
		$arView = array("blocks", "list", "table");
		$cookieName = self::getCookieName('view');
		
		if (!empty($_REQUEST["view"]))
		{
			// If params is exist ( view?clear_cache=Y )
			if (strpos($_REQUEST["view"], '?') !== false)
			{
				$tmp = explode('?', $_REQUEST["view"]);
				$_REQUEST["view"] = $tmp[0];
			}
			if (in_array($_REQUEST["view"], $arView))
			{
				$view = htmlspecialchars($_REQUEST["view"]);
			}
			else
			{
				$view = self::$_arDefVal['view'];
			}
			$APPLICATION->set_cookie($cookieName, $view);
		}
		else
		{
			$view = $APPLICATION->get_cookie($cookieName) ? $APPLICATION->get_cookie($cookieName) : $default;
			if (!in_array($view, $arView))	$view = self::$_arDefVal['view'];
		}
		
		return $view;
	}
	
	public static function getPageCount()
	{
		global $APPLICATION;
		$cookieName = self::getCookieName('page_count');
		
		if (!empty($_REQUEST["page_count"]))
		{
			if(($pos = strpos($_REQUEST["page_count"],"?"))!==false) $_REQUEST["page_count"] = substr($_REQUEST["page_count"], 0, $pos);
			$_REQUEST["page_count"] = $_REQUEST["page_count"];
			if (in_array($_REQUEST["page_count"], self::$_arPageCount))
			{
				$page_count = htmlspecialchars($_REQUEST["page_count"]);
			}
			else
			{
				$page_count = self::$_arDefVal['page_count'];
			}
			$APPLICATION->set_cookie($cookieName, $page_count);
		}
		else
		{
			$page_count = in_array($APPLICATION->get_cookie($cookieName), self::$_arPageCount) ? $APPLICATION->get_cookie($cookieName) : self::$_arDefVal['page_count'];
		}
		
		return $page_count;
	}
	
	public static function getSort($arParams = array(), $isSku = false, $default = false)
	{
		global $APPLICATION, $_COOKIE;
		$arReturn = array();
		$hasCatalog = \Bitrix\Main\Loader::includeModule('catalog');
		$cookieName = self::getCookieName('sort');
		
		if (!isset($isSku) && isset($arParams['IBLOCK_ID'])) {
			if ($hasCatalog) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$mxResult = \CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
				if (is_array($mxResult)) {
					$isSku = true;
				} else {
					$isSku = false;
				}
			}
		}

		$sort = $_REQUEST['sort'] ? htmlspecialchars($_REQUEST['sort']) : $APPLICATION->get_cookie($cookieName);
		$sort = $sort ? $sort : $default;
		if(isset($arParams['DEFAULT_ELEMENT_SORT_BY']) && !empty($arParams['DEFAULT_ELEMENT_SORT_BY']))
		{
			$sort = $sort ? $sort : $arParams['DEFAULT_ELEMENT_SORT_BY'];
		}
		$sort = $sort ? $sort : self::$_arDefVal['sort'];
		$sort = strtolower($sort);
		$APPLICATION->set_cookie($cookieName, $sort);
		$_COOKIE[\COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_".$cookieName] = $sort;
		$arReturn['ACTIVE'] = $sort;
		
		if($sort == 'price')
		{
			if($isSku)
			{
				$sort = 'PROPERTY_'.\CRZBitronic2Handlers::$_sortProps['MIN'];
			}
			else
			{
				$sort = !empty($arParams['LIST_PRICE_SORT']) ? $arParams['LIST_PRICE_SORT'] : 'CATALOG_PRICE_1';
			}
		}
		$arReturn['FOR_PARAMS'] = $sort;
		
		return $arReturn;
	}
	
	public static function getSortBy($default = false)
	{
		global $APPLICATION, $_COOKIE;
		$cookieName = self::getCookieName('by');
		
		$arBy = array('asc', 'desc');
		$by = $_REQUEST['by'] ? htmlspecialchars($_REQUEST['by']) : $APPLICATION->get_cookie($cookieName);
		$by = $by ? $by : $default;
		$by = $by && in_array(strtolower($by), $arBy) ? $by : self::$_arDefVal['by'];
		$by = strtolower($by);
		$APPLICATION->set_cookie($cookieName, $by);
		$_COOKIE[\COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_".$cookieName] = $by;

		return $by;
	}
}
