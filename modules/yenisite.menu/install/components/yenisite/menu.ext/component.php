<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/*
"FROM_IBLOCK" => "1"
"IS_PARENT" => "1"
"FILTER" - filter for VIEW_HIT GetList
*/

global $USER;

if(!CModule::IncludeModule("iblock")) die();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;
if($arParams["CACHE_TYPE"] == "N" || $arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "N")
	$arParams["CACHE_TIME"] = 0;

if(!$arParams["IBLOCK_TYPE_SORT_FIELD"])
    $arParams["IBLOCK_TYPE_SORT_FIELD"] = "name";
if(!$arParams["IBLOCK_TYPE_SORT_ORDER"])
    $arParams["IBLOCK_TYPE_SORT_ORDER"] = "asc";
if(!$arParams["IBLOCK_SORT_FIELD"])
    $arParams["IBLOCK_SORT_FIELD"] = "sort";
if(!$arParams["IBLOCK_SORT_ORDER"])
    $arParams["IBLOCK_SORT_ORDER"] = "asc";
if(!$arParams["SECTION_SORT_FIELD"])
    $arParams["SECTION_SORT_FIELD"] = "left_margin";
if(!$arParams["SECTION_SORT_ORDER"])
    $arParams["SECTION_SORT_ORDER"] = "asc";
if(!$arParams["ELEMENT_SORT_FIELD"])
    $arParams["ELEMENT_SORT_FIELD"] = "sort";
if(!$arParams["ELEMENT_SORT_ORDER"])
    $arParams["ELEMENT_SORT_ORDER"] = "asc";
	
if (!($arParams['SHOW_BY_CLICK']))
	$arParams['SHOW_BY_CLICK'] = 'N';

if($arParams['GET_SECTION_UF'] !== "Y") {
	$arParams['GET_SECTION_UF'] = "N";
}
$arParams["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];

do {
	if(is_array($arParams["IBLOCK_ID"])) {
		if(!in_array('all', $arParams['IBLOCK_ID'])) break;
	}
	$arParams["IBLOCK_ID"] = array();
} while (0);

foreach($arParams["IBLOCK_ID"] as $k=>$v)
	if($v==="")
		unset($arParams["IBLOCK_ID"][$k]);

if(!is_array($arParams["SECTION_ID"]))
	$arParams["SECTION_ID"] = array();
foreach($arParams["SECTION_ID"] as $k=>$v)
	if($v==="")
		unset($arParams["SECTION_ID"][$k]);

$arParams["DEPTH_LEVEL_SECTIONS"] = intval($arParams["DEPTH_LEVEL_SECTIONS"]);
if ($arParams["DEPTH_LEVEL_SECTIONS"] < 0) $arParams["DEPTH_LEVEL_SECTIONS"] = 0;

$ib_types_flag=1;
$ib_flag=1;
$ib_sections_flag=1;
$ib_elements_flag=1;

$DEPTH_LEVEL=0;

$LEVEL=0;

$arResult = array();
if(!isset($arParams["HIDE_ELEMENT"]))$arParams["HIDE_ELEMENT"] = 'N';
switch($arParams["HIDE_ELEMENT"])
{
	case 'AVAILABLE':
	case 'ACTIVE':
		$bIncCnt = true;
		break;
	case 'N':
	default:
		$bIncCnt = false;
		break;
}

if($arParams["ELEMENT_CNT"] == "Y") {$bIncCnt = true; }
if($this->StartResultCache(false, $USER->GetUserGroupString()))
{
	$arFilterDefault = array("ACTIVE" => "Y", "CATALOG_AVAILABLE" => "Y");
	if($arParams["DEPTH_LEVEL_FINISH"]>=1)
	{
		$i=0;
		$arChildSections = array();
        $arOrder = array($arParams['IBLOCK_TYPE_SORT_FIELD'] => $arParams['IBLOCK_TYPE_SORT_ORDER']) ;
		$arFilter = ($arParams['IBLOCK_TYPE_SORT_FIELD'] == 'name') ? array('LANGUAGE_ID' => LANGUAGE_ID) : array();

        if(is_set($arParams["IBLOCK_TYPE"]) && !is_array($arParams["IBLOCK_TYPE"]))
		{
			$arFilter['ID'] = $arParams["IBLOCK_TYPE"];
		    $db_iblock_type = CIBlockType::GetList($arOrder, $arFilter);
		}
        else
		{
         	$db_iblock_type = CIBlockType::GetList($arOrder, $arFilter);
        }

		while($ar_iblock_type = $db_iblock_type->Fetch())
		{
		   $continue = true;
		   
            if(is_array($arParams["IBLOCK_TYPE"]))
			{
                if(in_array($ar_iblock_type["ID"], $arParams["IBLOCK_TYPE"]))
                    $continue = false;
			
				foreach( $arParams["IBLOCK_TYPE_MASK"] as $val )
				{
					$val = substr($val, 0, -1);
					if (!empty($val) && strpos( $ar_iblock_type["ID"], $val ) !== false )
					{
						$continue = false;
						break;
					}
				}
		    }
		    else
			{
		        $continue = false;
		    }
			
		    if($continue) continue;
		    
			$LEVEL=1-($arParams["DEPTH_LEVEL_START"]-1);

			$arIBType = (empty($ar_iblock_type['NAME'])) ? CIBlockType::GetByIDLang($ar_iblock_type["ID"], LANG) : $ar_iblock_type;
			if($arIBType)
			{
			    $gcnt = 0;
			    $gi = 0;

				if($arParams["DEPTH_LEVEL_START"]<=1)
				{
					$DEPTH_LEVEL=$LEVEL;

					$arResult[$i][0]=$arIBType["NAME"];
					
					//$arResult[$i][1]="/catalog/". $ar_iblock_type["ID"]. "/";
					$url = str_replace("#IBLOCK_TYPE#", $ar_iblock_type["ID"], $arParams[IBLOCK_TYPE_URL]);
					$url = str_replace($arParams['IBLOCK_TYPE_URL_REPLACE'], "", $url);
					$arResult[$i][1]= $url;
					
					$arResult[$i][2]=Array();
					$arResult[$i][3]=Array("FROM_IBLOCK" => "1", "IS_PARENT" => "", "DEPTH_LEVEL" => $DEPTH_LEVEL, "FILTER" => $arFilterDefault+array("IBLOCK_TYPE" => $ar_iblock_type['ID']));
                    $gi = $i;
					$i++;
				}

				if($arParams["DEPTH_LEVEL_FINISH"]>=2)
				{
					$arFilter =  Array('TYPE'=>$ar_iblock_type["ID"], 'ID'=>$arParams["IBLOCK_ID"], 'SITE_ID'=>SITE_ID, 'ACTIVE'=>'Y', "CNT_ACTIVE"=>'Y');
					
					$res = CIBlock::GetList(array($arParams['IBLOCK_SORT_FIELD'] => $arParams['IBLOCK_SORT_ORDER']), $arFilter, $bIncCnt);
    						
					while($ar_res = $res->GetNext())
					{
						// проверка на наличие активных элементов в инфоблоке
						if($ar_res["ELEMENT_CNT"]<=0 && $arParams["HIDE_ELEMENT"]!='N')
							continue;
						// проверка на наличие элементов на складе в инфоблоке 
						if($arParams["HIDE_ELEMENT"] == 'AVAILABLE')
						{
							$available_cnt = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $ar_res["ID"], "ACTIVE"=>"Y", "CATALOG_AVAILABLE"=>'Y'), false, false, array("ID"));
							$available_cnt = $available_cnt->SelectedRowsCount();
							if($available_cnt<=0)
								continue;
						}
						
                        $search = array("#IBLOCK_CODE#", "#IBLOCK_ID#", "#IBLOCK_TYPE#");
                        $replace = array($ar_res["CODE"], $ar_res["ID"], $ar_iblock_type["ID"]);
                        $list_page_url = str_replace($search, $replace, $ar_res['LIST_PAGE_URL']);
                        $search = array("#CODE#", "#ID#", "#TYPE#");
                        $list_page_url = str_replace($search, $replace, $list_page_url);

							
						$list_page_url = str_replace("#IBLOCK_CODE#", $ar_res["CODE"], $ar_res['LIST_PAGE_URL']);
						$list_page_url = str_replace("#IBLOCK_ID#", $ar_res["ID"], $list_page_url);
    					$list_page_url = str_replace("#CODE#", $ar_res["CODE"], $list_page_url);
						$list_page_url = str_replace("#ID#", $ar_res["ID"], $list_page_url);
						$list_page_url = str_replace("#TYPE#", $ar_iblock_type["ID"], $list_page_url);
						$list_page_url = str_replace("#IBLOCK_TYPE#", $ar_iblock_type["ID"], $list_page_url);
					
						$LEVEL=2-($arParams["DEPTH_LEVEL_START"]-1);

						if($arParams["DEPTH_LEVEL_START"]<=2)
						{
							if($DEPTH_LEVEL<$LEVEL)
								if($arParams["DEPTH_LEVEL_START"]<=1)
									$arResult[$i-1][3]["IS_PARENT"]=1;
							$DEPTH_LEVEL=$LEVEL;

							$arResult[$i][0]=$ar_res["NAME"];
							
							//$arResult[$i][1]="/catalog/". $ar_res["CODE"]. "/";
							$arResult[$i][1] = $list_page_url;
							
							
							$arResult[$i][2]= Array();
							
							if($arParams["ELEMENT_CNT"] == "Y")
							{
								if($arParams["ELEMENT_CNT_AVAILABLE"] == "Y")
								{
									if($arParams["HIDE_ELEMENT"] != 'AVAILABLE')
									{
										$available_cnt = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $ar_res["ID"], "ACTIVE"=>"Y", "CATALOG_AVAILABLE"=>'Y'), false, false, array("ID"));
										$available_cnt = $available_cnt->SelectedRowsCount();
									}
									$gcnt += $available_cnt;
									$cnt = $available_cnt;
								}
								else
								{
									$gcnt += $ar_res["ELEMENT_CNT"];
									$cnt = $ar_res["ELEMENT_CNT"];
								}
							}
							
							$arResult[$i][3]= Array("FROM_IBLOCK"=>"1", "IS_PARENT"=>"", "DEPTH_LEVEL"=>$DEPTH_LEVEL, "PICTURE" => CFile::GetPath($ar_res["PICTURE"]), "ELEMENT_CNT"=>$cnt);
							if ($LEVEL == 1) {
								$arResult[$i][3]["FILTER"] = $arFilterDefault + array("IBLOCK_ID" => $ar_res['ID']);
							}
							$arResult[$i][3]["ITEM_IBLOCK_ID"] = $ar_res["ID"];

							$i++;
						} // if($arParams["DEPTH_LEVEL_START"]<=2)

						if($arParams["DEPTH_LEVEL_FINISH"] >= 3)
						{		
								$arFilter = array("IBLOCK_ID" => $ar_res["ID"], "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y","CNT_ACTIVE"=>'Y');
								
								if ($arParams["DEPTH_LEVEL_SECTIONS"] > 0) {
									$arFilter['<=DEPTH_LEVEL'] = $arParams["DEPTH_LEVEL_SECTIONS"];
								}
								$arMainSectionSelect = array("*");
    							if($arParams['GET_SECTION_UF'] == "Y") {
									$arMainSectionSelect[] = "UF_*";
								}
                                $res_s = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilter, $bIncCnt,$arMainSectionSelect);
								while($ar_res_s = $res_s->GetNext())
								{
									$LEVEL = 3 + ($ar_res_s["DEPTH_LEVEL"] - 1) - ($arParams["DEPTH_LEVEL_START"] - 1);
	
									// проверка на наличие активных элементов в разделе
									if($ar_res_s["ELEMENT_CNT"]<=0 && $arParams["HIDE_ELEMENT"]!='N')
											continue;
									// проверка на наличие элементов на складе в разделе
									if($arParams["HIDE_ELEMENT"] == 'AVAILABLE')
									{
										$available_cnt = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $ar_res["ID"], "SECTION_ID" =>$ar_res_s["ID"], "INCLUDE_SUBSECTIONS"=> 'Y',"ACTIVE"=>"Y", "CATALOG_AVAILABLE"=>'Y'), false, false, array("ID"));
										$available_cnt = $available_cnt->SelectedRowsCount();
										if($available_cnt<=0)
											continue;
									}
									if($arParams["DEPTH_LEVEL_START"] <= 3)
									{
										
										if($DEPTH_LEVEL < $LEVEL)
											if($arParams["DEPTH_LEVEL_START"] <= 2 || $ar_res_s["DEPTH_LEVEL"] > 1 )
												$arResult[$i-1][3]["IS_PARENT"] = 1;
										$DEPTH_LEVEL = $LEVEL;
										
										$arResult[$i][0]=$ar_res_s["NAME"];
										$arResult[$i][1]=$ar_res_s["SECTION_PAGE_URL"];//"/catalog/". $ar_res["CODE"]. "/index.php?SECTION_ID=". $ar_res_s["ID"];
										$arResult[$i][2]=Array();

										// Save list ID all children
										if($ar_res_s['IBLOCK_SECTION_ID'] > 0)
										{
											$arChildSections[$ar_res_s['IBLOCK_SECTION_ID']][] = $ar_res_s['ID'];
										}

										
										if($arParams["ELEMENT_CNT"] == "Y")
										{
											if($arParams["ELEMENT_CNT_AVAILABLE"] == "Y")
											{
												if($arParams["HIDE_ELEMENT"] != 'AVAILABLE')
												{
													$available_cnt = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $ar_res["ID"], "SECTION_ID" =>$ar_res_s["ID"], "INCLUDE_SUBSECTIONS"=> 'Y',"ACTIVE"=>"Y", "CATALOG_AVAILABLE"=>'Y'), false, false, array("ID"));
													$available_cnt = $available_cnt->SelectedRowsCount();
												}
												$cnt = $available_cnt;
											}
											else
												$cnt = $ar_res_s["ELEMENT_CNT"];
										}
							
										$arResult[$i][3]=Array("FROM_IBLOCK" => "1", "IS_PARENT" => "", "DEPTH_LEVEL" => $DEPTH_LEVEL, "PICTURE" => CFile::GetPath($ar_res_s["PICTURE"]), "ELEMENT_CNT" => $cnt);
										if ($LEVEL == 1) {
											$arResult[$i][3]["FILTER"] = $arFilterDefault + array("IBLOCK_ID" => $ar_res_s['IBLOCK_ID'], "SECTION_ID" => $ar_res_s["ID"], "INCLUDE_SUBSECTIONS" => "Y");
										}
										$arResult[$i][3]["ITEM_IBLOCK_ID"] = $ar_res_s["ID"];
										if($arParams['GET_SECTION_UF'] == "Y") {
											$arSectionUf = array();
											foreach ($ar_res_s as $key => &$val) {
												if(substr($key,0,3) == "UF_") {
													$arSectionUf[$key] = $val;
												}
											}
											unset($key); unset($val);
											$arResult[$i][3]['UF_FIELDS'] = $arSectionUf;
										}
										$i++;
	                                }
									
										if($arParams["DEPTH_LEVEL_FINISH"] >= 4)
										{        
	
										$res_e = CIBlockElement::GetList(Array($arParams["ELEMENT_SORT_FIELD"]=>$arParams["ELEMENT_SORT_ORDER"]), Array("IBLOCK_ID" => $ar_res["ID"], "SECTION_ID" => $ar_res_s["ID"],"ACTIVE"=>"Y"));
										while($ar_res_e = $res_e->GetNext())
										{
											$LEVEL = 3 + $ar_res_s["DEPTH_LEVEL"] - ($arParams["DEPTH_LEVEL_START"] - 1);

											if($arParams["DEPTH_LEVEL_START"] <= 4)
											{
		
												if($DEPTH_LEVEL<$LEVEL)
													if($arParams["DEPTH_LEVEL_START"] <= 3)
														$arResult[$i-1][3]["IS_PARENT"] = 1;
												$DEPTH_LEVEL=$LEVEL;

												$arResult[$i][0]=$ar_res_e["NAME"];
												$arResult[$i][1]=$ar_res_e["DETAIL_PAGE_URL"];
												$arResult[$i][2]=Array();
        								$arResult[$i][3]=Array("FROM_IBLOCK" => "1", "IS_PARENT" => "", "DEPTH_LEVEL" => $DEPTH_LEVEL);
												$arResult[$i][3]["ITEM_IBLOCK_ID"] = $ar_res_e["ID"];
												$i++;
											}
        								}
        
									} // if($arParams["DEPTH_LEVEL_FINISH"] >= 4)

								} // while($ar_res_s = $res_s->Fetch())

						} // if($arParams["DEPTH_LEVEL_FINISH"] >= 3)

					}  // while($ar_res = $res->Fetch())
					
					if($arParams["DEPTH_LEVEL_START"] <= 1) $arResult[$gi][3]["ELEMENT_CNT"] = $gcnt;
					
					// if($arResult[$gi][3]["ELEMENT_CNT"]<=0 && $arParams["HIDE_ELEMENT"]!='N')
						// unset($arResult[$gi]);
					if($arParams["HIDE_ELEMENT"]!='N')
					{
						if(isset($arResult[$gi+1]))
						{
							if($arResult[$gi+1][3]["IS_PARENT"]==1 && $arResult[$gi][3]["DEPTH_LEVEL"]==$arResult[$gi+1][3]["DEPTH_LEVEL"])
							{
								unset($arResult[$gi]);
							}
							if($arResult[$gi][3]["DEPTH_LEVEL"]==1 && $arResult[$gi][3]["IS_PARENT"]!=1)
							{
								unset($arResult[$gi]);
							}
								
						}
						else
							unset($arResult[$gi]);
					}
					
				} //if($arParams["DEPTH_LEVEL_FINISH"] >= 2)

			} //if($arIBType = CIBlockType::GetByIDLang($ar_iblock_type["ID"], LANG))

		} //while($ar_iblock_type = $db_iblock_type->Fetch())
		
		// set id of child sections
		foreach($arResult as $key => $arItem)
		{
			if(array_key_exists ($arItem[3]['ITEM_IBLOCK_ID'] , $arChildSections ))
			{
				$arResult[$key][3]['CHILD_SECTION_ID'] = $arChildSections[$arItem[3]['ITEM_IBLOCK_ID']];
			}
		}
		unset($arChildSections, $arItem);

	} //if($arParams["DEPTH_LEVEL_FINISH"] >= 1)
	
// $obCache->EndDataCache(array('arResult' => $arResult));
$this->EndResultCache();
}
else
{
	// $vars = $obCache->GetVars();
	// $arResult = $vars['arResult'];
}

return $arResult;
?>