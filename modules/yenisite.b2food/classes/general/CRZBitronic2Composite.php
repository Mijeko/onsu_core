<?php

/**
 * 
 * 
 *
 */
use Bitrix\Main\Data\StaticCacheProvider;
use Bitronic2\Catalog\CookiesUtils;
use Bitronic2\Mobile;

class CRZBitronic2Composite {

	static private $serviceParams = array();

	/**
	 * @param $arParams - array of options
	 * @see '/install/wizards/yenisite/bitronic2/site/templates/romza_bitronic2/js/back-end/ajax_core.js' - "RZB2.ajax.spinner" for full list of available options
	 * @return string
	 */
	public static function insertCompositLoader(array $arParams = array())
	{
		/*
		$arParams += array(
			'width' => '2',
			'radius' => '5'
		);*/
		$str = '<span class="rz-loader"';
		foreach ($arParams as $key => $value) {
			$str .= ' data-' . $key . '="' . $value . '"';
		}
		$str .= '></span>';
		return $str;
	}
}
if (!class_exists('CacheProvider')) {
	class CacheProvider extends StaticCacheProvider
	{
		public function getCachePrivateKey()
		{
			return self::getCachePrefix();
		}

		public function setUserPrivateKey()
		{
			\CHTMLPagesCache::setUserPrivateKey(self::getCachePrefix(), 0);
		}

		public function isCacheable()
		{
			return true;
		}

		public function onBeforeEndBufferContent()
		{

		}

		public static function getCachePrefix()
		{
			static $isMobile;
			if (!isset($isMobile)) {
				Mobile::Init();
				$isMobile = Mobile::isMobile();
			}
			
			$key = NULL;
			$key = ($isMobile) ? 'mobile' : 'desktop';

			global $rz_b2_options;
			if(is_array($rz_b2_options)
			&& is_array($rz_b2_options['GEOIP'])
			&& is_array($rz_b2_options['GEOIP']['ITEM'])) {
				$key .= $rz_b2_options['GEOIP']['ITEM']['ID'];
			}
			
			if(defined("IS_CATALOG_LIST") && IS_CATALOG_LIST)
			{
				$view = CookiesUtils::getView();
				$page_count = CookiesUtils::getPageCount();
				$sort = CookiesUtils::getSort();
				$sort = $sort['ACTIVE'];
				$by = CookiesUtils::getSortBy();

				$key .= "/view_{$view}_pagecount_{$page_count}_sort_{$sort}_by_{$by}/";
			}
			
			return $key;
		}

		// $SITE_ID = SITE_ID - for situation if module update, but wizard not started
		public static function getObject($SITE_ID = SITE_ID)
		{
			if($SITE_ID == SITE_ID)
			{
				\CHTMLPagesCache::setUserPrivateKey(self::getCachePrefix(), 0);
				return new self();
			}
		}

	}
}