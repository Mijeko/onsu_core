<?
namespace Yenisite\Core;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

//Loc::loadMessages(__FILE__);

class IBlock
{
	/**
	 * Init iblock module
	 *
	 * @return bool
	 */
	static public function init()
	{
		static $bMod;
		if (!isset($bMod)) {
			$bMod = Loader::includeModule('iblock');
		}
		return $bMod;
	}

	/**
	 * Get array with site IDs that iblock attached to
	 *
	 * @param int $id - IBlock ID
	 * @return array - array of SITE_ID
	 */
	static public function getSites($id)
	{
		if (!self::init()) return array();
		static $arSites = array();

		if (!isset($arSites[$id])) {
			$arSites[$id] = array();
			$rsSites = \CIBlock::GetSite($id);
			while ($arSite = $rsSites->Fetch()) {
				$arSites[$id][] = $arSite['SITE_ID'];
			}
		}
		return $arSites[$id];
	}

	/**
	 * Get iblock id for given element id
	 *
	 * Checks iblock SITE_ID links if second parameter exists
	 * If it is not attached to given SITE_ID then returns false
	 *
	 * @param int $id - iblock element id
	 * @param string $siteID - SITE_ID
	 * @return int|false
	 */
	static public function getIdByElement($id, $siteId = null)
	{
		if (!self::init()) return false;
		static $arCache = array();

		if (!array_key_exists($id, $arCache)) {
			$arCache[$id] = \CIBlockElement::GetIBlockByID($id);
		}
		$iblockId = $arCache[$id];

		if(isset($siteId))
		{
			$arSites = self::getSites($iblockId);
			if(!in_array($siteId, $arSites)) return false;
		}
		return $iblockId;
	}

	/**
	 * Get IBLOCK_ID from IBLOCK_CODE or IBLOCK_ID (if you're not sure)
	 *
	 * @param string|int $code - code or id
	 * @return int
	 */
	static public function getIdByCode($code)
	{
		static $arCache = array();

		if (!isset($arCache[$code])) {
			if (is_int($code)) {
				$arCache[$code] = $code;
			}
			elseif (self::init()) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$rsIblock = \CIBlock::GetList(array(), array('CODE' => $code));
				$arIblock = $rsIblock->GetNext(false, false);
				$arCache[$code] = intval($arIblock['ID']);
			} else {
				$arCache[$code] = 0;
			}
		}
		return $arCache[$code];
	}


    //GET MANY ELEMENTS BY ANY FILTER
    public static function getElementsByFilter($iblockId,$arFilter = array(),$needProps = true){
        $arReturn = array();
        if (!is_array($arFilter) || empty ($iblockId)){
            return $arReturn;
        }

        if (!Loader:: includeModule('iblock')){
            return $arReturn;
        }
        $arrFilter = array('ACTIVE' => 'Y','IBLOCK_ID' => $iblockId);
        $arFilter = array_merge($arrFilter,$arFilter);

        $arElements = array();
        $dbElements = \CIBlockElement::GetList(array('NAME' => 'ASC'),$arFilter,false,false, array('IBLOCK_ID','ID','NAME','PREVIEW_TEXT'));
        while ($dbElement = $dbElements->GetNextElement()){
            $arFields = $dbElement->GetFields();
            $arElement = $arFields;
            if ($needProps) {
                $arProps = $dbElement->GetProperties();
                $arElement['PROPERTIES'] = $arProps;
            }
            $arElements[] = $arElement;
        }
        unset($dbElements,$dbElement,$arFields,$arProps);

        return $arElements;
    }

    //CREATE NEW PROPS
    public static function createProps ($iblock_id, $code, $param){
        // -------------	CHECK AND CREATE NEED PROPERTY	------------- //
        $res = \CIBlockProperty::GetList(Array(), Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id, "CODE" => $code));
        if (!$ar_res = $res->GetNext()) {
            $param["MULTIPLE"] = in_array($param["MULTIPLE"], array("Y", "N")) ? $param["MULTIPLE"] : "N";
            $param['SMART_FILTER'] = in_array($param['SMART_FILTER'], array('Y', 'N')) ? $param['SMART_FILTER'] : 'N';
            $param["PROPERTY_TYPE"] = in_array($param["TYPE"], array("S", "N", "L", "F", "G", "E")) ? $param["TYPE"] : "S";
            $param["LIST_TYPE"] = in_array($param["LIST_TYPE"], array("C", "L")) ? $param["LIST_TYPE"] : "L";
            unset($param['TYPE']);

            if (strlen($param["NAME"]) <= 0) {
                $param["NAME"] = (GetMessage($code) == '') ? $code : GetMessage($code);
            }
            $param["SORT"] = (intval($param["SORT"]) > 0) ? $param["SORT"] : '20000';
            $param['CODE'] = $code;
            $param['ACTIVE'] = 'Y';
            $param['IBLOCK_ID'] = $iblock_id;

            if (isset($param['VALUES'])) {
                $arValues = $param['VALUES'];
                unset($param['VALUES']);
            }

            $ibp = new \CIBlockProperty;
            $PropID = $ibp->Add($param);
            if (intval($PropID) <= 0) {
                return false;
            }

            if ($param['PROPERTY_TYPE'] == 'L' && is_array($arValues)) {
                foreach ($arValues as $arEnumFields) {
                    $arEnumFields['PROPERTY_ID'] = $PropID;
                    \CIBlockPropertyEnum::Add($arEnumFields);
                }
            }

            return $PropID;
        }
        return $ar_res;
    }

    public static function updatePropOfElement ($value, $elementID, $iblockID, $propCode){
        if (empty($elementID) || empty($iblockID) || empty($propCode)) return false;

        \CIBlockElement::SetPropertyValuesEx($elementID, $iblockID, array($propCode => $value));
    }

    public static function getValueOfPropByCode ($IBLOCK_ID, $ELEMENT_ID, $PROP_CODE){
        $arPropValue = array();
        $rs = \CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, array(), array('CODE' => $PROP_CODE));
        while ($ar = $rs->GetNext(false, false)) {
            if (!empty($ar['VALUE'])) {
                if (!is_array($ar['VALUE'])) {
                    $arPropValue[] = $ar['VALUE'];
                } else{
                    $arPropValue = $ar['VALUE'];
                }
            }
        }

        return $arPropValue;
    }

    public static function clearIblockCacheByTag($idIblock = ''){
        if (empty($idCatalog)) return;
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag("iblock_id_".$idIblock);
    }

    public static function getEnumValuesOfProp ($arPropCode,$IBLOCK_ID){
        if (empty($arPropCode) || empty($IBLOCK_ID)) return array();

        $arReturn = array();

        $arPropCode = !is_array($arPropCode) ? array($arPropCode) : $arPropCode;

        foreach ($arPropCode as $propCode) {
            $property_enums = \CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$propCode));
            while($enum_fields = $property_enums->GetNext())
            {
                $arReturn[$propCode][$enum_fields["VALUE"]] = $enum_fields["ID"];
            }
        }

        return $arReturn;
    }

    public static function getSubSections($arDataOfSection){

        if (!Loader::includeModule('iblock')) return array();

        $cacheId = 'section_'.$arDataOfSection['ID'].'_'.$arDataOfSection['IBLOCK_ID'];
        $arReturn = array();

        if ($arReturn = Tools::getSavedValues($cacheId)){
            return $arReturn;
        }

        $arFilter = array(
            'IBLOCK_ID' => $arDataOfSection['IBLOCK_ID'],
            '>DEPTH_LEVEL' => $arDataOfSection['DEPTH_LEVEL']
            );

        if (!empty($arDataOfSection['RIGHT_MARGIN']) && !empty($arDataOfSection['LEFT_MARGIN'])) {
            $arFilter['>LEFT_MARGIN'] = $arDataOfSection['LEFT_MARGIN'];
            $arFilter['<RIGHT_MARGIN'] = $arDataOfSection['RIGHT_MARGIN'];
        }

        $dbSubSections = \CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);

        while ($arSubSection = $dbSubSections->Fetch()){
            $arReturn[$arSubSection['ID']] = $arSubSection;
        }
        unset($dbSubSections,$arFilter,$arSubSection);

        Tools::saveSomeValuesInCache($arReturn,$cacheId,true,$arDataOfSection['IBLOCK_ID']);

        return $arReturn;
    }

    public static function getTreeToSection ($blockID, $toSectionID){
        if (!Loader::includeModule('iblock')) return array();

        $cacheId = 'tree_sections_'.$toSectionID.'_'.$blockID;
        $arReturn = array();

        if ($arReturn = Tools::getSavedValues($cacheId)){
            return $arReturn;
        }


        $nav = \CIBlockSection::GetNavChain($blockID, $toSectionID);

        while($arSectionPath = $nav->GetNext()){
            $arReturn[] = $arSectionPath;
        }

        Tools::saveSomeValuesInCache($arReturn,$cacheId,true,$arReturn);

        return $arReturn;
    }

    public static function getIblocksByFilter($arFilter = array()){
        $arReturn = array();
        if (!Loader::includeModule('iblock')) return $arReturn;

        $cacheId = 'iblocks_get_'.implode('_',$arFilter);

        if ($arReturn = Tools::getSavedValues($cacheId)){
            return $arReturn;
        }

        $bdBlocks = \CIBlock::GetList(Array(),$arFilter);
        while($arIblock = $bdBlocks->Fetch())
        {
            $arReturn[$arIblock['ID']] = $arIblock;
        }

        Tools::saveSomeValuesInCache($arReturn,$cacheId);

        return $arReturn;
    }
}