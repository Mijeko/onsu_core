<?php
use Bitrix\Main,
	Bitrix\Main\Application,
	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc as Loc,
	Bitrix\Main\SystemException;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//Loc::loadMessages(__FILE__);

CBitrixComponent::includeComponentClass("bitrix:sale.personal.order.detail");

class CBitrixPersonalOrderDetailBitronic2Component extends CBitrixPersonalOrderDetailComponent
{
	protected static $bResizer = false;

	/**
	 * Function checks and prepares all the parameters passed. Everything about $arParam modification is here.
	 *
	 * @param mixed[] $arParams List of unchecked parameters
	 * @return mixed[] Checked and valid parameters
	 */
	public function onPrepareComponentParams($arParams)
	{
		$pathThief = new ReflectionProperty('CBitrixComponent', '__relativePath');
		$pathThief->setAccessible(true);
		$pathThief->setValue($this, '/bitrix/sale.personal.order.detail');

		$arParams = parent::onPrepareComponentParams($arParams);

		if (static::$bResizer = Loader::includeModule('yenisite.resizer2')) {
			$arParams['PICTURE_WIDTH'] = 0;
			$arParams['PICTURE_HEIGHT'] = 0;
			$this->tryParseInt($arParams["RESIZER_SET"], 6);
		}

		return $arParams;
	}

	/**
	 * For each basket items it fills information about properties stored in
	 *
	 * @param mixed[] $arBasketItems		List of basket items
	 * @param mixed[] $arElementIds			Array of element id
	 * @param mixed[] $arSku2Parent			Mapping between sku ids and their parent ids
	 * @return void
	 */
	public function obtainBasketPropsElement(&$arBasketItems, $arElementIds, $arSku2Parent)
	{
		parent::obtainBasketPropsElement($arBasketItems, $arElementIds, $arSku2Parent);
		if (!static::$bResizer) return;

		if (self::isNonemptyArray($arBasketItems))
		{
			foreach ($arBasketItems as &$item)
			{
				// catalog-specific logic farther
				if(!$this->cameFromCatalog($item))
				{
					continue;
				}

				// resampling picture
				if (!is_array($item['PICTURE'])) $item['PICTURE'] = array();

				$item['PICTURE']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($item['PRODUCT_ID'], $this->arParams['RESIZER_SET']);
			}
		}
	}
}