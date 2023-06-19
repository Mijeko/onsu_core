<?php
use Bitrix\Main,
	Bitrix\Main\Application,
	Bitrix\Catalog\CatalogViewedProductTable,
	Bitrix\Main\Localization\Loc as Loc,
	Bitrix\Main\SystemException;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//Loc::loadMessages(__FILE__);

CBitrixComponent::includeComponentClass("bitrix:catalog.bigdata.products");

class CatalogBigdataProductsNoinjectComponent extends CatalogBigdataProductsComponent
{
	/**
	 * Prepare Component Params
	 *
	 * @param array $params
	 * @return array
	 */
	public function onPrepareComponentParams($params)
	{
		$pathThief = new ReflectionProperty('CBitrixComponent', '__relativePath');
		$pathThief->setAccessible(true);
		$pathThief->setValue($this, '/bitrix/catalog.bigdata.products');

		return parent::onPrepareComponentParams($params);
	}

	protected function getInjectedJs($items, $uniqId)
	{
		return "";
	}
}