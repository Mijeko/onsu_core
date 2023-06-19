<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/* section.all */

if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("yenisite.mainspec"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();

if(is_array($arCurrentValues["IBLOCK_TYPE"]))
{
	foreach($arCurrentValues["IBLOCK_TYPE"] as $key=>$val)
	{
		if($val){
			$val = str_replace("#SITE_ID#", SITE_ID, $val);
			$val = preg_replace('\'={}#."', '', $val);
			
			$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), array("ACTIVE"=>"Y", "TYPE" => $val));

			while($arr=$rsIBlock->Fetch())
				$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
		}
	}
}

$arProperty_LNS = array();
$arProperty_N = array();
$arProperty_X = array();
$arProperties_ALL_wsys = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	if($arr["PROPERTY_TYPE"] != "F")
		$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];

	if($arr["PROPERTY_TYPE"]=="N")
		$arProperty_N[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];

	if($arr["PROPERTY_TYPE"]!="F")
	{
		if($arr["MULTIPLE"] == "Y")
			$arProperty_X[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		elseif($arr["PROPERTY_TYPE"] == "L")
			$arProperty_X[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		elseif($arr["PROPERTY_TYPE"] == "E" && $arr["LINK_IBLOCK_ID"] > 0)
			$arProperty_X[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arProperty_UF = array();
$arSProperty_LNS = array();
$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arProperty_UF[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
	if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
		$arSProperty_LNS[$FIELD_NAME] = $arProperty_UF[$FIELD_NAME];
}

if(!is_array($arCurrentValues["IBLOCK_ID"]) && $arCurrentValues["IBLOCK_ID"] > 0)
{

	$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
	$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
	$arProperty_Offers = array();
	if($OFFERS_IBLOCK_ID)
	{
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$OFFERS_IBLOCK_ID));
		while($arr=$rsProp->Fetch())
		{
			if($arr["PROPERTY_TYPE"] != "F")
				$arProperty_Offers[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}
	}
	
	
}else{
		$OFFERS_IBLOCK_ID = 0;
		$arProperty_Offers = array();
		$arSKU = false;
		foreach($arIBlock as $key=>$val)
		{
			$arOffers = CIBlockPriceTools::GetOffersIBlock($key);
			if(is_array($arOffers))
			{
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$OFFERS_IBLOCK_ID));
				while($arr=$rsProp->Fetch())
				{
					if ($arr['XML_ID'] == 'CML2_LINK')
						continue;
					if($arr["PROPERTY_TYPE"] != "F")
						$arProperty_Offers[$arr["CODE"]] = "[".$arr["ID"]."][".$arr["CODE"]."] ".$arr["NAME"];
				}
			}
			
		}
}

$arPrice = array();
$arPrice2 = array();
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) {
	
	$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	$arPrice2["CATALOG_PRICE_".$arr["ID"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
}
else
{
	$arPrice = $arProperty_N;
}

$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);

/*IS BITRONIC*/
$is_bitronic = false;
if(CModule::IncludeModule('yenisite.bitronic')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitroniclite')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitronicpro')) $is_bitronic = true;

/*tab*/
$TabList = CYenisiteMainspec::TabList();

$TabListSORT = array(
	'NEW' => 100,
	'HIT' => 200,
	'SALE' => 300,
	'BESTSELLER' => 400,
);
	
	
/*PROPS*/
if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
	$system_properties = array('ARTICLE','HOLIDAY','SHOW_MAIN','HIT','SALE','PHOTO','DESCRIPTION','MORE_PHOTO','NEW','KEYWORDS','TITLE','FORUM_TOPIC_ID','FORUM_MESSAGE_CNT','PRICE_BASE','H1','YML','FOR_ORDER','WEEK_COUNTER','WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating');
	$arProperties_ALL	= array() ;
	$arProperties_LNS	= array() ; // list, number, string
	$arProperties_E		= array() ; // link element

	$dbProperties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"])));
	while ($arProperty = $dbProperties->GetNext())
	{
		$arProperties_ALL_wsys[$arProperty["CODE"]] = "[{$arProperty['CODE']}] {$arProperty['NAME']}" ;
		if(!in_array($arProperty['CODE'], $system_properties) && strpos($arProperty['CODE'], 'TURBO_') !== 0)
		{
			$arProperties_ALL[$arProperty["CODE"]] = "[{$arProperty['CODE']}] {$arProperty['NAME']}" ;

			if(in_array($arProperty["PROPERTY_TYPE"], array("L", "N", "S")))
				$arProperties_LNS[$arProperty["CODE"]] = $arProperties_ALL[$arProperty["CODE"]] ;
			if($arProperty['PROPERTY_TYPE'] == 'E')
				$arProperties_E[$arProperty["CODE"]] = $arProperties_ALL[$arProperty["CODE"]] ;
		}
	}

}
if($is_bitronic)
{
	$arValues['PRODUCT_DISPLAY_MODE']['VALUES'] = array(
		'D' => GetMessage('CP_BCS_TPL_DML_DEFAULT'),
		'N' => GetMessage('CP_BCS_TPL_DML_SIMPLE'),
		'SB' => GetMessage('CP_BCS_TPL_DML_EXT')
	);
	$arValues['PRODUCT_DISPLAY_MODE']['DEFAULT'] = 'D';
}
else
{
	$arValues['PRODUCT_DISPLAY_MODE']['VALUES'] = array(
		'N' => GetMessage('CP_BCS_TPL_DML_SIMPLE'),
		'SB' => GetMessage('CP_BCS_TPL_DML_EXT')
	);
	$arValues['PRODUCT_DISPLAY_MODE']['DEFAULT'] = 'N';
}
	

/* ---------------------------------------------------------- $arComponentParameters ----------------------------------------*/	
$arComponentParameters = array();
$arComponentParameters["GROUPS"] = array();

//GROUPS
	
	/*template*/
	$arComponentParameters["GROUPS"]["STICKERS"]= array(
		"NAME" => GetMessage("STICKER_GROUP"),
		"SORT" => '200',
	);
	$arComponentParameters["GROUPS"]["IBLOCKVOTE"]= array(
		"NAME" => GetMessage("IBLOCKVOTE_GROUP"),
		"SORT" => '4500',
	);	
	/*component*/
	$arComponentParameters["GROUPS"]["PRICES"]= array(
		"SORT" => '500',
		"NAME" => GetMessage("IBLOCK_PRICES"),
	);

		
//PARAMS

	/*BASE*/
	$arComponentParameters_base = array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"REFRESH" => "Y",
			"DEFAULT"  => array(
				"catalog_%",
				"catalog",
				"#SITE_ID#_%"
			),			
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"PROPERTY_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperties_ALL,
			"ADDITIONAL_VALUES" => "Y",
			"SIZE" => min(10, 1 + count($arProperties_ALL))
		),
		"SECTION_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_SECTION_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"SECTION_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_SECTION_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),/*
		"SECTION_USER_FIELDS" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BCS_SECTION_USER_FIELDS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arProperty_UF,
		),*/
		"INCLUDE_SUBSECTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BCS_INCLUDE_SUBSECTIONS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_ALL_WO_SECTION" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CP_BCS_SHOW_ALL_WO_SECTION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
		"HIDE_NOTAVAILABLE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HIDE_NOTAVAILABLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),		
		"HIDE_WITHOUTPICTURE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HIDE_WITHOUTPICTURE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),				
	);	
	
	/*STICKERS*/
	$arComponentParameters_stickers = CYenisiteMainspec::SmartStickerParams();
	/*array(
		"MAIN_SP_ON_AUTO_NEW" => array(),
		"STICKER_NEW" => array(),
		"STICKER_HIT" => array(),
		"STICKER_BESTSELLER" => array(),
	);*/

	/*VISUAL*/	
	$arComponentParameters_view = array(
		"SHOW_TABS" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SHOW_TABS"),
			"TYPE" => "LIST",
			"SIZE" => "4",
			"MULTIPLE" => "Y",
			"REFRESH" => "Y",
			"DEFAULT" => array_keys($TabList),
			"VALUES" => $TabList,
		),
		"DEFAULT_TAB" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("DEFAULT_TAB"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => 'NEW',
			"VALUES" => $TabList,
		),
		"BLOCK_VIEW_MODE" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("BLOCK_VIEW_MODE"),
			"TYPE" => "LIST",
			"VALUES" => array(
				'popup' => GetMessage("BLOCK_VIEW_MODE_POPUP"),
				'nopopup' => GetMessage("BLOCK_VIEW_MODE_NOPOPUP"),
			),
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => '={$ys_options["block_view_mode"]}',
		),	
		"TABS_INDEX" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("TABS_INDEX"),
			"TYPE" => "LIST",
			"VALUES" => array(
				'one_slider' => GetMessage("TABS_INDEX_ONE_SLIDER"),
				'list' => GetMessage("TABS_INDEX_MULTISLIDE"),
			),
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => '={$ys_options["tabs_index"]}',
		),
		"COLOR_SCHEME" => Array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("COLOR_SCHEME"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"red" => GetMessage("COLOR_SCHEME_RED"), 
				"green" => GetMessage("COLOR_SCHEME_GREEN"), 
				"ice" => GetMessage("COLOR_SCHEME_BLUE"), 
				"metal" => GetMessage("COLOR_SCHEME_METAL"), 
				"pink" => GetMessage("COLOR_SCHEME_PINK"), 
				"yellow" => GetMessage("COLOR_SCHEME_YELLOW")
			),
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => '={$ys_options["color_scheme"]}',
		),
		"IMAGE_SET" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("IMAGE_SET"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => "3"
		),
		"IMAGE_SET_BIG" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("IMAGE_SET_BIG"),
			"TYPE" => "LIST",
			"VALUES" => $list,
			"DEFAULT" => "4"
		),
		"ELEMENT_SORT_FIELD" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"shows" => GetMessage("IBLOCK_SORT_SHOWS"),
				"sort" => GetMessage("IBLOCK_SORT_SORT"),
				"timestamp_x" => GetMessage("IBLOCK_SORT_TIMESTAMP"),
				"name" => GetMessage("IBLOCK_SORT_NAME"),
				"id" => GetMessage("IBLOCK_SORT_ID"),
				"active_from" => GetMessage("IBLOCK_SORT_ACTIVE_FROM"),
				"active_to" => GetMessage("IBLOCK_SORT_ACTIVE_TO"),
			),
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "rand",
		),
		"ELEMENT_SORT_ORDER" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "Y",
		),
		"LIST_PRICE_SORT" => array(
			"PARENT" => "VISUAL",
			"NAME"   => GetMessage("PRICE_SORT"),
			"TYPE"   => "LIST",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arPrice2,
			"DEFAULT" => "CATALOG_PRICE_1"
		),
		"SHOW_ELEMENT" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SHOW_ELEMENT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		
		"OFFERS_FIELD_CODE" => CIBlockParameters::GetFieldCode(GetMessage("CP_BCS_OFFERS_FIELD_CODE"), "VISUAL"),
		"OFFERS_PROPERTY_CODE" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCS_OFFERS_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_Offers,
			"ADDITIONAL_VALUES" => "Y",
		),
		
		
		"OFFERS_SORT_FIELD" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCS_OFFERS_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"shows" => GetMessage("IBLOCK_FIELD_SHOW_COUNTER"),
				"sort" => GetMessage("IBLOCK_FIELD_SORT"),
				"timestamp_x" => GetMessage("IBLOCK_FIELD_TIMESTAMP_X"),
				"name" => GetMessage("IBLOCK_FIELD_NAME"),
				"id" => GetMessage("IBLOCK_FIELD_ID"),
				"active_from" => GetMessage("IBLOCK_FIELD_ACTIVE_FROM"),
				"active_to" => GetMessage("IBLOCK_FIELD_ACTIVE_TO"),
			),
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "rand",
		),
		"OFFERS_SORT_ORDER" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCS_OFFERS_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "Y",
		),
		
		'PRODUCT_DISPLAY_MODE' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BCS_TPL_PRODUCT_DISPLAY_MODE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => $arValues['PRODUCT_DISPLAY_MODE']['DEFAULT'],
			'VALUES' => $arValues['PRODUCT_DISPLAY_MODE']['VALUES']
		),

		'USE_MOUSEWHEEL' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BCS_USE_MOUSEWHEEL'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
	);

	$arComponentParameters_view += CYenisiteMainspec::StickerPropParams($arProperties_ALL_wsys);
	$arComponentParameters_view_sort = 
	$arComponentParameters_view_text = array();

	if(empty($arCurrentValues['SHOW_TABS'])) $arCurrentValues['SHOW_TABS'] = array_keys($TabList);
	foreach($arCurrentValues['SHOW_TABS'] as $tab)
	{
		$arComponentParameters_view_sort['TAB_SORT_'.$tab] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('SORT_INDEX').$TabList[$tab],
			"TYPE"	 => "STRING",
			"DEFAULT" => $TabListSORT[$tab],
		);

		$arComponentParameters_view_text['TAB_TEXT_'.$tab] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('TAB_TEXT').$TabList[$tab],
			"TYPE"	 => "STRING",
			"DEFAULT" => $TabList[$tab],
		);
	}
	$arComponentParameters_view = array_merge($arComponentParameters_view, $arComponentParameters_view_sort, $arComponentParameters_view_text);


	if($arCurrentValues['PRODUCT_DISPLAY_MODE'] == "SB" || $arCurrentValues['PRODUCT_DISPLAY_MODE'] == "D" || empty($arCurrentValues['PRODUCT_DISPLAY_MODE']))
	{
		$arComponentParameters_view['OFFER_TREE_PROPS'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BCS_TPL_OFFER_TREE_PROPS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arProperty_Offers
		);
	}

	/*URL_TEMPLATES*/
	$arComponentParameters_url = array(
		"SECTION_URL" => CIBlockParameters::GetPathTemplateParam(
			"SECTION",
			"SECTION_URL",
			GetMessage("IBLOCK_SECTION_URL"),
			"",
			"URL_TEMPLATES"
		),
		"DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
			"DETAIL",
			"DETAIL_URL",
			GetMessage("IBLOCK_DETAIL_URL"),
			"",
			"URL_TEMPLATES"
		),
		"BASKET_URL" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_BASKET_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/basket.php",
		),
		"ACTION_VARIABLE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_ACTION_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "action",
		),
		"PRODUCT_ID_VARIABLE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_PRODUCT_ID_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "id",
		),
		"PRODUCT_QUANTITY_VARIABLE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("CP_BCS_PRODUCT_QUANTITY_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "quantity",
		),
		"PRODUCT_PROPS_VARIABLE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("CP_BCS_PRODUCT_PROPS_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "prop",
		),
		"SECTION_ID_VARIABLE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_SECTION_ID_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "SECTION_ID",
		),
	);

	/*PRICE*/	
	$arComponentParameters_price = array(
		'PRODUCT_PROPERTIES' => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("CP_BC_PRODUCT_PROPERTIES"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperties_ALL,
			"SIZE" => "4",
			"DEFAULT" => ""
		),
		"PRICE_CODE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
			"DEFAULT" => array(
				0 => "BASE",
				1 => "0",
				2 => "1",
				3 => "2",
			),
		),		
		/*"PRICE_TABLE_NAME" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("PRICE_TABLE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "prices",
		),*/
		"USE_PRICE_COUNT" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_USE_PRICE_COUNT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			),
		"SHOW_PRICE_COUNT" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_SHOW_PRICE_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
		),
		"PRICE_VAT_INCLUDE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_VAT_INCLUDE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"USE_PRODUCT_QUANTITY" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("CP_BCS_USE_PRODUCT_QUANTITY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		'HIDE_BUY_IF_PROPS' => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("HIDE_BUY_IF_PROPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>86400),
		/*
		"CACHE_FILTER" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),*/
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BCS_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	);
	
	/*IBLOCKVOTE*/
	$arComponentParameters_vote = array(
		"IBLOCK_MAX_VOTE" => array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("IBLOCK_MAX_VOTE"),
			"TYPE" => "STRING",
			"DEFAULT" => "5",
		),
		"IBLOCK_VOTE_NAMES" => array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("IBLOCK_VOTE_NAMES"),
			"TYPE" => "STRING",
			"VALUES" => array(),
			"MULTIPLE" => "Y",
			"DEFAULT" => array("1","2","3","4","5"),
			"ADDITIONAL_VALUES" => "Y",
		),
		"DISPLAY_AS_RATING" => Array(
			"PARENT" => "IBLOCKVOTE",
			"NAME" => GetMessage("TP_CBIV_DISPLAY_AS_RATING"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"rating" => GetMessage("TP_CBIV_RATING"),
				"vote_avg" => GetMessage("TP_CBIV_AVERAGE"),
			),
			"DEFAULT" => "rating",
		),
		
	);
	
	/*OTHER*/
	$arComponentParameters_other = array(
		"META_KEYWORDS" =>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_KEYWORDS"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => array_merge(Array("-"=>" "), $arSProperty_LNS),
		),
		"META_DESCRIPTION" =>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_DESCRIPTION"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => array_merge(Array("-"=>" "), $arSProperty_LNS),
		),
		"BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BCS_BROWSER_TITLE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(Array("-"=>" ", "NAME" => GetMessage("IBLOCK_FIELD_NAME")), $arSProperty_LNS),
		),
		"ADD_SECTIONS_CHAIN" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BCS_ADD_SECTIONS_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"DISPLAY_COMPARE" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_DISPLAY_COMPARE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SET_TITLE" => Array(),
		"SET_STATUS_404" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BCS_SET_STATUS_404"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"INCLUDE_JQUERY" => Array(
			"NAME" => GetMessage("YS_BS_INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y'
		),
	);
	


$arComponentParameters["PARAMETERS"] = array_merge(
	$arComponentParameters_base,
	$arComponentParameters_stickers,
	$arComponentParameters_url,
	$arComponentParameters_view,
	$arComponentParameters_price,
	$arComponentParameters_vote,
	$arComponentParameters_other
);

if (isset($arCurrentValues['MAIN_SP_ON_AUTO_NEW']) && 'N' == $arCurrentValues['MAIN_SP_ON_AUTO_NEW']){
	$arComponentParameters['PARAMETERS']['STICKER_NEW'] = array (
		'HIDDEN' => 'Y',
	);
	$arComponentParameters['PARAMETERS']['STICKER_HIT'] = array (
		'HIDDEN' => 'Y',
	);
	$arComponentParameters['PARAMETERS']['STICKER_BESTSELLER'] = array (
		'HIDDEN' => 'Y',
	);
}

/*LIKE IN COMPONENT*/
if($is_bitronic)
CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("T_IBLOCK_DESC_PAGER_CATALOG"), true, true);
if (CModule::IncludeModule('catalog') && CModule::IncludeModule('currency'))
{
	$arComponentParameters["PARAMETERS"]['CONVERT_CURRENCY'] = array(
		'PARENT' => 'PRICES',
		'NAME' => GetMessage('CP_BCS_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arComponentParameters['PARAMETERS']['CURRENCY_ID'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('CP_BCS_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}

if(!$OFFERS_IBLOCK_ID)
{
	unset($arComponentParameters["PARAMETERS"]["OFFERS_FIELD_CODE"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_PROPERTY_CODE"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_FIELD"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_ORDER"]);
}
else
{
	unset($arComponentParameters["PARAMETERS"]["PRODUCT_PROPERTIES"]);
	$arComponentParameters["PARAMETERS"]["OFFERS_CART_PROPERTIES"] = array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("CP_BCS_OFFERS_CART_PROPERTIES"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arProperty_Offers,
	);
}

if($is_bitronic){
	$arComponentParameters["PARAMETERS"]["PAGE_ELEMENT_COUNT"] = array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("IBLOCK_PAGE_ELEMENT_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "20",
	);
	$arComponentParameters["PARAMETERS"]["LINE_ELEMENT_COUNT"] = array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("IBLOCK_LINE_ELEMENT_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "4",
	);
}

if (CModule::IncludeModule('yenisite.geoipstore')) {
		$arComponentParameters['PARAMETERS']['PRICE_CODE']['TYPE'] = 'STRING';
		$arComponentParameters['PARAMETERS']['PRICE_CODE']['DEFAULT'] = '$prices';
		$arComponentParameters['PARAMETERS']['PRICE_CODE']['NAME'] = GetMessage("PRICE_TABLE_NAME");

		unset($arComponentParameters['PARAMETERS']['PRICE_CODE']['VALUES']);
		unset($arComponentParameters['PARAMETERS']['PRICE_CODE']['MULTIPLE']);

		$arTemplateParameters['STORE_CODE'] = array(
			"PARENT" => "STORE_SETTINGS",
			"NAME" => GetMessage("STORE_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '$stores',
		);
	}
?>