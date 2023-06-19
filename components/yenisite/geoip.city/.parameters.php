<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("statistic"))
{
	return;
}
if (!CModule::IncludeModule("sale"))
{
	return;
}

$db_person = CSalePersonType::GetList();
$arPersons = array();
while ($arPerson = $db_person->Fetch()) {
	$arPersons[$arPerson['ID']] = $arPerson;
}

$db_props = CSaleOrderProps::GetList(array("SORT" => "ASC"));
while ($props = $db_props->Fetch())
{
	$arPerson = array_key_exists($props['PERSON_TYPE_ID'], $arPersons) ? $arPersons[$props['PERSON_TYPE_ID']] : false;
	$arOrderProps[$props["ID"]] = "[".$props["ID"]."] "
	                            . (is_array($arPerson)
	                              ? '('.$arPerson['LID'].' - '.$arPerson['NAME'].') '
	                              : '')
	                            . $props["NAME"];
}
$arOrderProps[0] = GetMessage("YS_LOCATOR_NOT_FILL");

//Build list of all cities
$arCityProps = array();
$dbCities = CSaleLocation::GetList(array(),
	array('COUNTRY_LID' => LANGUAGE_ID, 'CITY_LID' => LANGUAGE_ID),
	false, false, array('ID', 'CITY_NAME', 'REGION_NAME', 'COUNTRY_NAME'));
while ($arLoc = $dbCities->Fetch()) {
	$arCityProps[$arLoc['ID']] = $arLoc['CITY_NAME']
	                           . (empty($arLoc['REGION_NAME'])  ? '' : ', ' . $arLoc['REGION_NAME'])
	                           . (empty($arLoc['COUNTRY_NAME']) ? '' : ', ' . $arLoc['COUNTRY_NAME']);
}
asort($arCityProps);
$arCityProps = array('0' => ' ') + $arCityProps;

//Find default cities
$sCityMess = 'YS_LOCATOR_CITY_';
$obCity = new CCity();
$arCityInfo = $obCity->GetFullInfo();

if ($arCityInfo['COUNTRY_CODE']['VALUE'] == 'UA') {
	$sCityMess .= 'UA_';
}
for ($i = 1; $i < 10; $i++) {
	$arLoc = CSaleLocation::GetList(array(),
		array('CITY_NAME' => GetMessage($sCityMess.$i.'_DEFAULT')),
		false, false, array('ID'))->Fetch();
	$cityID[$i] = $arLoc['ID'];
}


$arComponentParameters = Array(
	"GROUPS" => Array(
		"YS_LOCATOR" => array(
			"NAME" => GetMessage("YS_LOCATOR_GROUP_NAME"),
			"SORT" => 2000
		),
		"YS_LOCATOR_ORDER" => array(
			"NAME" => GetMessage("YS_LOCATOR_ORDER_GROUP_NAME"),
			"SORT" => 3000
		),
		"YS_LOCATOR_CITIES" => array(
			"NAME" => GetMessage("YS_LOCATOR_CITIES_GROUP_NAME"),
			"SORT" => 4000
		)
	),
		
	"PARAMETERS" => Array(
		"CACHE_TIME"  =>  Array("DEFAULT" => 3600),
		"COLOR_SCHEME" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("YS_LOCATOR_COLOR"),
			"TYPE" => "LIST",
			"VALUES" => Array(
				"red" => GetMessage("YS_LOCATOR_COLOR_RED"),
				"green" => GetMessage("YS_LOCATOR_COLOR_GREEN"),
				"ice" => GetMessage("YS_LOCATOR_COLOR_BLUE"),
				"metal" => GetMessage("YS_LOCATOR_COLOR_METAL"),
				"pink" => GetMessage("YS_LOCATOR_COLOR_PINK"),
				"yellow" => GetMessage("YS_LOCATOR_COLOR_YELLOW")
			),
			"ADDITIONAL_VALUES" => "Y",
		),
		"INCLUDE_JQUERY" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N'
		),
		"NEW_FONTS" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("NEW_FONTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y'
		),
		"P1_LOCATION_ID" => Array(
			"PARENT" => "YS_LOCATOR_ORDER",
			"NAME" => GetMessage("YS_LOCATOR_P1_LOCATION_ID"),
			"TYPE" => "LIST",
			"DEFAULT" => "5",
			"VALUES" => $arOrderProps
		),
		"P1_CITY_ID" => Array(
			"PARENT" => "YS_LOCATOR_ORDER",
			"NAME" => GetMessage("YS_LOCATOR_P1_CITY_ID"),
			"TYPE" => "LIST",
			"DEFAULT" => "6",
			"VALUES" => $arOrderProps
		),
		"P2_LOCATION_ID" => Array(
			"PARENT" => "YS_LOCATOR_ORDER",
			"NAME" => GetMessage("YS_LOCATOR_P2_LOCATION_ID"),
			"TYPE" => "LIST",
			"DEFAULT" => "18",
			"VALUES" => $arOrderProps
		),
		"P2_CITY_ID" => Array(
			"PARENT" => "YS_LOCATOR_ORDER",
			"NAME" => GetMessage("YS_LOCATOR_P2_CITY_ID"),
			"TYPE" => "LIST",
			"DEFAULT" => "17",
			"VALUES" => $arOrderProps
		),
		"AUTOCONFIRM" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("YS_LOCATOR_AUTOCONFIRM"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
		"DISABLE_CONFIRM_POPUP" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("YS_LOCATOR_DISABLE_CONFIRM_POPUP"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
		"RELOAD_PAGE" => Array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("YS_LOCATOR_RELOAD_PAGE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		),
	),
);

if (CModule::IncludeModule('yenisite.geoipstore')){
	$arComponentParameters["PARAMETERS"]["UNITE_WITH_GEOIPSTORE"] = array(
			"PARENT" => "YS_LOCATOR",
			"NAME" => GetMessage("UNITE_WITH_GEOIPSTORE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		);
}

for ($i = 1; $i < 10; $i++) {
	$arComponentParameters["PARAMETERS"]["CITY_$i"] = array(
		"PARENT" => "YS_LOCATOR_CITIES",
		"NAME" => GetMessage("YS_LOCATOR_CITY_$i"),
		"TYPE" => "LIST",
		"DEFAULT" => $cityID[$i],
		"VALUES" => $arCityProps
	);
}
?>
