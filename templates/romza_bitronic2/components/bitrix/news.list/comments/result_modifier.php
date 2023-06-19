<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

CModule::IncludeModule('yenisite.resizer2');

$authorIdList = array();
foreach($arResult["ITEMS"] as $arItem) {
	if (intval($arItem['FIELDS']['CREATED_BY']) <= 0) continue;
	$authorIdList[] = $arItem['FIELDS']['CREATED_BY'];
}
array_unique($authorIdList);

$rsUsers = CUser::GetList(
	$by = 'id',
	$order = 'asc',
	$arFilter = array("@ID" => $authorIdList),
	$arSelect = array("ID", "PERSONAL_PHOTO")
);

$arResult['AUTHORS'] = array();
//$arResult['AUTHORS']['GUEST']['AVATAR'] = CResizer2Resize::ResizeGD2(false, $arParams["RESIZER_COMMENT_AVATAR"]);
while ($arUser = $rsUsers->Fetch()) {
	if(intval($arUser["PERSONAL_PHOTO"]) > 0) {
		$path = $arUser["PERSONAL_PHOTO"];
		$arUser['AVATAR'] = CResizer2Resize::ResizeGD2(CFile::GetPath($path), $arParams["RESIZER_COMMENT_AVATAR"]);
	} else {
		$arUser['AVATAR'] = false;
	}

	$arResult['AUTHORS'][$arUser['ID']] = $arUser;
}

foreach($arResult["ITEMS"] as &$arItem) {
	if (array_key_exists($arItem['FIELDS']['CREATED_BY'], $arResult['AUTHORS'])) {
		$arItem['AVATAR'] = $arResult['AUTHORS'][$arItem['FIELDS']['CREATED_BY']]['AVATAR'];
	} else {
		$arItem['AVATAR'] = false;//$arResult['AUTHORS']['GUEST']['AVATAR'];
	}
}
unset($arItem);
?>