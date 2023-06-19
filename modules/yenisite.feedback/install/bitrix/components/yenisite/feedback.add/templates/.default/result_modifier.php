<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult['FIELDS'] as &$arItem)
{
	if (($arItem['PROPERTY_TYPE'] == 'S' || $arItem['PROPERTY_TYPE'] == 'N') && $arItem['USER_TYPE'] == NULL)
    {
        //Set value for email field
        if (strcasecmp($arItem['CODE'], "email") == 0 && empty($arResult['DATA'][$arItem['CODE']]))
        {
            $emailVal = ($arResult['EMAIL'] !== FALSE) ? $arResult['EMAIL'] : "";
            $arResult['DATA'][$arItem['CODE']] = $emailVal;
        }
        
		$arItem['HTML'] = "<span class='right'><input type='text' class='txt' name='" . $arResult['CODE'] . "[" . $arItem['CODE'] . "]' value='".((!empty($arResult['DATA']))?$arResult['DATA'][$arItem['CODE']]:'')."'></span>";
	}
    	
	elseif ($arItem['PROPERTY_TYPE'] == 'S' && $arItem['USER_TYPE'] == 'HTML')
		$arItem['HTML'] = "<textarea name='" . $arResult['CODE'] . "[" . $arItem['CODE'] . "]'>".((!empty($arResult['DATA']))?$arResult['DATA'][$arItem['CODE']]:'')."</textarea>";
		
	elseif ($arItem['PROPERTY_TYPE'] == 'S' && $arItem['USER_TYPE'] == 'DateTime')
        {
		$arItem['HTML'] = "<input type='text' id='".$arItem['CODE']."' class='txt' name='" . $arResult['CODE'] . "[" . $arItem['CODE'] . "]'  value='".((!empty($arResult['DATA']))?$arResult['DATA'][$arItem['CODE']]:'')."'>";
                
                //$arItem['HTML'] .=
                ob_start();
                    $APPLICATION->IncludeComponent('bitrix:main.calendar', '', Array(
                          'SHOW_INPUT' => 'N',
                          'FORM_NAME' => $arResult['CODE'],
                          'INPUT_NAME' => $arItem['CODE'],
                          'INPUT_NAME_FINISH' => '',
                          'INPUT_VALUE' => '',
                          'INPUT_VALUE_FINISH' => '', 
                          'SHOW_TIME' => 'N', 
                          'HIDE_TIMEBAR' => 'Y', 
                       )
                    );
                $arItem['HTML'] .= ob_get_contents(); 
                ob_end_clean(); 

                
        }	
	elseif ($arItem['PROPERTY_TYPE'] == 'E' && $arItem['USER_TYPE'] == NULL)
		$arItem['HTML'] = "<input type='hidden' name='" . $arResult['CODE'] . "[" . $arItem['CODE'] . "]' value = '".$arParams['ELEMENT_ID']."'>";

        elseif ($arItem['PROPERTY_TYPE'] == 'F' && $arItem['USER_TYPE'] == NULL)
		$arItem['HTML'] = "<input type='file' name='" . $arResult['CODE'] . "[" . $arItem['CODE'] . "]'>";
}
unset($arItem);

if (is_array($arResult['SECTIONS']))
{
    $arResult['SECTIONS_SELECT'] = "<select name='" . $arResult['CODE'] . "[section]'>";
    foreach($arResult['SECTIONS'] as $section)
    {
        $arResult['SECTIONS_SELECT'] .= "<option value = '". $section['CODE'] ."'>". $section['NAME'] ."</option>";
    }
    
    $arResult['SECTIONS_SELECT'] .= "</select>";
    // = 
}

?>