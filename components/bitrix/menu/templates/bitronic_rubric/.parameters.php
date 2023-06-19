<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_BS_FILTER"]= array(
	"NAME" => GetMessage("YS_BS_GROUP_NAME"),
	"SORT" => 2000,
);
$arTemplateParameters = array(
	"THEME" => Array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("THEME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"red" => GetMessage("THEME_RED"),
			"ice" => GetMessage("THEME_ICE"),
			"green" => GetMessage("THEME_GREEN"),
			"yellow" => GetMessage("THEME_YELLOW"),
			"pink" => GetMessage("THEME_PINK"),
			"metal" => GetMessage("THEME_METAL"),
		),
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => $ys_options["color_scheme"],
	),
);

if(CModule::IncludeModule('yenisite.resizer2')) {
	$dbSets = CResizer2Set::GetList();
	while($arSet = $dbSets->Fetch()) {
		$arSets[$arSet["id"]] = "[{$arSet["id"]}] {$arSet["NAME"]} ({$arSet['w']}x{$arSet['h']})";
		if($arSet['h'] == 170 && $arSet['w'] == 170) {
			$defualtSetId = $arSet['id'];
			//break;
		}
	}
	
	if (empty($defualtSetId)) {
		$defualtSetId = "4";
		// CResizer2Set::Add('RUBRIC', 170, 170, 100, 'N', 'CROP');
		// CResizer2Set::Add('RUBRIC', 170, 170, 100, 'N', 'FIT');
		
		// $dbSets = CResizer2Set::GetList();
		// while($arSet = $dbSets->Fetch()) {
		// 	$arSets[$arSet["id"]] = "[{$arSet["id"]}] {$arSet["NAME"]} ({$arSet['w']}x{$arSet['h']})";
						
		// 	if($arSet['h'] == 170 && $arSet['w'] == 170) {
		// 		$defualtSetId = $arSet['id'];
		// 		break;
		// 	}
		// }
	}
	
	$arTemplateParameters['RESIZER2_SET'] = Array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("YS_BS_RESIZER2_SETS"),
		"TYPE" => "LIST",	
		"VALUES" => $arSets,
		"DEFAULT" => $defualtSetId
	);
}

?>
