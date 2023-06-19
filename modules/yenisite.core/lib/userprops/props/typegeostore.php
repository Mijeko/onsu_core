<?
namespace Yenisite\Core\Userprops\Props;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
if(!Loader::IncludeModule('catalog')) return;

class TypeGeoStore extends \CUserTypeIBlockElement {

    private static $nameClass = '\\Yenisite\\Core\\Userprops\\Props\\TypeGeoStore';

    function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "geo_store_element",
            "CLASS_NAME" => self::$nameClass,
            "DESCRIPTION" => GetMessage("USER_TYPE_GEO_STORE_NAME"),
            "BASE_TYPE" => "enum",
        );
    }

    function GetIBlockPropertyDescription()
    {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "geo_store_element",
            "DESCRIPTION" => GetMessage("USER_TYPE_GEO_STORE_NAME"),
            'GetPropertyFieldHtml' => array(self::$nameClass, 'GetPropertyFieldHtml'),
            'GetAdminListViewHTML' => array(self::$nameClass, 'GetAdminListViewHTML'),
            "GetSettingsHTML"	=>array(self::$nameClass,"GetSettingsHTML"),
            "PrepareSettings"	=>array(self::$nameClass,"PrepareSettings"),
            "ConvertToDB" => array(self::$nameClass,"ConvertToDB"),
            "ConvertFromDB" => array(self::$nameClass,"ConvertFromDB"),
            "GetAdminFilterHTML" => array(self::$nameClass,"GetFilterHTML"),
        );
    }

    function CheckFields($arUserField, $value)
    {
        $aMsg = array();
        return $aMsg;
    }

    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName,$edit = false)
    {
        $arValues = $arProperty['VALUE'] ? : $arProperty['USER_TYPE_SETTINGS']['GEO_STORE_ID'];
        $arValues = $value['VALUE'] ? : $arValues;
        if ($_REQUEST['action'] == 'edit' || !empty($_REQUEST['ID']) || $edit || (isset($_REQUEST['ID']) && $_REQUEST['ID'] == 0)){
            return self::GetHTMLListGeoStore($strHTMLControlName['VALUE'].'[]',$arValues,$arProperty['USER_TYPE_SETTINGS']['GEO_STORE_ID']);
        }else {
            $content = '<span>' . GetMessage('VALUES_OF_SELECTED_ID_GEO_STORE');
            if (is_array($arValues)) {
                foreach ($arValues as $geoStoreID) {
                    $content .= ' ' . $geoStoreID . ';';
                }
            } else{
                $content .= ' ' . intval($arValues) . ';';
            }
            $content .= '</span>';
            return $content;
        }
    }

    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl);
    }

    function GetEditFormHTMLMulty($arUserField, $arHtmlControl)
    {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl);
    }

    function GetAdminListEditHTML($arUserField, $arHtmlControl)
    {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl);
    }

    function GetAdminListEditHTMLMulty($arUserField, $arHtmlControl)
    {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl);
    }

    function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        return self::GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName);
    }
    function GetFilterHTML($arUserField, $arHtmlControl) {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl, true);
    }


    function GetSettingsHTML($arUserField = false, $arHtmlControl, $bVarsFromForm)
    {
        $result = '';
        $geoStore_ids = $arUserField['SETTINGS']['GEO_STORE_ID'] ? : $arUserField['USER_TYPE_SETTINGS']['GEO_STORE_ID'];
        $geoStore_ids = $geoStore_ids ? : array();
        if(Loader::IncludeModule('yenisite.geoipstore'))
        {
            $result .= '
			<tr>

				<td>'.GetMessage("USER_TYPE_GEO_STORE_DISPLAY").':</td>
				<td>
					'.self::GetHTMLListGeoStore($arHtmlControl["NAME"].'[GEO_STORE_ID][]',$geoStore_ids,array()).'
				</td>
			</tr>
			';
        }
        else
        {
            $result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_GEO_STORE_DISPLAY").':</td>
				<td>
					<input type="text" size="6" name="'.$arHtmlControl["NAME"].'[GEO_STORE_ID]" value="'.htmlspecialcharsbx($geoStore_ids).'">
				</td>
			</tr>
			';
        }
        return $result;
    }

    function PrepareSettings($arUserField)
    {
        $height = intval($arUserField["SETTINGS"]["LIST_HEIGHT"]);
        $disp = $arUserField["SETTINGS"]["DISPLAY"];
        if($disp!="CHECKBOX" && $disp!="LIST")
            $disp = "LIST";

        $active_filter = $arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y"? "Y": "N";

        return array(
            "DISPLAY" => $disp,
            "LIST_HEIGHT" => ($height < 1? 1: $height),
            "ACTIVE_FILTER" => $active_filter,
            "GEO_STORE_ID" => $arUserField["SETTINGS"]["GEO_STORE_ID"] ? : $arUserField["USER_TYPE_SETTINGS"]["GEO_STORE_ID"]
        );
    }

    function ConvertToDB($arProperty, $value){

        $return = false;
        if(is_array($value)&& array_key_exists("VALUE", $value))
        {
            $return = array("VALUE" => current($value["VALUE"]));
            if(strlen(trim($value["DESCRIPTION"])) > 0)$return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
        }
        return $return;
    }

    function ConvertFromDB($arProperty, $value){
        $return = false;
        if(!is_array($value["VALUE"]))
        {
            $return = array("VALUE" => $value["VALUE"]);
            if($value["DESCRIPTION"])$return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
        }
        return $return;
    }

    function GetList($arGeoStores = array())
    {
        $rsElement = false;
        $arFilter = array();

        foreach ($arGeoStores as $idGeoStore){
            $arFilter['ID'][] = $idGeoStore;
        }

        if(Loader::IncludeModule('yenisite.geoipstore'))
        {
            $rsElement = \CYSGeoIPStore::GetList('item', array(), $arFilter, array());
        }
        return $rsElement;
    }

    function GetHTMLListGeoStore ($name_property, $values, $arGeoStoresIDS){
        $dbGeoStore = self::GetList($arGeoStoresIDS);
        $arIDS = array();
        $selectedFirst = 0 == $values ? 'selected' : '';
        $content = '<select size="15" name="'.$name_property.'" id="'.$name_property.'">';
        $content .= '<option value="0" '.$selectedFirst.' >' . GetMessage('EMPTY_CHOOSE') . '</option>';
        while ($arGeoStore = $dbGeoStore->Fetch()){
            if (!in_array($arGeoStore['ID'],$arIDS)) {
                $arIDS[] = $arGeoStore['ID'];
                $selected = $arGeoStore['ID'] == $values ? 'selected' : '';
                $content .= '<option '.$selected.' value="' . $arGeoStore['ID'] . '">' . $arGeoStore['NAME'] . '</option>';
            }
        }
        $content .= '</select>';

        return $content;
    }


}