<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
	return;
if(!CModule::IncludeModule('yenisite.resizer2') || !method_exists('CResizer2Set' , 'GetBySizeMode'))
{
	return;
}


// MODE:  HEIGHT, WIDTH, CROP, FIT_LARGE, CWIDTH, CHEIGHT, FILL

$postfix = '_RESIZER_SET';
$arNeedSets = array(
	'MENU_ICON' => array(
		"WIDTH" 	=> "18",
		"HEIGHT"	=> "18",
		"MODE"		=> "FIT_LARGE"
	),
	'PERSONAL_AVA' => array(
		"WIDTH" 	=> "40",
		"HEIGHT"	=> "40",
		"MODE"		=> "FILL"
	),
	'SMALL_BASKET_ICON' => array(// for small basket, compare, category list
		"WIDTH" 	=> "65",
		"HEIGHT"	=> "65",
		"MODE"		=> "FILL"
	),
	'SLIDER_TO_1200' => array(
		"WIDTH" 	=> "600",
		"HEIGHT"	=> "1000",
		"MODE"		=> "FIT_LARGE"
	),
	'SLIDER_TO_991' => array(
		"WIDTH" 	=> "600",
		"HEIGHT"	=> "1000",
		"MODE"		=> "FIT_LARGE"
	),
	'COOL_SLIDER_BIG' => array(
		"WIDTH" 	=> "350",
		"HEIGHT"	=> "300",
		"MODE"		=> "FIT_LARGE"
	),
	'COOL_SLIDER_SMALL' => array(
		"WIDTH" 	=> "100",
		"HEIGHT"	=> "80",
		"MODE"		=> "FIT_LARGE"
	),
	'ELEMENT_LIST' => array(
		"WIDTH" 	=> "230",
		"HEIGHT"	=> "230",
		"MODE"		=> "FILL"
	),
	'ELEMENT_LIST_VIP' => array(
		"WIDTH" 	=> "490",
		"HEIGHT"	=> "290",
		"MODE"		=> "FIT_LARGE"
	),
	'ELEMENT_DETAIL_ICON' => array(
		"WIDTH" 	=> "90",
		"HEIGHT"	=> "90",
		"MODE"		=> "CROP"
	),
	'BIG_BASKET_ICON' => array(// for big basket, add order, order detail
		"WIDTH" 	=> "90",
		"HEIGHT"	=> "90",
		"MODE"		=> "FILL"
	),
	'NEWS_MAIN' => array(
		"WIDTH" 	=> "100",
		"HEIGHT"	=> "100",
		"MODE"		=> "CROP"
	),
	'NEWS_LIST' => array(
		"WIDTH" 	=> "360",
		"HEIGHT"	=> "360",
		"MODE"		=> "CROP"
	),
	'SERVICE_LIST' => array(
		"WIDTH" 	=> "250",
		"HEIGHT"	=> "100",
		"MODE"		=> "FIT_LARGE"
	),
	'PARTNER_LIST' => array(
		"WIDTH" 	=> "200",
		"HEIGHT"	=> "200",
		"MODE"		=> "FILL"
	),
	'COMPANY_PAGE_LIST' => array(
		"WIDTH" 	=> "200",
		"HEIGHT"	=> "200",
		"MODE"		=> "FIT_LARGE"
	),
	'LICENSE_PAGE_BIG' => array(
		"WIDTH" 	=> "498",
		"HEIGHT"	=> "800",
		"MODE"		=> "FIT_LARGE"
	),
	'ELEMENT_DETAIL_MEDIUM' => array(
		"WIDTH" 	=> "350",
		"HEIGHT"	=> "350",
		"MODE"		=> "FIT_LARGE"
	),
	'ELEMENT_DETAIL_BIG' => array(
		"WIDTH" 	=> "1000",
		"HEIGHT"	=> "1000",
		"MODE"		=> "FIT_LARGE"
	),
	'ELEMENT_DETAIL_PROP' => array(
		"WIDTH" 	=> "100",
		"HEIGHT"	=> "20",
		"MODE"		=> "FIT_LARGE"
	),
    'BANNER_ACTION' => array(
		"WIDTH" 	=> "1150",
		"HEIGHT"	=> "250",
		"MODE"		=> "FIT_LARGE"
	),
    'SECTION_LARGE' => array(
        "WIDTH" 	=> "110",
        "HEIGHT"	=> "110",
        "MODE"		=> "FIT_LARGE"
    ),
    'SECTION_BIG' => array(
        "WIDTH" 	=> "750",
        "HEIGHT"	=> "550",
        "MODE"		=> "FIT_LARGE"
    ),
    'IMG_STORE' => array(
        "WIDTH" 	=> "400",
        "HEIGHT"	=> "300",
        "MODE"		=> "FIT_LARGE"
    ),
    'PAYMENT' => array(
        "WIDTH" 	=> "95",
        "HEIGHT"	=> "45",
        "MODE"		=> "FIT_LARGE"
    ),
    'REVIEWS_IMG' => array(
        "WIDTH" 	=> "170",
        "HEIGHT"	=> "170",
        "MODE"		=> "FIT_LARGE"
    ),
    'RESIZER_COMPLECTS' => array(
        "WIDTH" 	=> "130",
        "HEIGHT"	=> "100",
        "MODE"		=> "FILL"
    ),
    'RESIZER_FOR_INSTAGRAM_IMG' => array(
        "WIDTH" 	=> "191",
        "HEIGHT"	=> "191",
        "MODE"		=> "FILL"
    ),
    'RESIZER_SECTIONS_LVL_FIRST' => array(
        "WIDTH" 	=> "102",
        "HEIGHT"	=> "120",
        "MODE"		=> "FILL"
    ),
    'RESIZER_DETAIL_SKU_TABLE' => array(
        "WIDTH" 	=> "80",
        "HEIGHT"	=> "60",
        "MODE"		=> "FILL"
    ),
    'RESIZER_REVIEW_IMG' => array(
        "WIDTH" 	=> "340",
        "HEIGHT"	=> "270",
        "MODE"		=> "FILL"
    ),
    'RESIZER_FOR_VIEWD_ITEMS' => array(
        "WIDTH" 	=> "35",
        "HEIGHT"	=> "60",
        "MODE"		=> "FILL"
    ),
);

$arMacros = array();
foreach ($arNeedSets as $set => $arSet) {
	$set_id = CResizer2Set::GetBySizeMode($arSet["WIDTH"],$arSet["HEIGHT"], $arSet["MODE"]);
	if ($set_id === false) {
		$set_id = CResizer2Set::Add(GetMessage($set), $arSet["WIDTH"], $arSet["HEIGHT"], 100, 'N', $arSet["MODE"]);
	}
	$arMacros[$set . $postfix] = $set_id;
}
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", $arMacros);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/news/index.php",    $arMacros);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/reviews/index.php", $arMacros);
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/brands/",        $arMacros);
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/company/",       $arMacros);
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/include_areas/", $arMacros);
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/personal/",      $arMacros);
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."actions/",      $arMacros);
?>