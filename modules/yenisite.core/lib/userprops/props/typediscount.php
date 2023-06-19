<?
namespace Yenisite\Core\Userprops\Props;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
if(!Loader::IncludeModule('catalog')) return;

class Typediscount extends \CUserTypeIBlockElement{

    private static $nameClass = '\\Yenisite\\Core\\Userprops\\Props\\Typediscount';

    static function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "discount_element",
            "CLASS_NAME" => self::$nameClass,
            "DESCRIPTION" => GetMessage("USER_TYPE_DISCOUNT_NAME"),
            "BASE_TYPE" => "int",
        );
    }

    function GetIBlockPropertyDescription()
    {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "discount_element",
            "DESCRIPTION" => GetMessage("USER_TYPE_DISCOUNT_NAME"),
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
        $arValues = $arProperty['VALUE'] ? : $arProperty['USER_TYPE_SETTINGS']['DISCOUNT_ID'];
        $arValues = $value['VALUE'] ? : $arValues;
        if ($_REQUEST['action'] == 'edit' || !empty($_REQUEST['ID']) || $edit || (isset($_REQUEST['ID']) && $_REQUEST['ID'] == 0)){
            return self::GetHTMLListDiscounts($strHTMLControlName['VALUE'].'[]',$arValues,$arProperty['USER_TYPE_SETTINGS']['DISCOUNT_ID']);
        }else {
            $content = '<span>' . GetMessage('VALUES_OF_SELECTED_ID_DISCOUNT');
            foreach ($arValues as $discountID) {
                $content .= ' ' . $discountID . ';';
            }
            $content .= '</span>';
            return $content;
        }
    }

    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        return self::GetPropertyFieldHtml($arUserField, array(), $arHtmlControl);
    }

    function GetAdminListEditHTML($arUserField, $arHtmlControl)
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
        $discount_ids = $arUserField['SETTINGS']['DISCOUNT_ID'] ? : $arUserField['USER_TYPE_SETTINGS']['DISCOUNT_ID'];
        $discount_ids = $discount_ids ? : array();
        if(Loader::IncludeModule('catalog'))
        {
            $result .= '
			<tr>

				<td>'.GetMessage("USER_TYPE_DISCOUNT_DISPLAY").':</td>
				<td>
					'.self::GetHTMLListDiscounts($arHtmlControl["NAME"].'[DISCOUNT_ID][]',$discount_ids,array()).'
				</td>
			</tr>
			';
        }
        else
        {
            $result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_DISCOUNT_DISPLAY").':</td>
				<td>
					<input type="text" size="6" name="'.$arHtmlControl["NAME"].'[DISCOUNT_ID]" value="'.htmlspecialcharsbx($discount_id).'">
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
            "DISCOUNT_ID" => $arUserField["SETTINGS"]["DISCOUNT_ID"] ? : $arUserField["USER_TYPE_SETTINGS"]["DISCOUNT_ID"]
        );
    }

    function ConvertToDB($arProperty, $value){

        $return = false;
        if(is_array($value)&& array_key_exists("VALUE", $value))
        {
            $return = array("VALUE" => serialize($value["VALUE"]),);
            if(strlen(trim($value["DESCRIPTION"])) > 0)$return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
        }
        return $return;
    }

    function ConvertFromDB($arProperty, $value){
        $return = false;
        if(!is_array($value["VALUE"]))
        {
            $return = array("VALUE" => unserialize($value["VALUE"]),);
            if($value["DESCRIPTION"])$return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
        }
        return $return;
    }

    static function GetList($arDiscounts = array())
    {
        $rsElement = false;
        $arFilter = array();

        foreach ($arDiscounts as $IDdiscount){
            $arFilter['ID'][] = $IDdiscount;
        }

        if(Loader::IncludeModule('catalog'))
        {
            $rsElement = \CCatalogDiscount::GetList(array(),$arFilter,false,false,array());
        }
        return $rsElement;
    }

    function GetHTMLListDiscounts ($name_property, $values, $arDiscountsIDS){
        $rsDiscounts = self::GetList($arDiscountsIDS);
        $arIDS = array();
        $selectedFirst = in_array(0,$values) ? 'selected' : '';
        $content = '<select size="15" name="'.$name_property.'" id="'.$name_property.'" multiple="multiple">';
        $content .= '<option value="0" '.$selectedFirst.' >' . GetMessage('EMPTY_CHOOSE') . '</option>';
        while ($arDiscount = $rsDiscounts->Fetch()){
            if (!in_array($arDiscount['ID'],$arIDS)) {
                $arIDS[] = $arDiscount['ID'];
                $selected = in_array($arDiscount['ID'],$values) ? 'selected' : '';
                $content .= '<option '.$selected.' value="' . $arDiscount['ID'] . '">' . $arDiscount['NAME'] . '</option>';
            }
        }
        $content .= '</select>';

        return $content;
    }


}