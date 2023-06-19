<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::includeModule('highloadblock');


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;
$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
if($arParams["SECTION_ID"])
	$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["GENERATION"] = $arParams["GENERATION"]=="Y";
$arParams["SHOW_ENG"] = $arParams["SHOW_ENG"]=="Y";
$arParams["GROUP_ENG"] = $arParams["GROUP_ENG"]=="Y";
$arParams["SHOW_RUS"] = $arParams["SHOW_RUS"]=="Y";
$arParams["GROUP_RUS"] = $arParams["GROUP_RUS"]=="Y";
$arParams["SHOW_NUMBER"] = $arParams["SHOW_NUMBER"]=="Y";
$arParams["GROUP_NUMBER"] = $arParams["GROUP_NUMBER"]=="Y";
$arParams["SHOW_ALL"] = $arParams["SHOW_ALL"]=="Y";
$arParams["LIST_ENABLE"] = $arParams["LIST_ENABLE"]=="Y";
if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
if($arParams["GENERATION"])
	{
	$arParams["GROUP_NUMBER_FLAG"]=0;
	$arParams["GROUP_RUS_FLAG"]=0;
	$arParams["GROUP_ENG_FLAG"]=0;
	}
else
	{
	$arParams["GROUP_NUMBER_FLAG"]=1;
	$arParams["GROUP_RUS_FLAG"]=1;
	$arParams["GROUP_ENG_FLAG"]=1;
	}

$arParams["HL_IBLOCK"] = (isset($arParams["HL_IBLOCK"]) && $arParams["HL_IBLOCK"] == 'Y') ? true : false;

if(strlen($arParams["FILTER_NAME"])<=0|| !preg_match("#^[A-Za-z_][A-Za-z01-9_]*$#", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = $FILTER_NAME = "arrFilter";
else
	$arResult["FILTER_NAME"] = $FILTER_NAME = $arParams["FILTER_NAME"];
global $$FILTER_NAME;
if(!is_array($$FILTER_NAME))
	$$FILTER_NAME = array();
$arrFilter_not_abcd=array();
foreach($$FILTER_NAME as $key => $value)
	{
		if($key!=">NAME" && $key!="<NAME" && $key!="=<NAME")
			$arrFilter_not_abcd[$key]=$value;
	}
$letter=htmlspecialcharsEx($_REQUEST["letter"]);
$arServiceLetter = array("number", "all", "rus", "eng"); 
if(!in_array($letter,$arServiceLetter))
	$letter_id=ord(htmlspecialcharsEx($_REQUEST["letter"]));
/*************************************************************************
				Подключаем кэш
*************************************************************************/
if($this->StartResultCache(false, array($arrFilter_not_abcd, $letter_id, $USER->GetGroups())))
{
	$arResult=Array();
	$arResult['letters'] = Array();
				
    if($arParams['LIST_ENABLE'])
        $arResult['list'] = Array();

	if($arParams["GENERATION"])
	{
		if(CModule::IncludeModule("iblock"))
		{
			$arSelect = array_merge($arParams["FIELD_CODE"], array(
				"ID",
				"NAME",
				"CODE",
				"IBLOCK_ID",
				"IBLOCK_SECTION_ID",
				"DETAIL_PAGE_URL",
			));
			if($arParams["HL_IBLOCK"])
			{
				$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($arParams["IBLOCK_ID"])->fetch();
				$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
				$entityDataClass = $entity->getDataClass();
				$arFilter = array(
					'order' => array($arParams['HL_NAME_FIELD'] => 'ASC')
				);
				$res = $entityDataClass::getList($arFilter);
			}
			else
			{
				$arFilter = array(
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"IBLOCK_LID" => SITE_ID,
					"IBLOCK_ACTIVE" => "Y",
					"ACTIVE_DATE" => "Y",
					"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				);
				if(isset($arParams["SECTION_ID"]))
				{
					$arFilter["SECTION_ID"] = $arParams["SECTION_ID"];
					$arFilter["INCLUDE_SUBSECTIONS"] = $arParams['INCLUDE_SUBSECTIONS'] == 'Y' ? 'Y' : 'N';
				}          
				
				$res = CIBlockElement::GetList(Array("NAME"=>"asc"), array_merge($arrFilter_not_abcd,$arFilter), false, false, $arSelect);
			}
			$temp="";
			$i=0;
			$list = array () ;
			while($fields = $res->Fetch())
			{
				if($arParams["HL_IBLOCK"])
					$name = $fields[$arParams['HL_NAME_FIELD']];
				else	
					$name = $fields['NAME'];
        if(LANG_CHARSET != 'windows-1251')
					$name = iconv(LANG_CHARSET, 'windows-1251', $name);
				$temp = $name{0};
				$temp_ord=ord($temp);
				if($arParams['LIST_ENABLE'] == 'Y')
				{
					$list[$temp][$i]['NAME'] = $fields['NAME'];
					$list[$temp][$i]['DETAIL_PAGE_URL'] = $fields['DETAIL_PAGE_URL'];
				}
				if($i==0 || !array_search($temp, $arResult['letters']))
				{
					if( ($temp_ord>=65 && $temp_ord<=90) || ($temp_ord>=97 && $temp_ord<=122)) 
					{
						if($arParams["SHOW_ENG"] && !$arParams["GROUP_ENG"])
						{
							if($temp_ord>=65 && $temp_ord<=90)
								$arResult['letters'][$i]=$temp;
							else
							{
								$arResult['letters'][$i]=chr(ord($temp)-32);
							}
							$i++;
						}
                        $arParams["GROUP_ENG_FLAG"]=1;
					}
					elseif( ($temp_ord>=192 && $temp_ord<=223) || ($temp_ord>=224 && $temp_ord<=255)) 
					{
						if($arParams["SHOW_RUS"] && !$arParams["GROUP_RUS"])
						{
							if($temp_ord>=192 && $temp_ord<=223)
								$arResult['letters'][$i]=$temp;
							else
							{
								$arResult['letters'][$i]=chr(ord($temp)-32);
							}
							$i++;
						}
					$arParams["GROUP_RUS_FLAG"]=1;
					}

					elseif($temp_ord>=48 && $temp_ord<=57) 
					{
						if($arParams["SHOW_NUMBER"] && !$arParams["GROUP_NUMBER"])
						{
							$arResult['letters'][$i]=$temp;
							$i++;
						}
					$arParams["GROUP_NUMBER_FLAG"]=1;
					}
                    
				}
			}
			if(!in_array($letter,$arServiceLetter) && strlen($letter) > 0 && !array_search(strtoupper($letter), $arResult['letters']))
				$arResult['letters'][] = (LANG_CHARSET != 'windows-1251') ? iconv(LANG_CHARSET, 'windows-1251', $letter) : $letter;
			$arResult['letters'] = array_unique($arResult['letters']);
			if(LANG_CHARSET != 'windows-1251')
			{
				foreach($arResult['letters'] as $n=>$char)
				{
					$arResult['letters'][$n] = iconv('windows-1251', LANG_CHARSET, $char);
				}
			}
			foreach($arResult['letters'] as $n=>$char)
			{
				$arResult['list'][$arResult['letters'][$n]] = $list[$char];
			}
		}
	} 
	else
	{
		if($arParams["SHOW_ENG"] && !$arParams["GROUP_ENG"])
			$arResult['letters']=array_merge(Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"),$arResult['letters']);
		if($arParams["SHOW_RUS"] && !$arParams["GROUP_RUS"])
			$arResult['letters']=array_merge(Array(GetMessage("YENISITE_CATALOGABCD_A"),GetMessage("YENISITE_CATALOGABCD_B"),GetMessage("YENISITE_CATALOGABCD_V"),GetMessage("YENISITE_CATALOGABCD_G"),GetMessage("YENISITE_CATALOGABCD_D"),GetMessage("YENISITE_CATALOGABCD_E"),GetMessage("YENISITE_CATALOGABCD_J"),GetMessage("YENISITE_CATALOGABCD_Z"),GetMessage("YENISITE_CATALOGABCD_I"),GetMessage("YENISITE_CATALOGABCD_K"),GetMessage("YENISITE_CATALOGABCD_L"),GetMessage("YENISITE_CATALOGABCD_M"),GetMessage("YENISITE_CATALOGABCD_N"),GetMessage("YENISITE_CATALOGABCD_O"),GetMessage("YENISITE_CATALOGABCD_P"),GetMessage("YENISITE_CATALOGABCD_R"),GetMessage("YENISITE_CATALOGABCD_S"),GetMessage("YENISITE_CATALOGABCD_T"),GetMessage("YENISITE_CATALOGABCD_U"),GetMessage("YENISITE_CATALOGABCD_F"),GetMessage("YENISITE_CATALOGABCD_H"),GetMessage("YENISITE_CATALOGABCD_C"),GetMessage("YENISITE_CATALOGABCD_C1"),GetMessage("YENISITE_CATALOGABCD_S1"),GetMessage("YENISITE_CATALOGABCD_S2"),GetMessage("YENISITE_CATALOGABCD_Y"),GetMessage("YENISITE_CATALOGABCD_E1"),GetMessage("YENISITE_CATALOGABCD_U1"),GetMessage("YENISITE_CATALOGABCD_A1")),$arResult['letters']);
		if($arParams["SHOW_NUMBER"] && !$arParams["GROUP_NUMBER"])
			$arResult['letters']=array_merge(Array("0","1","2","3","4","5","6","7","8","9"),$arResult['letters']);
			
		if($arParams['LIST_ENABLE'] == 'Y')
		{
			if($arParams["HL_IBLOCK"])
			{
				$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($arParams["IBLOCK_ID"])->fetch();
				$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
				$entityDataClass = $entity->getDataClass();
				$arFilter = array(
					'order' => array($arParams['HL_NAME_FIELD'] => 'ASC')
				);
				$res = $entityDataClass::getList($arFilter);
			}
			else
			{
				$arSelect = array_merge($arParams["FIELD_CODE"], array(
					"ID",
					"NAME",
					"CODE",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
					"DETAIL_PAGE_URL",
				));
				$arFilter = array(
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"IBLOCK_LID" => SITE_ID,
					"IBLOCK_ACTIVE" => "Y",
					"ACTIVE_DATE" => "Y",
					"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				);
				if(isset($arParams["SECTION_ID"]))
				{
					$arFilter["SECTION_ID"] = $arParams["SECTION_ID"];
					$arFilter["INCLUDE_SUBSECTIONS"] = $arParams['INCLUDE_SUBSECTIONS'] == 'Y' ? 'Y' : 'N';
				}
				$res = CIBlockElement::GetList(Array("NAME"=>"asc"), array_merge($arrFilter_not_abcd,$arFilter), false, false, $arSelect);
			}
			$temp="";
			while($fields = $res->Fetch())
			{
				if($arParams["HL_IBLOCK"])
					$name = $fields[$arParams['HL_NAME_FIELD']];
				else	
					$name = $fields['NAME'];
				if(LANG_CHARSET != 'windows-1251')
					$name = iconv(LANG_CHARSET, 'windows-1251', $name);
				$temp = $name{0};
				$list[$temp][] = array('NAME' => $fields['NAME'], 'DETAIL_PAGE_URL'=> $fields['DETAIL_PAGE_URL']);
			}
			
			foreach($arResult['letters'] as $n=>$char)
			{
				$arResult['list'][$arResult['letters'][$n]] = $list[$char];
			}
		}
	}
	$this->IncludeComponentTemplate();
}
if($letter=="all")
{
}
elseif($letter=="number")
{
    ${$FILTER_NAME}[">NAME"] = "0";
    ${$FILTER_NAME}["<NAME"] = chr(ord(9)+1);
}
elseif($letter=="rus")
{
    ${$FILTER_NAME}[">NAME"] = GetMessage("YENISITE_CATALOGABCD_A");
    ${$FILTER_NAME}["<=NAME"] = GetMessage("YENISITE_CATALOGABCD_A111");
}
elseif($letter=="eng")
{
    ${$FILTER_NAME}[">NAME"] = "A";
    ${$FILTER_NAME}["<NAME"] = GetMessage("YENISITE_CATALOGABCD_A");
}
elseif($letter || $letter === '0')
{
    foreach($arResult['letters'] as $key=>$value)
    {
        if($letter==$value)
        {
                ${$FILTER_NAME}["NAME"] = $value."%";
        }
    }
}?>