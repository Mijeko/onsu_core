<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// die('stoop');

CModule::IncludeModule('yenisite.core');

$wizard = \Yenisite\Core\Wizard\Main::getCurrentWizard();
$install_type = $wizard->GetVar("install_type");

if($install_type == 'update')
{
	$arServices = Array(
		"main" => Array(
			"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
			"STAGES" => Array(
				"files.php", // Copy bitrix files  		<---!!change
				"handlers.php", // Register module handlers
				"template.php", // Install template		<---!!change
				"resizer.php", // Install resizer sets
				"menu.php", // Install menu
				"banners.php", // Install banners		<---!!change
				"vote.php",
			),
		),
		"iblock" => Array(
			"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
			"STAGES" => Array(
				"types.php", //IBlock types
				"reviews.php",
				"feedback.php",
				"services.php",
                "actions.php",
				"content.php",
				"references.php",//reference of brands
				"references2.php",
				"properties.php",
			),
		),
	);
    if (!CModule::IncludeModule('yenisite.bitronic2lite')){
        $arServices += array(
            "sale" => Array(
                "NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
                "STAGES" => Array(
                   "step3.php"
                ),
            ),
        );
    }
}
else
{
	$arServices = Array(
		"main" => Array(
			"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
			"STAGES" => Array(
				"files.php", // Copy bitrix files  		<---!!change
				"handlers.php", // Register module handlers
				// "search.php", // Indexing files
				"template.php", // Install template		<---!!change
				"resizer.php", // Install resizer sets	<---!!change
				"theme.php", // Install theme			<---!!change
				"menu.php", // Install menu
				"settings.php",
				"banners.php", // Install banners		<---!!change
				"vote.php",
			),
		),
		"iblock" => Array(
			"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
			"STAGES" => Array(
				"types.php", //IBlock types
				"news.php",
				"reviews.php",
				"feedback.php",
				"banners.php",
				"services.php",
                "actions.php",
				"content.php",
				"references.php",//reference of colors
				"references2.php",
				"catalog.php",//catalog iblock import
				"catalog2.php",//offers iblock import
				"catalog3.php",
				"properties.php",
			),
		),
		"forum" => Array(
			"NAME" => GetMessage("SERVICE_FORUM"),
			"STAGES" => Array(
				"index.php",
			),
		),
		"subscribe" => array(
			"NAME" => GetMessage("SERVICE_SUBSCRIBE"),
			"STAGES" => array(
				"rubrics.php"
			)
		)
	);
	
	if (CModule::IncludeModule('yenisite.bitronic2lite'))
	{
		$arServices += array(
			"yenisite.market" => Array(
				"NAME" => GetMessage("SERVICE_MARKET_SETTINGS"),
				"STAGES" => Array(
					"index.php"
				)
			)
		);
		$arServices['iblock']['STAGES'][] = 'comments.php';
	}
	else
	{
		$arServices += array(
			"sale" => Array(
				"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
				"STAGES" => Array(
					"locations.php", "step1.php", "step2.php", "step3.php"
				),
			),
			"catalog" => Array(
				"NAME" => GetMessage("SERVICE_CATALOG_SETTINGS"),
				"STAGES" => Array(
					"index.php",
				),
			)
		);
	}
}
?>