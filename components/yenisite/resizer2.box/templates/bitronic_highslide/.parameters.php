<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule("yenisite.resizer2");
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
}


$arOpacity = array("0" => "0", "0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => "0.5", "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1" => "1");
$arOverlay = array("true" => "Y", "false" => "N");

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_RESIZER2_PARAMETERS"]= array(
	"NAME" => GetMessage("YENISITE_RESIZER2_PARAMETERS"),
	"SORT" => 2000,
);

$arTemplateParameters = array(
    "SET_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,    
	"DEFAULT" => "2"     
    ),
    
    "SET_BIG_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_BIG_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,        
	"DEFAULT" => "1"  
    ),
    
    "SET_SMALL_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_SMALL_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet, 
	"DEFAULT" => "6"       
    ),
		
    		
    "SHOW_DELAY_DETAIL" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_DELAY_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "300"			
		),
		
    "HIDE_DELAY_DETAIL" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HIDE_DELAY_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "600"			
		),
	"OUTLINE_TYPE" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OUTLINE_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => array(	'beveled'=>"beveled",
								'drop-shadow'=>"drop-shadow",
								'glossy-dark'=>"glossy-dark",
								'outer-glow'=>"outer-glow",
								'rounded-black'=>"rounded-black",
								'rounded-white'=>"rounded-white",
								''=>"default"),	
			"DEFAULT" => '',
		),
	"DIMMING_OPACITY" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("DIMMING_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),
			"DEFAULT" => '0.5',		
		),	
	"TRANSITIONS_APPEARANCE" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSITIONS_APPEARANCE"),
			"TYPE" => "LIST",
			"VALUES" => array(	'expand'=>"expand",
								'fade'=>"fade"),	
			"DEFAULT" => 'expand',
		),
	"TRANSITIONS_CHANGE" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSITIONS_CHANGE"),
			"TYPE" => "LIST",
			"VALUES" => array(	'expand'=>"expand",
								'fade'=>"fade",
								'crossfade'=>"crossfade"),	
			"DEFAULT" => 'expand',
		),
	"NUMBER_POSITION" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("NUMBER_POSITION"),
			"TYPE" => "LIST",
			"VALUES" => array(	'heading'=>GetMessage("TOP"),
								'caption'=>GetMessage("BOTTOM"),),	
			"DEFAULT" => 'caption',
		),
	"WRAPPER_CLASS_NAME" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("WRAPPER_CLASS_NAME"),
			"TYPE" => "LIST",
			"VALUES" => array(	'dark'=>"dark",
								'floating-caption'=>"floating-caption",
								'controls-in-heading'=>"controls-in-heading",
								),	
			"DEFAULT" => 'dark',
		),
	
);
?>
