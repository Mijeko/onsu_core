<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!CModule::IncludeModule("advertising"))
	return;

WizardServices::IncludeServiceLang("banners.php", $languageID);

$wizard =& $this->GetWizard();

//  ###	BANNER TYPES ### //
$arBannerTypes = array(
	'brands', 'catalog_element_single', 'catalog_section_single', 'catalog_filter', 'index_single', 'index_bot_left',
	'news',   'catalog_element_double', 'catalog_section_double', 'catalog_bottom', 'index_double', 'index_bot_right',
	'404',    'catalog_element_triple', 'catalog_section_in_goods'
);

$typePrefix = 'b2_';	// CHANGE

foreach ($arBannerTypes as $type) {
	$arFields = array(
		"ACTIVE" => 'Y',
		"NAME"   => GetMessage($type),
		"SORT"   => 100,
		"SID"    => $type = $typePrefix.$type,
	);
	if (CAdvType::GetByID($type)->SelectedRowsCount() > 0) {
		$SID = CAdvType::Set($arFields, $type);
	} else {
		$SID = CAdvType::Set($arFields, '');
	}
}

//  ###	BANNER CONTRACTS ### //
$showHours = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');

$arFilter = Array(
	"LAMP" => 'green',
	"SITE" => array(WIZARD_SITE_ID),
	"NAME" => 'for bitronic2'			// CHANGE
);
$res = CAdvContract::GetList($by = "s_id", $order = "desc", $arFilter, $is_filtered);

if (!$ar = $res->Fetch()) {
	$arFields = array(
		"ACTIVE"     => "Y",
		"arrSITE"    => array(WIZARD_SITE_ID),
		"arrTYPE"    => array("ALL"),
		"arrWEEKDAY" => array(
			"SUNDAY"    => $showHours,
			"MONDAY"    => $showHours,
			"TUESDAY"   => $showHours,
			"WEDNESDAY" => $showHours,
			"THURSDAY"  => $showHours,
			"FRIDAY"    => $showHours,
			"SATURDAY"  => $showHours,
		),
		"DEFAULT_STATUS_SID" => 'PUBLISHED',
		"NAME" => "for bitronic2"				// CHANGE
	);
	$contractId = CAdvContract::Set($arFields, 0, 'N');
} else {
	$contractId = $ar['ID'];
}

//  ###	BANNERS ### //
$arImages = array(
	'promo1'  => array('NAME' => 'promo1.jpg', 'ALT' => GetMessage('banner_alt')),
	'promo2'  => array('NAME' => 'promo2.jpg', 'ALT' => GetMessage('banner_alt')),
	'banner1' => array('NAME' => 'banner-placeholder-1.png',  'ALT' => GetMessage('banner_alt-1')),
	'banner2' => array('NAME' => 'banner-placeholder-2.png',  'ALT' => GetMessage('banner_alt-2')),
	'banner3' => array('NAME' => 'banner-placeholder-3.png',  'ALT' => GetMessage('banner_alt-3')),
	'banner4' => array('NAME' => 'banner-placeholder-4.png',  'ALT' => GetMessage('banner_alt-4')),
	'banner5' => array('NAME' => 'banner-placeholder-5.png',  'ALT' => GetMessage('banner_alt-5')),
	'banner6' => array('NAME' => 'banner-placeholder-6.png',  'ALT' => GetMessage('banner_alt-6')),
	'banner7' => array('NAME' => 'banner-placeholder-7.png',  'ALT' => GetMessage('banner_alt-7')),
	'catalog-banner' => array('NAME' => 'catalog-banner.jpg', 'ALT' => GetMessage('banner3_ALT')),
	'catalog-banner-in-items' => array('NAME' => 'banner-placeholder-8.png', 'ALT' => GetMessage('banner_alt-8')),
);

$arBanners = array(
	array('TYPE' => 'index_bot_left',         'IMAGES' => array($arImages['promo1'])),
	array('TYPE' => 'index_bot_right',        'IMAGES' => array($arImages['promo2'])),
	array('TYPE' => 'index_single',           'IMAGES' => array($arImages['banner1'])),
	array('TYPE' => 'catalog_section_single', 'IMAGES' => array($arImages['banner1'])),
	array('TYPE' => 'catalog_element_single', 'IMAGES' => array($arImages['banner1'])),
	array('TYPE' => 'index_double',           'IMAGES' => array($arImages['banner2'], $arImages['banner3'])),
	array('TYPE' => 'catalog_section_double', 'IMAGES' => array($arImages['banner2'], $arImages['banner3'])),
	array('TYPE' => 'catalog_element_double', 'IMAGES' => array($arImages['banner2'], $arImages['banner3'])),
	array('TYPE' => 'catalog_element_triple', 'IMAGES' => array($arImages['banner4'], $arImages['banner5'], $arImages['banner6'])),
	array('TYPE' => 'catalog_filter',         'IMAGES' => array($arImages['banner7'])),
	array('TYPE' => 'catalog_bottom',         'IMAGES' => array($arImages['catalog-banner'])),
	array('TYPE' => 'catalog_section_in_goods',       'IMAGES' => array($arImages['catalog-banner-in-items'])),

	array('TYPE' => 'brands', 'IMAGES' => array($arImages['banner1'])),
	array('TYPE' => 'news',   'IMAGES' => array($arImages['banner1'])),
	array('TYPE' => '404',    'IMAGES' => array($arImages['banner1'])),
);

foreach ($arBanners as $arBanner) {
	$arBanner['TYPE'] = $typePrefix . $arBanner['TYPE'];

	$arFilter = Array(
		//"STATUS_SID"  => 'PUBLISHED',
		"CONTRACT_ID" => $contractId,
		"TYPE_SID"    => $arBanner['TYPE'],
		"SITE"        => array(WIZARD_SITE_ID)
	);

	$res = CAdvBanner::GetList($by = "s_id", $order = "desc", $arFilter, $is_filtered);
	if ($res->Fetch()) continue;

	$arFields = array(
		"CONTRACT_ID" => $contractId,
		"TYPE_SID"    => $arBanner['TYPE'],
		"STATUS_SID"  => ($wizard->GetVar("install_type") != 'update') ? 'PUBLISHED' : 'READY',
		"ACTIVE"      => 'Y',
		"arrSITE"     => array(WIZARD_SITE_ID),
		"URL"         => "http://romza.ru",
		"URL_TARGET"  => '_blank',
		"arrWEEKDAY"  => array(
			"SUNDAY"    => $showHours,
			"MONDAY"    => $showHours,
			"TUESDAY"   => $showHours,
			"WEDNESDAY" => $showHours,
			"THURSDAY"  => $showHours,
			"FRIDAY"    => $showHours,
			"SATURDAY"  => $showHours
		)
	);

	foreach ($arBanner['IMAGES'] as $arImage) {
		$arFields['arrIMAGE_ID'] = CFile::MakeFileArray(
			str_replace("//", "/", WIZARD_ABSOLUTE_PATH . '/site/services/main/img/' . $arImage['NAME'])
		);
		$arFields['IMAGE_ALT'] = (isset($arImage['ALT']) && strlen($arImage['ALT']) > 0) ? $arImage['ALT'] : '';

		$ID = CAdvBanner::Set($arFields, 0);
	}

	unset($arrIMAGE_ID);
}
