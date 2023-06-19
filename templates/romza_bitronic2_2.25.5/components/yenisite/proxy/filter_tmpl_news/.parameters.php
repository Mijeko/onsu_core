<?
if (!Bitrix\Main\Loader::includeModule('iblock')) return;
$arProperties_ALL = array();
$needCMP = 'BITRIX_NEWS_LIST-';
if ($curIblockID = (int)$arCurrentValues[$needCMP."IBLOCK_ID"]) {
    $dbProperties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array('ACTIVE' => 'Y', 'IBLOCK_ID' => $curIblockID));
    while ($arProp = $dbProperties->GetNext()) {
        $strPropName = '[' . $arProp['ID'] . ']' . ('' != $arProp['CODE'] ? '[' . $arProp['CODE'] . ']' : '') . ' ' . $arProp['NAME'];
        $arProperties_ALL[$arProp["CODE"]] = "[{$arProp['CODE']}] {$arProp['NAME']}";
    }
}

// SET META && TITLE
$arTemplateParameters[$needCMP.'SET_BROWSER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'SET_META_KEYWORDS']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'SET_META_DESCRIPTION']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'SET_LAST_MODIFIED']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'INCLUDE_IBLOCK_INTO_CHAIN']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'ADD_SECTIONS_CHAIN']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'SET_BROWSER_TITLE']['DEFAULT'] = 'N';
$arTemplateParameters[$needCMP.'SET_META_KEYWORDS']['DEFAULT'] = 'N';
$arTemplateParameters[$needCMP.'SET_META_DESCRIPTION']['DEFAULT'] = 'N';
$arTemplateParameters[$needCMP.'SET_LAST_MODIFIED']['DEFAULT'] = 'N';
$arTemplateParameters[$needCMP.'INCLUDE_IBLOCK_INTO_CHAIN']['DEFAULT'] = 'N';
$arTemplateParameters[$needCMP.'ADD_SECTIONS_CHAIN']['DEFAULT'] = 'N';

//SECTIONS
$arTemplateParameters[$needCMP.'PARENT_SECTION']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PARENT_SECTION_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'INCLUDE_SUBSECTIONS']['HIDDEN'] = 'Y';

//PAGER
$arTemplateParameters[$needCMP.'PAGER_TEMPLATE']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'DISPLAY_TOP_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'DISPLAY_BOTTOM_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_SHOW_ALWAYS']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_DESC_NUMBERING']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_DESC_NUMBERING_CACHE_TIME']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_SHOW_ALL']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PAGER_BASE_LINK_ENABLE']['HIDDEN'] = 'Y';

//404
$arTemplateParameters[$needCMP.'SET_STATUS_404']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'SHOW_404']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'MESSAGE_404']['HIDDEN'] = 'Y';

//SPECIFIC
$arTemplateParameters[$needCMP.'HIDE_LINK_WHEN_NO_DETAIL']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'DETAIL_URL']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'FILTER_NAME']['HIDDEN'] = 'Y';
$arTemplateParameters[$needCMP.'PREVIEW_TRUNCATE_LEN']['HIDDEN'] = 'Y';

$arTemplateParameters['REMOVE_POSTFIX_IN_NAMES']['HIDDEN'] = 'Y';


$arTemplateParameters[$needCMP.'PROP_FOR_SHOW_ON_MAIN'] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("PROP_FOR_SHOW_ON_MAIN"),
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "VALUES" => $arProperties_ALL,
    "SIZE" => "8",
    "DEFAULT" => array('RZ_SHOW_ON_MAIN'),
);

$arTemplateParameters[$needCMP.'PROP_FOR_RELATED_ITEMS'] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("PROP_FOR_RELATED_ITEMS"),
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "VALUES" => $arProperties_ALL,
    "SIZE" => "8",
    "DEFAULT" => array('RELATED_ITEMS'),
);

if (\Bitrix\Main\Loader::includeModule('yenisite.core')) {
    \Yenisite\Core\Resize::AddResizerParams(array('ITEM'), $arTemplateParameters);
}