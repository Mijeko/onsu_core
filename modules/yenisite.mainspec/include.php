<?
IncludeModuleLangFile(__FILE__);

Class CYenisiteMainspec 
{
	static public function TabList()
	{
		static $tabList = array();

		if (empty($tabList)) {
			$tabList = array(
				'NEW' => GetMessage("METHOD_TAB_NEW"),
				'HIT' => GetMessage("METHOD_TAB_HIT"),
				'SALE' => GetMessage("METHOD_TAB_SALE"),
				'BESTSELLER' => GetMessage("METHOD_TAB_BESTSELLER"),
			);
		}
		return $tabList;
	}

	static public function SmartStickerParams()
	{
		return array(
			"MAIN_SP_ON_AUTO_NEW" => array(
				"PARENT" => "STICKERS",
				"NAME" => GetMessage("MAIN_SP_ON_AUTO_NEW"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => 'Y',
				"REFRESH" => 'Y',
			),
			"STICKER_NEW" => array(
				"PARENT" => "STICKERS",
				"NAME" 	 => GetMessage('STICKER_NEW'),
				"TYPE"	 => "STRING",
				"DEFAULT" => '14',
			),
			"STICKER_HIT" => array(
				"PARENT" => "STICKERS",
				"NAME" 	 => GetMessage('STICKER_HIT'),
				"TYPE"	 => "STRING",
				"DEFAULT" => '100',
			),
			"STICKER_BESTSELLER" => array(
				"PARENT" => "STICKERS",
				"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
				"TYPE"	 => "STRING",
				"DEFAULT" => '3',
			),
		);
	}

	static public function StickerPropParams(array $arPropList)
	{
		$arParams = array();
		$TabList = self::TabList();
		foreach($TabList as $tab => $tabName)
		{
			$arParams['TAB_PROPERTY_'.$tab] = array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('TAB_PROPERTY').$TabList[$tab],
				"TYPE"	 => "LIST",
				'VALUES' => $arPropList,			
				"DEFAULT" => $tab,
				"ADDITIONAL_VALUES" => 'Y',
			);
		}
		return $arParams;
	}

	static public function CatalogParams(array $arPropList, array &$arCurrentValues)
	{
		$arParams = array_merge(self::SmartStickerParams(), self::StickerPropParams($arPropList));
		if (isset($arCurrentValues['MAIN_SP_ON_AUTO_NEW']) && 'N' == $arCurrentValues['MAIN_SP_ON_AUTO_NEW']){
			$arParams['STICKER_NEW']['HIDDEN'] = 'Y';
			$arParams['STICKER_HIT']['HIDDEN'] = 'Y';
			$arParams['STICKER_BESTSELLER']['HIDDEN'] = 'Y';
		}
		return $arParams;
	}
}
?>
