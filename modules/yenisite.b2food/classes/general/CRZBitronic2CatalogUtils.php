<?php

/**
 *
 *
 *
 */

use Bitrix\Currency\CurrencyTable as BX_CurrencyTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;
use Yenisite\Core\Ajax;
use Yenisite\Core\Tools;
use Yenisite\Core\Resize;
use Yenisite\Core\ModulesCheck;
use Yenisite\Core\IBlock as YenIblock;

Loc::loadMessages(__FILE__);
//set constants with module name in MODULE_ROOT
include __DIR__ . '/../../constants.php';

class CRZBitronic2CatalogUtils
{
    static private $_cacheDir = '/bitronic2/catalog';
    static private $_cacheTime = 604800;
    private static $arGeoStoreData = array();
    private static $prop_for_filter_of_geo_store = 'RZ_GEO_STORE_ITEM';
    private static $tmp_cache_prop_for_filter_of_geo_store = '';
    private static $str_feo_store_module = 'yenisite.geoipstore';

    // SYSTEM PROPS
    static public $_systemPropsMask = array('CML2_', 'TURBO_', 'RZ_');
    static public $_systemProps = array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY',
        'SHOW_MAIN', 'HIT', 'SALE', 'PHOTO', 'DESCRIPTION', 'MORE_PHOTO', 'NEW', 'KEYWORDS', 'TITLE', 'FORUM_TOPIC_ID', 'FORUM_MESSAGE_CNT',
        'PRICE_BASE', 'H1', 'YML', 'FOR_ORDER', 'WEEK_COUNTER', 'WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'RECOMMEND',
        'vote_count', 'vote_sum', 'rating', 'BLOG_POST_ID', 'BLOG_COMMENTS_CNT', 'TURBO_YANDEX_LINK', 'MINIMUM_PRICE', 'MAXIMUM_PRICE',
        'vote_count', 'vote_sum', 'rating', 'BLOG_POST_ID', 'BLOG_COMMENTS_CNT', 'TURBO_YANDEX_LINK', 'MINIMUM_PRICE', 'MAXIMUM_PRICE',
        'STORE_AMOUNT_BOOL', 'RZ_AVAILABLE', 'RZ_VIP', 'RZ_CREDIT_HINT', 'RZ_DELIVERY_HINT', 'RZ_GUARANTEE_HINT', 'RZ_FOR_ORDER_TEXT', 'RZ_CREDIT', 'RZ_DELIVERY', 'RZ_GUARANTEE', 'ARTICUL_PROP');

    public static function insertServiceInfo()
    {
        // TODO: COMPOSITE!!!!!
        return '<pre class="cache_time_debug">' . date('H:i:s - d.m.Y') . "</pre>";
    }

    public static function isSale(){
        return Loader::includeModule('sale');
    }

    public static function noJsPage()
    {
        return SITE_DIR . "nojs.php";
    }

    public static function ShowMessage($arMess)
    {
        // TYPE => (success , fail)
        $arConvertType = array(
            "ERROR" => "fail",
            "error" => "fail",
            "OK" => "success",
            "ok" => "success"
        );

        if (!is_array($arMess) || !array_key_exists("MESSAGE", $arMess)) {
            $arMess = Array("MESSAGE" => $arMess, "TYPE" => "ERROR");
        }

        if (is_array($arMess["MESSAGE"])) {
            $arMess["MESSAGE"] = implode('<br>', $arMess["MESSAGE"]);
        }

        $arMess["TYPE"] = strtr($arMess["TYPE"], $arConvertType);

        if ($arMess["MESSAGE"] <> "") {
            $arSearch = array(GetMessage('BITRONIC2_DELETE_LOGIN'), GetMessage('BITRONIC2_REPLACE_LOGIN'));
            $arReplace = array('', 'e-mail');
            $arMess["MESSAGE"] = str_ireplace($arSearch, $arReplace, $arMess["MESSAGE"]);
            $arMess["MESSAGE"] = str_replace(array("<br>", "<br />"), "\n", $arMess["MESSAGE"]);

            $arMess["MESSAGE"] = htmlspecialcharsbx($arMess["MESSAGE"]); //why do you need this?

            AddMessage2Log($arMess["MESSAGE"], RZ_B2_MODULE_FULL_NAME);

            $arMess["MESSAGE"] = str_replace("\n", "<br />", $arMess["MESSAGE"]);
            $arMess["MESSAGE"] = str_replace("&amp;", "&", $arMess["MESSAGE"]);

            //stupid hack
            $arMess["MESSAGE"] = str_replace(array('&lt;a href=&quot;', 'yes&quot;&gt;', '&lt;/a&gt;'), array('<a href="', 'yes">', '</a>'), $arMess['MESSAGE']);

            CJSCore::RegisterExt('rz_b2_ajax_core', array(
                'js' => SITE_TEMPLATE_PATH . "/js/back-end/ajax_core.js",
                'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/ajax.php',
                'rel' => array('jquery'),
            ));
            CJSCore::Init(array('rz_b2_ajax_core'));

            ?>
            <script>
                if (typeof RZB2.ajax.showMessage != 'undefined') {
                    RZB2.ajax.showMessage('<?=$arMess["MESSAGE"]?>', '<?=$arMess["TYPE"]?>');
                }
                else {
                    console.log('undefined RZB2.ajax.showMessage');
                    console.log('<?=$arMess["MESSAGE"]?>');
                }
            </script><?
        }
    }

    public static function getElementPriceFormat($currency = false, $notFormatValue = 0, $formatValue = false, $arAttr = array())
    {
        $notFormatValue = str_replace(' ', '', $notFormatValue);
        $notFormatValue = floatval($notFormatValue);
        if (!$currency && CModule::IncludeModule("sale"))
            $currency = CSaleLang::GetLangCurrency(SITE_ID);
        if (!$currency)
            $currency = 'RUB';

        $priceFormat = '';
        $price_mask = '#PRICE_VALUE#';
        $templateStr = $price_mask;
        if (!isset($arAttr['CLASS']) || empty($arAttr['CLASS'])) {
            $arAttr['CLASS'] = 'value';
        }

        if (!empty($arAttr)) {
            if (!isset($arAttr['TAG']) || empty($arAttr['TAG'])) {
                $arAttr['TAG'] = 'span';
            }

            $templateStr = '<' . strtolower($arAttr['TAG']);
            foreach ($arAttr as $attr => $value) {
                if ($attr == 'TAG') continue;
                $templateStr .= ' ' . strtolower($attr) . '="' . $value . '"';
            }
            $templateStr .= '>' . $price_mask . '</' . strtolower($arAttr['TAG']) . '>';
        }

        $bCurrency = CModule::IncludeModule('currency');

        if ($bCurrency) {
            $notFormatValue = CCurrencyLang::CurrencyFormat($notFormatValue, $currency, false);
        }
        if ($currency == 'RUB') {
            $priceFormat = str_replace($price_mask, $notFormatValue, $templateStr) . ' <span class="b-rub">' . GetMessage('BITRONIC2_RUB_CHAR') . '</span>';
        } elseif (!$bCurrency || !$currency) {
            if (empty($formatValue)) $formatValue = $notFormatValue;
            $priceFormat = str_replace($price_mask, $formatValue, $templateStr);
        } else {
            $arCurFormat = CCurrencyLang::GetCurrencyFormat($currency);
            $priceFormat = str_replace($price_mask, $notFormatValue, str_replace('#', $templateStr, $arCurFormat['FORMAT_STRING']));
        }

        return $priceFormat;
    }

    public static function getCurrencyLang($currency)
    {
        if (!\Bitrix\Main\Loader::IncludeModule('currency')) {
            return '';
        }
        if ($currency == 'RUB') {
            $return = ' <span class="b-rub">' . GetMessage('BITRONIC2_RUB_CHAR') . '</span>';
        } else {
            $arCurFormat = CCurrencyLang::GetCurrencyFormat($currency);
            $return = str_replace('#', '', $arCurFormat['FORMAT_STRING']);
        }
        return $return;
    }

    /**
     * Return pictures id array of element
     */
    public static function getElementPictureArray($arElement, $image_prop = 'MORE_PHOTO', $bSearchOffers = true)
    {
        if (!is_array($arElement))
            return false;

        $obCache = new CPHPCache;
        $cache_id = 'ELEM_' . $arElement['ID'] . '_PICT_ARRAY_' . $image_prop . '_OFFERS_' . serialize($bSearchOffers);

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $vars = $obCache->GetVars();
            $arReturn = $vars;
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $arReturn = array();

            $detail = self::GetFieldPicSrc($arElement['DETAIL_PICTURE']);
            if ($detail !== false)
                $arReturn[] = $detail;

            if (is_array($arElement['PROPERTIES'][$image_prop])) {
                $arPropFile = $arElement['PROPERTIES'][$image_prop]['VALUE'];
                if (is_array($arPropFile))
                    $arReturn = array_merge($arReturn, $arPropFile);
                elseif (intval($arPropFile) > 0)
                    $arReturn[] = intval($arPropFile);
            }

            if ($detail === false) {
                $preview = self::GetFieldPicSrc($arElement['PREVIEW_PICTURE']);
                if ($preview !== false)
                    $arReturn[] = $preview;
            }

            if (empty($arReturn) && $bSearchOffers) {
                $arReturn[] = self::getElementPictureById($arElement['ID'], false, true, true);
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arReturn);
        }
        unset($obCache);


        return $arReturn;
    }

    /**
     * Function return image ID of element
     */
    public static function getSectionPictureById($itemId, $resizer_set = false, $returnImgId = false)
    {
        if (!CModule::IncludeModule("iblock") || intval($itemId) <= 0)
            return false;

        $obCache = new CPHPCache;
        $cache_id = 'SECTION_' . $itemId . '_PICT';
        if (intval($resizer_set) > 0 && CModule::IncludeModule("yenisite.resizer2")) {
            $bResizer = true;
            // $cache_id .= "_RS".$resizer_set;
        }

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $vars = $obCache->GetVars();
            $arReturn = $vars;
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $dbSection = CIBlockSection::GetByID($itemId);
            if ($arSection = $dbSection->GetNext()) {
                // get image :
                $image = self::GetFieldPicSrc($arSection['PICTURE']);

                $arReturn = array(
                    'PRODUCT_PICTURE_ID' => $image,
                    'PRODUCT_PICTURE_SRC' => CFile::GetPath($image),
                );

                if (defined("BX_COMP_MANAGED_CACHE")) {
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arSection['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }
            } else {
                $arReturn['PRODUCT_PICTURE_ID'] = 0;
                $arReturn['PRODUCT_PICTURE_SRC'] = '';
            }

            $obCache->EndDataCache($arReturn);
        }
        unset($obCache);

        if ($returnImgId) {
            return $arReturn['PRODUCT_PICTURE_ID'];
        } else {
            if ($bResizer)
                $arReturn['PRODUCT_PICTURE_SRC'] = CResizer2Resize::ResizeGD2($arReturn['PRODUCT_PICTURE_SRC'], intval($resizer_set));

            return $arReturn['PRODUCT_PICTURE_SRC'];
        }
    }

    /**
     * Function return image ID of element
     */
    public static function getElementPictureById($itemId, $resizer_set = false, $findInParent = true, $returnImgId = false)
    {
        if (!CModule::IncludeModule("iblock")) {
            return false;
        }
        if (intval($itemId) <= 0) {
            $sectionId = intval(ltrim($itemId, 'Ss'));
            if ($sectionId > 0) {
                return self::getSectionPictureById($sectionId, $resizer_set, $returnImgId);
            }
            return false;
        }

        $obCache = new CPHPCache;
        $cache_id = 'ELEM_' . $itemId . '_PICT';
        if (intval($resizer_set) > 0 && CModule::IncludeModule("yenisite.resizer2")) {
            $bResizer = true;
            // $cache_id .= "_RS".$resizer_set;
        }

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $vars = $obCache->GetVars();
            $arReturn = $vars;
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $dbElement = CIBlockElement::GetByID($itemId);
            if ($arElement = $dbElement->GetNext()) {
                // get image :
                $image = self::getElementPicture($arElement);
                if (intval($image) <= 0 && \Bitrix\Main\Loader::includeModule('catalog')) {
                    $arOffers = CIBlockPriceTools::GetOffersArray($arElement['IBLOCK_ID'], $arElement['ID'], array('sort' => 'asc'), array('ID'), array(), 0, array(), false, array());

                    if (!empty($arOffers)) {
                        foreach ($arOffers as $arOffer) {
                            $image = self::getElementPictureById($arOffer['ID'], false, false, true);
                            if (intval($image) > 0) {
                                break;
                            }
                        }
                        unset($arOffers);
                    } elseif ($findInParent) {
                        $arElement = CCatalogSKU::GetProductInfo($arElement['ID']);
                        if (is_array($arElement) && intval($arElement['ID'] > 0)) {
                            $image = self::getElementPictureById($arElement['ID'], false, true, true);
                        }
                    }
                }

                $arReturn = array(
                    'PRODUCT_PICTURE_ID' => $image,
                    'PRODUCT_PICTURE_SRC' => CFile::GetPath($image),
                );

                if (defined("BX_COMP_MANAGED_CACHE")) {
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }
            } else {
                $arReturn['PRODUCT_PICTURE_ID'] = 0;
                $arReturn['PRODUCT_PICTURE_SRC'] = '';
            }

            $obCache->EndDataCache($arReturn);
        }
        unset($obCache);

        if ($returnImgId) {
            return $arReturn['PRODUCT_PICTURE_ID'];
        } else {
            if ($bResizer)
                $arReturn['PRODUCT_PICTURE_SRC'] = CResizer2Resize::ResizeGD2($arReturn['PRODUCT_PICTURE_SRC'], intval($resizer_set));

            return $arReturn['PRODUCT_PICTURE_SRC'];
        }
    }

    public static function getElementPicture($arElement, $arParamsImage = 'DETAIL_PICTURE', $default_image_code = 'MORE_PHOTO')
    {
        if (!is_array($arElement))
            return false;

        $picsrc = false;
        $find_in_prop = false;
        if ($arParamsImage != 'PREVIEW_PICTURE' && $arParamsImage != 'DETAIL_PICTURE') {
            $find_in_prop = true;
            if (!$picsrc = self::GetPropPicSrc($arElement, $arParamsImage)) {
                $picsrc = self::GetPropPicSrc($arElement, $default_image_code);
            }
        }

        if ($arParamsImage == 'DETAIL_PICTURE' || $arParamsImage == 'PREVIEW_PICTURE') {
            $picsrc = self::GetFieldPicSrc($arElement[$arParamsImage]);
        }

        if (!$picsrc) {
            if (!$find_in_prop)
                $picsrc = self::GetPropPicSrc($arElement, $default_image_code);

            if (!$picsrc && $arParamsImage != 'DETAIL_PICTURE')
                $picsrc = self::GetFieldPicSrc($arElement['DETAIL_PICTURE']);

            if (!$picsrc && $arParamsImage != 'PREVIEW_PICTURE')
                $picsrc = self::GetFieldPicSrc($arElement['PREVIEW_PICTURE']);
        }
        return $picsrc;
    }

    private static function GetPropPicSrc($arElement, $prop_code, $getCount = false)
    {
        if (!is_array($arElement) || !$prop_code)
            return false;
        if (!CModule::IncludeModule("iblock"))
            return false;
        $arPropFile = false;

        if (is_array($arElement['PROPERTIES'][$prop_code])) {
            $arPropFile = $arElement['PROPERTIES'][$prop_code]['VALUE'];
        } else {
            if (!empty($arElement['PRODUCT_ID']))
                $arElement['ID'] = $arElement['PRODUCT_ID'];

            if (empty($arElement['IBLOCK_ID'])) {
                $arElement['IBLOCK_ID'] = self::getElementIblockId($arElement['ID']);
            }

            $dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC", "VALUE_ID" => "ASC"), Array("CODE" => $prop_code));
            if ($arProp = $dbProp->Fetch()) {
                $arPropFile = $arProp['VALUE'];
            }
        }
        if ($arPropFile) {
            if ($getCount)
                return count($arPropFile);
            if (is_array($arPropFile)) {
                return $arPropFile[0];
            } elseif (intval($arPropFile) > 0) {
                return $arPropFile;
            }


            /* if(intval($pic_id) > 0)
            {
                return CFile::GetPath(intval($pic_id)) ;
            } */
        }
        return false;
    }

    private function GetFieldPicSrc($arElementPicField)
    {

        if (is_array($arElementPicField)) {
            return $arElementPicField['ID'];
        } elseif (intval($arElementPicField) > 0) {
            return intval($arElementPicField); //CFile::GetPath(intval($arElementPicField)) ;
        }

        return false;
    }

    public static function getElementIblockId($element_id)
    {
        if (intval($element_id) <= 0)
            return false;


        $obCache = new CPHPCache();
        if ($obCache->InitCache(self::$_cacheTime, "IBID_{$element_id}", self::$_cacheDir)) {
            $arElement = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            $res = CIBlockElement::GetByID($element_id);
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }
            if ($ar_res = $res->GetNext()) {
                $arElement = $ar_res;
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();
            }
            $obCache->EndDataCache($arElement);
        }

        return $arElement['IBLOCK_ID'];
    }

    public static function getCurrencyArray($currencyId = false)
    {
        if (!CModule::includeModule('currency'))
            return false;

        $obCache = new CPHPCache();
        if ($obCache->InitCache(self::$_cacheTime, "CURRENCY_TABLE", self::$_cacheDir)) {
            $arReturn = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }


            $arReturn = array();
            $currencyIterator = BX_CurrencyTable::getList(array(
                'select' => array('CURRENCY')
            ));
            while ($currency = $currencyIterator->fetch()) {
                $currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
                $arReturn[] = array(
                    'CURRENCY' => $currency['CURRENCY'],
                    'FORMAT' => array(
                        'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
                        'DEC_POINT' => $currencyFormat['DEC_POINT'],
                        'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
                        'DECIMALS' => $currencyFormat['DECIMALS'],
                        'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
                        'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
                    )
                );
            }
            unset($currencyFormat, $currency, $currencyIterator);

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("currency");
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arReturn);
        }
        if ($currencyId !== false) {
            foreach ($arReturn as $arItem) {
                if ($arItem['CURRENCY'] == $currencyId) {
                    $arReturn = array($arItem);
                }
            }
        }
        return $arReturn;
    }

    public static function getCurrencyTemplate($currency = false)
    {
        if (!$currency) {
            return false;
        }

        $strReturn = '';

        if ($currency == 'RUB') {
            $strReturn = '<span class="b-rub">' . GetMessage('BITRONIC2_RUB_CHAR') . '</span>';
        }

        if (!$strReturn) {
            $arCurrencies = self::getCurrencyArray();
            foreach ($arCurrencies as $arCurrency) {
                if ($currency == $arCurrency['CURRENCY']) {
                    $strReturn = trim(str_replace('#', '', $arCurrency['FORMAT']['FORMAT_STRING']));
                    break;
                }
            }
        }

        return $strReturn;
    }

    public static function getDetailPropShowList($iblockId = false, $arSettingsHide = array())
    {
        if (intval($iblockId) <= 0) {
            return false;
        }
        if (!is_array($arSettingsHide)) {
            $arSettingsHide = array();
        }
        global $rz_b2_options;

        $arSettingsHide = array_merge(self::$_systemProps, $arSettingsHide);

        $obCache = new CPHPCache;
        $cache_id = 'DPSL_' . $iblockId . serialize($arSettingsHide);
        $cacheDir = self::$_cacheDir . '/props';
        $arReturn = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, $cacheDir)) {
            $arReturn = $obCache->GetVars();

            if (count(array_intersect($arReturn, $arSettingsHide)) > 0) {
                $obCache->Clean($cache_id, $cacheDir);
                $arReturn = array();
            }
        }

        if ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache($cacheDir);
            }

            if (CModule::IncludeModule('yenisite.market')) {
                $arSettingsHide[] = 'MARKET_QUANTITY';
                $rsPrices = CMarketPrice::GetList();
                while ($arPrice = $rsPrices->Fetch()) {
                    $arSettingsHide[] = $arPrice['code'];
                }
            }

            $dbProps = CIBlockProperty::GetList(array('sort' => 'asc'), array("IBLOCK_ID" => $iblockId, 'ACTIVE' => 'Y'));
            while ($arProp = $dbProps->Fetch()) {
                if (in_array($arProp["CODE"], $arSettingsHide))
                    continue;

                $find = false;
                foreach (self::$_systemPropsMask as $mask) {
                    if (strpos($arProp['CODE'], $mask) !== false) {
                        $find = true;
                        break;
                    }
                }
                if ($find)
                    continue;

                $arReturn[] = $arProp['CODE'];
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $iblockId);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arReturn);
        }

        unset($obCache);

        return $arReturn;
    }

    public static function getComponentCachePath($component)
    {
        $cacheThief = new ReflectionProperty('CBitrixComponent', '__cacheID');
        $cacheThief->setAccessible(true);
        $bxPath = '/' . trim(Bitrix\Main\Application::getPersonalRoot(), '/\\');
        $compPath = trim($component->getCachePath(), '/\\');
        $arCache = array(
            'path' => $bxPath . '/cache/' . $compPath,
            'pathJS' => $bxPath . '/cache/js/' . $compPath,
            'id' => ltrim(Cache::getPath($cacheThief->getValue($component)), '/\\')
        );
        $arCache['idJS'] = str_replace('.php', '.js', $arCache['id']);

        return $arCache;
    }

    public static function getJSCache($component)
    {
        $arCache = self::getComponentCachePath($component);

        $jsPath = rtrim(Loader::getDocumentRoot(), '/\\') . $arCache['pathJS'] . '/' . $arCache['idJS'];
        CheckDirPath($jsPath);
        $jsFile = @fopen($jsPath, 'c');
        if ($jsFile) {
            ftruncate($jsFile, 0);
            rewind($jsFile);
        }
        $arCache['path'] = $arCache['pathJS'];
        $arCache['path-full'] = $jsPath;
        $arCache['file'] = $jsFile;
        return $arCache;
    }

    public static function SendEvent($eventName, $productId, array $arEventFields = array())
    {
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $rsItem = \CIBlockElement::GetList(array(),
            array(
                'ID' => intval($productId),
            ),
            false,
            false,
            array(
                'ID',
                'IBLOCK_ID',
                'NAME',
                'DETAIL_PAGE_URL'
            )
        );
        $arSend = $rsItem->GetNext();
        unset($rsItem);
        $arEventFields += array(
            'PRODUCT_ID' => $arSend['ID'],
            'PRODUCT_URL' => $arSend['DETAIL_PAGE_URL'],
            'PRODUCT_NAME' => $arSend['NAME'],
            'HTTP' => \CMain::IsHTTPS() ? 'https://' : 'http://',
        );
        $host = $arEventFields['HTTP'] . $_SERVER['HTTP_HOST'];
        $arEventFields['PRODUCT_URL_FULL'] = $host . $arEventFields['PRODUCT_URL'];
        $arEventFields['PRODUCT_URL_ADMIN'] = $host . BX_ROOT . '/admin/' . CIBlock::GetAdminElementEditLink($arSend['IBLOCK_ID'], $arSend['ID']);
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        return \CEvent::Send($eventName, SITE_ID, $arEventFields);
    }

    public static function getAvailableStatus($productId, $originStatus, $arProduct = false)
    {
        global $rz_b2_options;

        if (CRZBitronic2Settings::isPro($geoip = true) && method_exists('Yenisite\Geoipstore\CatalogTools', 'getAvailableStatus')) {
            if (is_array($arProduct) && isset($arProduct['ID'])) {
                $productId = $arProduct;
            }
            $status = Yenisite\Geoipstore\CatalogTools::getAvailableStatus($productId, $rz_b2_options['GEOIP']['STORES']);
        }
        return $status !== NULL ? $status : $originStatus;
    }

    public static function getStoresCount($productId, $originQuantity)
    {
        global $rz_b2_options;

        $quantity = $originQuantity;
        if (CRZBitronic2Settings::isPro($geoip = true) && method_exists('Yenisite\Geoipstore\CatalogTools', 'getStoresAmount')) {
            $arQuantity = Yenisite\Geoipstore\CatalogTools::getStoresAmount($productId, $rz_b2_options['GEOIP']['STORES']);
            if (!empty($arQuantity) && is_array($arQuantity)) {
                $quantity = min(array_sum($arQuantity), $quantity);
            }
        }

        return $quantity;
    }

    public static function getCatchbuyInfo($productId, $arStickersResult = false)
    {
        $arReturn = array();
        do {
            if (0 >= intval($productId)) break;
            if (!Loader::IncludeModule('yenisite.catchbuy')) break;
            if (is_array($arStickersResult) && $arStickersResult['CATCHBUY'] === false) break;

            $rs = \Yenisite\Catchbuy\Catchbuy::getList(
                array(
                    'filter' => array(
                        'ACTIVE' => 'Y',
                        '=PRODUCT_ID' => intval($productId),
                        'LID' => SITE_ID
                    )
                )
            );
            if ($arReturn = $rs->Fetch() ?: $arReturn) {
                $arReturn['PERCENT'] = $arReturn['MAX_USES'] > 0 ? $arReturn['COUNT_USES'] / $arReturn['MAX_USES'] * 100 : 0;
            }
        } while (0);

        return $arReturn;
    }

    public static function getCatchbuyInfoList(&$arProducts)
    {
        if (!Loader::IncludeModule('yenisite.catchbuy')) return;

        $arProductId = array();
        $arLinks = array();
        foreach ($arProducts as $key => $arProduct) {
            if (is_array($arProduct['STICKERS']) && $arProduct['STICKERS']['CATCHBUY'] === false) continue;
            $arProductId[] = $arProduct['ID'];
            $arLinks[$arProduct['ID']] = &$arProducts[$key];
        }
        if (empty($arProductId)) return;

        $rs = \Yenisite\Catchbuy\Catchbuy::getList(
            array(
                'filter' => array(
                    'ACTIVE' => 'Y',
                    '@PRODUCT_ID' => $arProductId,
                    'LID' => SITE_ID
                ),
                'select' => array(
                    'ID', 'PRODUCT_ID', 'DISCOUNT_ID',
                    'LID', 'MAX_USES', 'COUNT_USES',
                    'ACTIVE', 'ACTIVE_FROM', 'ACTIVE_TO'
                )
            )
        );
        while ($arCatchbuy = $rs->Fetch()) {
            $arCatchbuy['PERCENT'] = $arCatchbuy['MAX_USES'] > 0 ? $arCatchbuy['COUNT_USES'] / $arCatchbuy['MAX_USES'] * 100 : 0;
            $arLinks[$arCatchbuy['PRODUCT_ID']]['CATCHBUY'] = $arCatchbuy;
            unset($arLinks[$arCatchbuy['PRODUCT_ID']]['CATCHBUY']['PRODUCT_ID']);
        }
    }

    public static function processItemCommon($arItem)
    {
        $arItem['MIN_PRICE'] = self::findMinPrice($arItem);
        $arItem['CATALOG_QUANTITY'] = self::getStoresCount($arItem['ID'], $arItem['CATALOG_QUANTITY']);
        $arItem['CAN_BUY'] = self::getAvailableStatus($arItem['ID'], $arItem['CAN_BUY'], $arItem);
        $arItem['NAME'] = isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
            : $arItem["NAME"];

        foreach (GetModuleEvents(CRZBitronic2Settings::getModuleId(), "OnAfterProcessItemCommon", true) as $arEvent)
            ExecuteModuleEventEx($arEvent, array(&$arItem));

        if (!empty($arItem['OFFERS']) && is_array($arItem['OFFERS'])) {
            foreach ($arItem['OFFERS'] as &$arOffer) {
                $arOffer = self::processItemCommon($arOffer);
            }
            unset($arOffer);
        }
        return $arItem;
    }

    public static function findMinPrice(&$arItem)
    {
        do {
            if ($arItem['MIN_PRICE']['CAN_BUY'] === 'Y') break;
            if (count($arItem['PRICES']) < 2) break;

            $oldCode = false;
            $newCode = false;
            $arMinPrice = array('DISCOUNT_VALUE' => INF);
            foreach ($arItem['PRICES'] as $code => $arPrice) {
                if ($arPrice['MIN_PRICE'] === 'Y') {
                    $oldCode = $code;
                    continue;
                }
                if ($arPrice['CAN_BUY'] !== 'Y') {
                    continue;
                }
                if ($arPrice['DISCOUNT_VALUE'] <= 0) {
                    continue;
                }
                if ($arPrice['DISCOUNT_VALUE'] > $arMinPrice['DISCOUNT_VALUE']) {
                    continue;
                }

                $newCode = $code;
                $arMinPrice = $arPrice;
            }
            if ($newCode) {
                $arItem['PRICES'][$oldCode]['MIN_PRICE'] = 'N';
                $arItem['PRICES'][$newCode]['MIN_PRICE'] = 'Y';

                return $arMinPrice;
            }
        } while (0);

        return $arItem['MIN_PRICE'];
    }

    /**
     * Iterate through item offers to find minimum price and availability status
     *
     * This method iterates all item offers including not available ones to find most minimum price.
     * If every offer is not available then change MIN_PRICE to found one,
     * otherwise it uses default CIBlockPriceTools::getMinPriceFromOffers().
     * In addition this method redefine CAN_BUY for main item and sets ON_REQUEST and FOR_ORDER (if required).
     *
     * @param array $arItem - CIBlockElement result array + offers
     * @param string $currency - result currency identifier
     * @param bool $bForOrder (optional) - also get status FOR_ORDER from offers, true by default
     */
    public static function fillMinPriceFromOffers(&$arItem, $currency, $bForOrder = true)
    {
        $can_buy_find = false;
        $minNotAvailPrice = false;
        $arItem['bOffers'] = true;
        $arItem['bOffersNotEqual'] = false;
        $arItem['CAN_BUY'] = false;
        $arItem['ON_REQUEST'] = false;

        foreach ($arItem['OFFERS'] as &$arOffer) {
            if (!empty($arOffer['MIN_PRICE'])) {
                if (
                    !empty($minNotAvailPrice) &&
                    0 < $arOffer['MIN_PRICE']['VALUE'] &&
                    $minNotAvailPrice['DISCOUNT_VALUE'] != $arOffer['MIN_PRICE']['DISCOUNT_VALUE']
                ) {
                    $arItem['bOffersNotEqual'] = true;
                }
                $minNotAvailPrice = (
                $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] < $minNotAvailPrice['DISCOUNT_VALUE'] || !$minNotAvailPrice
                    ? $arOffer['MIN_PRICE']
                    : $minNotAvailPrice
                );
            }
            $arOffer['ON_REQUEST'] = (empty($arOffer['MIN_PRICE']) || $arOffer['MIN_PRICE']['VALUE'] <= 0);
            if ($arOffer['ON_REQUEST']) {
                $arOffer['CAN_BUY'] = false;
                if (!$arItem['CAN_BUY']) {
                    $arItem['ON_REQUEST'] = $arOffer['ON_REQUEST'];
                }
            }
            if (!$can_buy_find && $arOffer['CAN_BUY']) {
                $arItem['CAN_BUY'] = $arOffer['CAN_BUY'];
                if ($bForOrder) {
                    if ($arOffer['CATALOG_QUANTITY'] > 0 || $arOffer['CATALOG_QUANTITY_TRACE'] == 'N') {
                        $arItem['FOR_ORDER'] = false;
                        $can_buy_find = true;
                    } else {
                        $arItem['FOR_ORDER'] = true;
                    }
                } else {
                    $can_buy_find = true;
                }
            }
        }
        unset($arOffer);

        if ($arItem['CAN_BUY']) {
            $arItem['MIN_PRICE'] = \CIBlockPriceTools::getMinPriceFromOffers($arItem['OFFERS'], $currency, false);
            $arItem['ON_REQUEST'] = false;
            $arItem['bOffersNotEqual'] = false;
            foreach ($arItem['OFFERS'] as &$arOffer) {
                if (
                    $arOffer['CAN_BUY'] &&
                    !empty($arOffer['MIN_PRICE']) &&
                    0 < $arOffer['MIN_PRICE']['VALUE'] &&
                    $arItem['MIN_PRICE']['DISCOUNT_VALUE'] != $arOffer['MIN_PRICE']['DISCOUNT_VALUE']
                ) {
                    $arItem['bOffersNotEqual'] = true;
                    break;
                }
            }
            unset($arOffer);
        } else {
            $arItem['MIN_PRICE'] = $minNotAvailPrice;
        }
    }

    public static function fillSKUMultiPrice(&$arItem, &$arPrices)
    {
        $arMinPrices = array();
        foreach ($arPrices as $priceCode => $arPrice) {
            $arMinPrices[$priceCode]['DISCOUNT_VALUE'] = INF;
        }
        foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
            foreach ($arOffer['PRICES'] as $priceCode => $arPrice) {
                if ($arPrice['DISCOUNT_VALUE'] <= 0) continue;
                if ($arMinPrices[$priceCode]['DISCOUNT_VALUE'] > $arPrice['DISCOUNT_VALUE']) {
                    $arMinPrices[$priceCode]['DISCOUNT_VALUE'] = $arPrice['DISCOUNT_VALUE'];
                    $arMinPrices[$priceCode]['CURRENCY'] = $arPrice['CURRENCY'];
                }
            }
        }
        if (!empty($arMinPrices)) {
            foreach ($arMinPrices as $priceCode => $arMinPrice) {
                $arPrice = &$arItem['PRICES'][$priceCode];
                $arPrice['DISCOUNT_VALUE'] = $arMinPrice['DISCOUNT_VALUE'] == INF ? 0 : $arMinPrice['DISCOUNT_VALUE'];
                $arPrice['CURRENCY'] = $arMinPrice['CURRENCY'];
                $arPrice['PRINT_DISCOUNT_VALUE'] = \CRZBitronic2CatalogUtils::getElementPriceFormat($arPrice['CURRENCY'], $arPrices['DISCOUNT_VALUE']);
                if (empty($arPrice['PRICE_ID'])) $arPrice['PRICE_ID'] = $arPrices[$priceCode]['ID'];
            }
            unset($arPrice, $arMinPrices);
        }
    }

    public static function getPriceMatrix($productId, $priceId, $arCurrencyParams)
    {
        $arMatrix = CatalogGetPriceTableEx($productId, 0, array($priceId), 'Y', $arCurrencyParams);
        if (is_array($arMatrix) && is_array($arMatrix['MATRIX'])) {
            foreach ($arMatrix['MATRIX'] as &$arCol) {
                foreach ($arCol as &$arRow) {
                    $arCopy = $arRow;
                    $arCopy['VALUE'] = $arCopy['PRICE'];
                    $arCopy['DISCOUNT_VALUE'] = $arCopy['DISCOUNT_PRICE'];
                    unset($arCopy['PRICE'], $arCopy['DISCOUNT_PRICE']);
                    $arRow['BONUS_PRICE'] = base64_encode(serialize(array($productId => $arCopy)));
                    $arRow['HTML_PRICE'] = self::getElementPriceFormat($arRow['CURRENCY'], $arRow['PRICE'], $arRow['PRICE']);
                    $arRow['HTML_DISCOUNT_PRICE'] = self::getElementPriceFormat($arRow['CURRENCY'], $arRow['DISCOUNT_PRICE'], $arRow['DISCOUNT_PRICE']);
                    $arRow['DISCOUNT_DIFF'] = $arRow['PRICE'] - $arRow['DISCOUNT_PRICE'];
                    $arRow['DISCOUNT_DIFF_PERCENT'] = $arRow['PRICE'] ? $arRow['DISCOUNT_DIFF'] / $arRow['PRICE'] : 0;
                }
            }
            unset($arCol, $arRow);
        }
        return $arMatrix;
    }

    public static function catalogSetConstruction(&$arItem) //for assumption
    {
        if (empty($arItem['MIN_PRICE'])) return;
        if ($arItem['PRICE_DISCOUNT_VALUE'] == $arItem['MIN_PRICE']['DISCOUNT_VALUE']
            && $arItem['PRICE_CURRENCY'] == $arItem['MIN_PRICE']['CURRENCY']
        ) {
            return;
        }
        $arItem['PRICE_CURRENCY'] = $arItem['MIN_PRICE']['CURRENCY'];
        $arItem['PRICE_DISCOUNT_VALUE'] = $arItem['MIN_PRICE']['DISCOUNT_VALUE'];
        $arItem['PRICE_PRINT_DISCOUNT_VALUE'] = $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
        $arItem['PRICE_VALUE'] = $arItem['MIN_PRICE']['VALUE'];
        $arItem['PRICE_PRINT_VALUE'] = $arItem['MIN_PRICE']['PRINT_VALUE'];
        $arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'] = $arItem['MIN_PRICE']['DISCOUNT_DIFF'];
        $arItem['PRICE_DISCOUNT_DIFFERENCE'] = $arItem['MIN_PRICE']['PRINT_DISCOUNT_DIFF'];
        $arItem['PRICE_DISCOUNT_PERCENT'] = $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
    }

    /**
     * Get count of reviews for given catalog item
     *
     * @param array $arItem - CIBlockElement array
     * @param array $arParams - bitrix:catalog parameters
     *
     * @return int
     */
    public static function getItemReviewCount(&$arItem, &$arParams)
    {
        switch ($arParams['REVIEWS_MODE']) {
            case 'blog':
                return intval($arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']);
            case 'forum':
                return intval($arItem['PROPERTIES']['FORUM_MESSAGE_CNT']['VALUE']);
            case 'feedback':
                $arFilter = array('IBLOCK_ID' => $arParams['FEEDBACK_IBLOCK_ID'], 'PROPERTY_ELEMENT_ID' => $arItem['ID']);
                return CIBlockElement::GetList(array(), $arFilter, array());
            default:
                return 0;
        }
    }

    /**
     * Get folder with component
     *
     * @param string $name - component/catalog folder
     *
     * @return string
     */
    protected static function getComponentFolder($name)
    {
        $siteDir = SITE_DIR;
        if (empty($siteDir)) {
            $siteDir = '/';
        }
        return COption::GetOptionString(CRZBitronic2Settings::getModuleId(), $name . '_folder', $siteDir . $name . '/', SITE_ID);
    }

    /**
     * Get folder with component bitrix:catalog
     *
     * @return string
     */
    public static function getCatalogFolder()
    {
        return self::getComponentFolder('catalog');
    }

    /**
     * Get URL to update cache with bitrix:catalog parameters
     *
     * @return string
     */
    public static function getCatalogPathForUpdate()
    {
        $catalogFolder = self::getCatalogFolder();
        $paramName = 'rz_update_catalog_parameters_cache';

        return "{$catalogFolder}?{$paramName}=y";
    }

    /**
     * Get URL to update cache with yenisite:highloadblock parameters
     *
     * @return string
     */
    public static function getBrandPathForUpdate()
    {
        $brandFolder = self::getComponentFolder('brands');
        $paramName = 'rz_update_brands_parameters_cache';

        return "{$brandFolder}?{$paramName}=y";
    }


    public static function getElementsListByDiscount($arDiscounts = array(), $idAction = '', $arParams)
    {
        if (empty($arDiscounts) || empty($idAction)) return;

        $arFilter = array('SITE_ID' => SITE_ID, 'ACTIVE' => 'Y');
        $arResult = array();
        $arIdDiscounts = array();
        $arDiscountsData = array();
        $arIdActions = array();
        global $CACHE_MANAGER;

        foreach ($arDiscounts as $discount) {
            $arFilter['ID'][] = $discount;
        }

        if (Loader::IncludeModule('catalog')) {

            $obCache = new CPHPCache;
            $cache_id = 'DISCOUNTS_' . $idAction . implode("_", $arFilter);

            if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
                $arDiscountsData = $obCache->GetVars();
            } elseif ($obCache->StartDataCache()) {
                if (defined("BX_COMP_MANAGED_CACHE")) {
                    global $CACHE_MANAGER;
                    $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
                }

                $rsElement = CCatalogDiscount::GetList(array(), $arFilter, false, false, array());

                while ($arElement = $rsElement->Fetch()) {
                    if (!in_array($arElement['ID'], $arIdDiscounts)) {
                        $arIdDiscounts[] = $arElement['ID'];
                    }
                    $arDiscountsData[] = $arElement;
                }

                unset ($arElement, $rsElement);

                if (defined("BX_COMP_MANAGED_CACHE")) {
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }

                $obCache->EndDataCache($arDiscountsData);
            }
            unset($obCache);
        }


        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
        $arFilter['?PROPERTY_' . $arParams['PROP_FOR_DISCOUNT']] = implode('|', $arIdDiscounts);

        if (Loader::includeModule('iblock')) {

            $obCache = new CPHPCache;
            $cache_id = 'elements_' . $idAction . implode("_", $arFilter);

            if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
                $arIdActions = $obCache->GetVars();
            } elseif ($obCache->StartDataCache()) {
                if (defined("BX_COMP_MANAGED_CACHE")) {
                    global $CACHE_MANAGER;
                    $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
                }

                $rsAllActionHasDiscount = CIBlockElement::GetList(array(), $arFilter, false, false, array());

                while ($arActionsData = $rsAllActionHasDiscount->GetNextElement()) {
                    $arProps = $arActionsData->getProperties();
                    $arFields = $arActionsData->getFields();
                    if (!is_array($arProps[$arParams['PROP_FOR_DISCOUNT']]['VALUE'])) $arProps[$arParams['PROP_FOR_DISCOUNT']]['VALUE'] = array($arProps[$arParams['PROP_FOR_DISCOUNT']]['VALUE']);

                    foreach ($arProps[$arParams['PROP_FOR_DISCOUNT']]['VALUE'] as $arProp) {
                        $arIdActions[$arProp][] = $arFields['ID'];
                    }
                }

                if (defined("BX_COMP_MANAGED_CACHE")) {
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }

                $obCache->EndDataCache($arIdActions);
            }
            unset($obCache);
        }
        unset($arActionsData, $rsAllActionHasDiscount);

        foreach ($arDiscountsData as $arElement) {
            if (!empty($arElement['IBLOCK_ID']) && !in_array($arElement['IBLOCK_ID'], $arResult['IBLOCK_ID'])) {
                $arResult['IBLOCK_ID'][$arElement['IBLOCK_ID']] = $arIdActions[$arElement['ID']];
                if (!empty($arElement['UNPACK'])) {
                    $arResult['UNPACK_ITEM'][] = htmlspecialchars_decode($arElement['UNPACK']);
                }
            } elseif (!empty($arElement['SECTION_ID']) && !in_array($arElement['SECTION_ID'], $arResult['SECTION_ID'])) {
                $arResult['SECTION_ID'][$arElement['SECTION_ID']] = $arIdActions[$arElement['ID']];
                if (!empty($arElement['UNPACK'])) {
                    $arResult['UNPACK_ITEM'][] = htmlspecialchars_decode($arElement['UNPACK']);
                }
            } elseif (!empty($arElement['PRODUCT_ID']) && !in_array($arElement['PRODUCT_ID'], $arResult['PRODUCT_ID'])) {
                $arResult['PRODUCT_ID'][$arElement['PRODUCT_ID']] = $arIdActions[$arElement['ID']];
                if (!empty($arElement['UNPACK'])) {
                    $arResult['UNPACK_ITEM'][] = htmlspecialchars_decode($arElement['UNPACK']);
                }
            } elseif (!empty($arElement['UNPACK'])) {
                //$arResult['IBLOCK_ID'][$arElement['IBLOCK_ID']] = $arIdActions[$arElement['ID']];
                $arResult['UNPACK_ITEM'][] = htmlspecialchars_decode($arElement['UNPACK']);
            }
        }

        return $arResult;
    }

    public static function getActionsOfElement($idElement = '', $idSection = '', $idIblock = '', $resizerBanner = '4', $arParams = array())
    {

        global $USER;

        $discounts = \CCatalogDiscount::GetDiscount(
            $idElement,
            $idIblock,
            array(),
            $USER->GetGroups(),
            'N',
            SITE_ID,
            array()
        );
        if (empty($discounts)) return;

        $arActionData = array();
        $arDiscountsID = array();
        $arFilter = array('IBLOCK' => $arParams['IBLOCK_ACTIONS_ID'],'ACTIVE' => 'Y');
        $arResult = array();
        $arSelect = array('NAME', 'IBLOCK', 'ID', 'PROPERTY_*', 'DETAIL_PAGE_URL', 'DETAIL_TEXT', 'PREVIEW_TEXT');

        $arParams['IBLOCK_ACTIONS_ID'] = $arParams['IBLOCK_ACTIONS_ID'] ?: self::getIblockbyType('bitronic2_actions');
        $arParams['PROP_FOR_BANNER'] = $arParams['PROP_FOR_BANNER'] ?: 'BANNER_IMG';
        $arParams['PROP_FOR_DISCOUNT'] = $arParams['PROP_FOR_DISCOUNT'] ?: 'DISCOUNTS';

        foreach ($discounts as $discount) {
            $arFilter['?PROPERTY_' . $arParams['PROP_FOR_DISCOUNT']] = $discount['ID'];
            $arDiscountsID[] = $discount['ID'];
        }

        $obCache = new CPHPCache();
        $cache_id = 'ELEMENT_ACTIONS_' . $idElement . $idSection . $idIblock . CRZBitronic2Settings::getModuleId();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arActionData = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $rsActionData = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

            if (!empty($rsActionData)) {
                while ($arAction = $rsActionData->GetNextElement()) {
                    $arFields = $arAction->GetFields();
                    $arProps['PROPERTIES'] = $arAction->GetProperties();
                    $hasDiscount = array_uintersect($arProps['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]['VALUE'], $arDiscountsID, "strcasecmp");
                    if (count($hasDiscount) > 0) {
                        $arActionData[] = array_merge($arFields, $arProps);
                    }
                }
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams['IBLOCK_ACTIONS_ID']);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arActionData);
        }
        unset($obCache);

        foreach ($arActionData as $arAction) {
            $bannerProp = $arAction['PARAMS']['PROP_FOR_BANNER'] ?: 'BANNER_IMG';
            if (!empty($arAction['PROPERTIES'][$bannerProp]['VALUE'])) {
                $imgSrc = CFile::GetPath($arAction['PROPERTIES'][$bannerProp]['VALUE']);
                $arResult[$arAction['ID']]['IMG'] = CResizer2Resize::ResizeGD2($imgSrc, $resizerBanner);
            }
            $arResult[$arAction['ID']]['SRC'] = $arAction['DETAIL_PAGE_URL'];
            $arResult[$arAction['ID']]['NAME'] = $arAction['NAME'];
            $arResult[$arAction['ID']]['DETAIL_TEXT'] = $arAction['DETAIL_TEXT'];
            $arResult[$arAction['ID']]['PREVIEW_TEXT'] = $arAction['PREVIEW_TEXT'];
        }

        return $arResult;
    }

    private static function getAllActionsByData($idElement = '', $idSection = '', $idIblock = '', $iblockIdCache)
    {
        $arFilter = array('SITE_ID' => SITE_ID, 'ACTIVE' => 'Y');
        $arIdAction = array();

        $obCache = new CPHPCache();
        $cache_id = 'ALL_DISCOUNTS_' . $idElement . $idSection . $idIblock . CRZBitronic2Settings::getModuleId();;

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arIdAction = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            if (!empty($idElement)) {
                $arFilter['PRODUCT_ID'] = $idElement;
                $rsActions = CCatalogDiscount::GetList(array(), $arFilter, false, false, array());
            }
            if (!empty($idSection) && $rsActions->result->num_rows <= 0) {
                $arFilter['SECTION_ID'] = $idSection;
                $arFilter['PRODUCT_ID'] = '';
                $rsActions = CCatalogDiscount::GetList(array(), $arFilter, false, false, array());
            }
            if (!empty($idIblock) && $rsActions->result->num_rows <= 0) {
                $arFilter['IBLOCK_ID'] = $idIblock;
                $arFilter['PRODUCT_ID'] = '';
                $arFilter['SECTION_ID'] = '';
                $rsActions = CCatalogDiscount::GetList(array(), $arFilter, false, false, array());
            }

            while ($arActions = $rsActions->Fetch()) {
                if (!empty($arActions['PRODUCT_ID']) && !in_array($arActions['ID'], $arIdAction['PRODUCT_ID'])) {
                    $arIdAction['PRODUCT_ID'][$arActions['ID']] = $arActions['ID'];
                }
                if (!empty($arActions['IBLOCK_ID']) && !in_array($arActions['ID'], $arIdAction['IBLOCK_ID'])) {
                    $arIdAction['IBLOCK_ID'][$arActions['ID']] = $arActions['ID'];
                }
                if (!empty($arActions['SECTION_ID']) && !in_array($arActions['ID'], $arIdAction['SECTION_ID'])) {
                    $arIdAction['SECTION_ID'][$arActions['ID']] = $arActions['ID'];
                }
            }

            unset ($rsActions, $arActions, $arFilter);

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $iblockIdCache);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arIdAction);
        }
        unset($obCache);

        return $arIdAction;
    }

    private static function queryToDiscount($arFilter = array())
    {
        $rsElements = \CCatalogDiscount::GetList(array(), $arFilter, false, false, array());
        $arResult = array();

        while ($arElement = $rsElements->Fetch()) {
            $arResult[$arElement['ID']] = $arElement['ID'];
        }

        unset ($rsElements, $arElement);
        return $arResult;
    }

    public static function getIblockbyType($type = 'catalog')
    {
        $cacheId = 'iblock_id_for_'.$type;
        $arIblock = '';
        $cacheOb = new CPHPCache();

        if ($cacheOb->InitCache(self::$_cacheTime, $cacheId, self::$_cacheDir)) {
            $arIblock = $cacheOb->GetVars();
        } elseif ($cacheOb->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $iblockFilter = (array('TYPE' => $type, 'ACTIVE' => 'Y'));
            $rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
            $arIblock = $rsIBlock->Fetch();

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arIblock['ID']);
                $CACHE_MANAGER->EndTagCache();
            }

            $cacheOb->EndDataCache($arIblock['ID']);
        }
        unset($cacheOb);

        return $arIblock;
    }

    public static function setFilterAvPrFoto(&$filterName, $arParams)
    {
        if ($arParams['HIDE_ITEMS_NOT_AVAILABLE']) {
            if (!empty($arParams['VALUE_RZ_AVAILABLE'])) {
                if (CRZBitronic2Settings::isPro($withGeoip = true)) {
                    global $rz_b2_options, $APPLICATION;
                    if(!isset($rz_b2_options['GEOIP'])) {
                        $arRes = $APPLICATION->IncludeComponent('yenisite:geoip.store', 'empty');
                        $rz_b2_options['GEOIP'] = $arRes;
                    }
                    $filterName['!=PROPERTY_RZ_AVAILABLE_'.$rz_b2_options['GEOIP']['ITEM']['ID'].'_VALUE'] = $arParams['VALUE_RZ_AVAILABLE'];
                } else {
                    $filterName['!=PROPERTY_RZ_AVAILABLE_VALUE'] = $arParams['VALUE_RZ_AVAILABLE'];
                }
            } else {
                $filterName['=CATALOG_AVAILABLE'] = 'Y';
            }
            $arParams['HIDE_NOT_AVAILABLE'] = 'Y';
            //$filterName['=CATALOG_AVAILABLE'] = 'Y';
        }
        if (CRZBitronic2Settings::getEdition() !== 'LITE') {
            $arIblockOffersData = self::getIblockData($arParams);
            $arPropertyData = self::getPropertyData($arIblockOffersData['OFFERS_PROPERTY_ID'], $arIblockOffersData['OFFERS_IBLOCK_ID']);
        }
        if ($arParams['HIDE_ITEMS_ZER_PRICE']) {
            if (CRZBitronic2Settings::isPro()) {
                if (!empty($arParams['PRICE_CODE'])) {
                    $arfilter = array();
                    $arPrices = self::getPrices($arParams);
                    foreach ($arParams['PRICE_CODE'] as $priceCode) {
                        $arfilter ['>CATALOG_PRICE_' . $arPrices[$priceCode]['ID']] = 0;
                        if (!empty($arIblockOffersData)) {
                            $arfilter["=ID"] = CIBlockElement::SubQuery('PROPERTY_' . $arPropertyData['CODE'],
                                array("IBLOCK_ID" => $arIblockOffersData['OFFERS_IBLOCK_ID'], '>CATALOG_PRICE_' . $arPrices[$priceCode]['ID'] => 0));
                        }
                    }
                    $filterName[] = array_merge(array('LOGIC' => 'OR'), $arfilter);
                }
            } else {
                if (is_array($arParams['LIST_PRICE_SORT'])) {
                    foreach ($arParams['LIST_PRICE_SORT'] as $priceCode) {
                        $arfilter['>' . $priceCode] = 0;
                        if (!empty($arIblockOffersData)) {
                            $arfilter["=ID"] = CIBlockElement::SubQuery('PROPERTY_' . $arPropertyData['CODE'],
                                array("IBLOCK_ID" => $arIblockOffersData['OFFERS_IBLOCK_ID'], '>' . $priceCode => 0));
                        }
                    }
                } else {
                    $arfilter['>' . $arParams['LIST_PRICE_SORT']] = 0;
                    if (!empty($arIblockOffersData)) {
                        $arfilter["=ID"] = CIBlockElement::SubQuery('PROPERTY_' . $arPropertyData['CODE'],
                            array("IBLOCK_ID" => $arIblockOffersData['OFFERS_IBLOCK_ID'], '>' . $arParams['LIST_PRICE_SORT'] => 0));
                    }
                }
                $filterName[] = array_merge(array('LOGIC' => 'OR'), $arfilter);
            }
        }
        if ($arParams['HIDE_ITEMS_WITHOUT_IMG']) {
            $filterName[] = array(
                    'LOGIC' => 'OR',
                    '!PREVIEW_PICTURE' => false,
                    '!PROPERTY_MORE_PHOTO' => false,
                    '!DETAIL_PICTURE' => false,
                    '=ID' => CIBlockElement::SubQuery('PROPERTY_' . $arPropertyData['CODE'], array(
                        'IBLOCK_ID' => $arIblockOffersData['OFFERS_IBLOCK_ID'],
                        'LOGIC' => 'OR',
                        '!PREVIEW_PICTURE' => false,
                        '!PROPERTY_MORE_PHOTO' => false,
                        '!DETAIL_PICTURE' => false,
                    )
                    ),
            );
        }

    }

    public static function getPropertyData($idProperty, $idIblock)
    {
        $obCache = new CPHPCache;
        $cache_id = 'property_data_' . $idProperty . $idIblock;
        $arPropertyData = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arPropertyData = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $rsPropertyData = CIBlockProperty::GetByID($idProperty, $idIblock);

            if ($arPropData = $rsPropertyData->GetNext()) {
                $arPropertyData = $arPropData;
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $idIblock);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arPropertyData);
        }
        unset($obCache);

        return $arPropertyData;
    }

    public static function getIblockData($arParams)
    {
        $obCache = new CPHPCache;
        $cache_id = 'oblock_data_' . $arParams['IBLOCK_ID'];
        $arIblockData = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arIblockData = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $arIblockData = CCatalog::GetByID($arParams['IBLOCK_ID']);

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arIblockData);
        }
        unset($obCache);

        return $arIblockData;
    }

    public static function getPrices($arParams)
    {
        $obCache = new CPHPCache;
        $cache_id = 'prices_list';
        $arPrices = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arPrices = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $rsPrice = CCatalogGroup::GetList(array(), array(), false, false, array());
            while ($arPrice = $rsPrice->Fetch()) {
                $arPrices[$arPrice['NAME']] = $arPrice;
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arPrices);
        }
        unset($obCache);

        return $arPrices;
    }

    public static function checkAvPrFotoForElement($arItem, $arParams)
    {
        if ($arParams['HIDE_ITEMS_NOT_AVAILABLE']) {
            if (!$arItem['CAN_BUY'] && !$arItem['ON_REQUEST'] || !isset($arItem['CAN_BUY'])) {
                return true;
            }
        }
        if ($arParams['HIDE_ITEMS_ZER_PRICE']) {
            if (CRZBitronic2Settings::isPro()) {
                $bzero = true;
                if (!empty($arItem['PRICES'])) {
                    foreach ($arItem['PRICES'] as $priceCode => $arPrice) {
                        if (!empty($arPrice['DISCOUNT_VALUE']) && $arPrice['DISCOUNT_VALUE'] > 0) {
                            $bzero = false;
                        }
                    }
                    if ($bzero) {
                        return true;
                    }
                }
            } elseif (isset($arItem['MIN_PRICE']) && !empty($arItem['MIN_PRICE']) && $arItem['MIN_PRICE']['VALUE'] <= 0) {
                return true;
            } elseif (isset($arItem['PRICE_VALUE']) && $arItem['PRICE_VALUE'] <= 0) {
                return true;
            }
        }
        if ($arParams['HIDE_ITEMS_WITHOUT_IMG']) {
            $arrImg = self::getElementPictureArray($arItem);
            if (!$arrImg[0]) {
                return true;
            }
        }
        return false;
    }

    public static function prepareCurrencyOfOrder(&$arResult)
    {
        global $rz_b2_options;
        if (empty($rz_b2_options)){
            global $APPLICATION;
            include $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "ajax/include_options.php";
        }
        if ($rz_b2_options['convert_currency']) {
            $arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
        }

        $arParams['CURRENCY_ID'] = $arParams['CURRENCY_ID'] ? $arParams['CURRENCY_ID'] : $arResult['BASE_LANG_CURRENCY'];

        foreach ($arResult['JS_DATA']['GRID']['ROWS'] as &$arRow) {
            $currency = $arRow['data']['CURRENCY'];
            $arRow['data']['SUM_BASE_FORMATED'] = $arRow['data']['SUM_BASE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arRow['data']['BASE_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arRow['data']['SUM_BASE_FORMATED'];
            $arRow['data']['PRICE_FORMATED'] = $arRow['data']['PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arRow['data']['PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arRow['data']['PRICE_FORMATED'];
            $arRow['data']['SUM'] = $arRow['data']['SUM'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arRow['data']['SUM_NUM'], $currency, $arParams['CURRENCY_ID'])) : $arRow['data']['SUM'];
        }

        foreach ($arResult['JS_DATA']['DELIVERY'] as &$arDelivery) {
            $currency = $arDelivery['CURRENCY'];
            $arDelivery['PRICE_FORMATED'] = $arDelivery['PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency,  CCurrencyRates::ConvertCurrency($arDelivery['PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arDelivery['PRICE_FORMATED'];
        }


        foreach ($arResult['BASKET_ITEMS'] as &$arItem) {
            $currency = $arItem['CURRENCY'];
            $arItem['PRICE_FORMATED'] = $arItem['PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arItem['PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arItem['PRICE_FORMATED'];
            $arItem['BASE_PRICE_FORMATED'] = $arItem['BASE_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arItem['BASE_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arItem['BASE_PRICE_FORMATED'];
            $arItem['SUM'] = $arItem['SUM'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arItem['SUM_NUM'], $currency, $arParams['CURRENCY_ID'])) : $arItem['SUM'];
            $arItem['SUM_BASE_FORMATED'] = $arItem['SUM_BASE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arItem['SUM_BASE'], $currency, $arParams['CURRENCY_ID'])) : $arItem['SUM_BASE_FORMATED'];
        }

        foreach ($arResult['DELIVERY'] as &$arDelivery) {
            $currency = $arDelivery['CURRENCY'];
            $arDelivery['PRICE_FORMATED'] = $arDelivery['PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arDelivery['PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arDelivery['PRICE_FORMATED'];
        }

        $arResult['JS_DATA']['TOTAL']['PAYED_FROM_ACCOUNT_FORMATED'] = $arResult['JS_DATA']['TOTAL']['PAYED_FROM_ACCOUNT_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['PAYED_FROM_ACCOUNT_FORMATED'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['PAYED_FROM_ACCOUNT_FORMATED'];
        $arResult['JS_DATA']['CURRENT_BUDGET_FORMATED'] = $arResult['JS_DATA']['CURRENT_BUDGET_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['USER_ACCOUNT']['CURRENT_BUDGET'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['CURRENT_BUDGET_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['PRICE_WITHOUT_DISCOUNT'] = $arResult['JS_DATA']['TOTAL']['PRICE_WITHOUT_DISCOUNT'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['PRICE_WITHOUT_DISCOUNT_VALUE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['PRICE_WITHOUT_DISCOUNT'];
        $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE_FORMATED'] = $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'] = $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_LEFT_TO_PAY'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['ORDER_PRICE_FORMATED'] = $arResult['JS_DATA']['TOTAL']['ORDER_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['ORDER_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['ORDER_PRICE_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['VAT_SUM_FORMATED'] = $arResult['JS_DATA']['TOTAL']['VAT_SUM_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['VAT_SUM'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['VAT_SUM_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE_FORMATED'] = $arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['DELIVERY_PRICE_FORMATED'] = $arResult['JS_DATA']['TOTAL']['DELIVERY_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['DELIVERY_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['DELIVERY_PRICE_FORMATED'];
        $arResult['JS_DATA']['TOTAL']['PAY_SYSTEM_PRICE_FORMATED'] = $arResult['JS_DATA']['TOTAL']['PAY_SYSTEM_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['JS_DATA']['TOTAL']['PAY_SYSTEM_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['JS_DATA']['TOTAL']['PAY_SYSTEM_PRICE_FORMATED'];
        $arResult['ORDER_TOTAL_PRICE_FORMATED'] = $arResult['ORDER_TOTAL_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['ORDER_TOTAL_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['ORDER_TOTAL_PRICE_FORMATED'];
        $arResult['ORDER_PRICE_FORMATED'] = $arResult['ORDER_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['ORDER_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['ORDER_PRICE_FORMATED'];
        $arResult['PRICE_WITHOUT_DISCOUNT'] = $arResult['PRICE_WITHOUT_DISCOUNT'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['PRICE_WITHOUT_DISCOUNT_VALUE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['PRICE_WITHOUT_DISCOUNT'];
        $arResult['DELIVERY_PRICE_FORMATED'] = $arResult['DELIVERY_PRICE_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['DELIVERY_PRICE'], $currency, $arParams['CURRENCY_ID'])) : $arResult['DELIVERY_PRICE_FORMATED'];
        $arResult['VAT_SUM_FORMATED'] = $arResult['VAT_SUM_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['VAT_SUM'], $currency, $arParams['CURRENCY_ID'])) : $arResult['VAT_SUM_FORMATED'];
        $arResult['PAYED_FROM_ACCOUNT_FORMATED'] = $arResult['PAYED_FROM_ACCOUNT_FORMATED'] ? CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arResult['PAYED_FROM_ACCOUNT_FORMATED'], $currency, $arParams['CURRENCY_ID'])) : $arResult['ORDER_PRICE'];
    }

    public static function checkActions($arItem, $arParams)
    {
        $hasNeedDisc = false;
        $arProduct = $arItem;
        global $USER;
        $arProduct['SECTION_ID'] = $arItem['IBLOCK_SECTION_ID'];
        $discounts = \CCatalogDiscount::GetDiscount(
            $arProduct['ID'],
            $arProduct['IBLOCK_ID'],
            array(),
            $USER->GetGroups(),
            'N',
            SITE_ID,
            array()
        );
        if (!empty($discounts)) {
            foreach ($discounts as $discount) {
                if (array_search($discount['UNPACK'], $arParams['UNPACK']) !== false) {
                    $hasNeedDisc = true;
                    break;
                }else{
                    if (!is_array($arParams['UNPACK'])) {
                        $unpackDiscount = strval($discount['UNPACK']);
                        $unpackParams = strval($arParams['UNPACK']);
                        $similarPercent = 0;
                        similar_text($unpackDiscount, $unpackParams, $similarPercent);
                    } else{
                        foreach ($arParams['UNPACK'] as $paramUnpack){
                            if (intval($similarPercent) > 80) break;
                            $unpackDiscount = strval($discount['UNPACK']);
                            $unpackParams = strval($paramUnpack);
                            $similarPercent = 0;
                            similar_text($unpackDiscount, $unpackParams, $similarPercent);
                        }
                    }
                    if (intval($similarPercent) > 80){
                        $hasNeedDisc = true;
                        break;
                    }
                }
            }
            unset($discount, $discounts);
        }
        return $hasNeedDisc;
    }

    public static function checkDifPriceAndSetBasketItems($arGeoRes)
    {
        if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
            $arParamsBigBasket = Tools::getPararmsOfCMP($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'personal/cart/index.php',true);
            $catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
            $catalogParams['PRICE_CODE'] = $catalogParams['PRICE_CODE'] ?: array('BASE');
            $arDiff = array_diff($arGeoRes['PRICES'], $catalogParams['PRICE_CODE']);
            if (count($arDiff) > 0) {
                \Bitrix\Main\Loader::includeModule('sale');
                CBitrixComponent::includeComponentClass('bitrix:sale.basket.basket');
                $bigBasket = new CBitrixBasketComponent();
                $bigBasket->onPrepareComponentParams($arParamsBigBasket);
                $bigBasket->getBasketItems();
            }
        }
    }

    public static function getCntReviewsOfItem($catalogParams = array(), $arElementData)
    {
        $cnt = 0;
        $cache = new CPHPCache();

        if (empty($catalogParams) || empty($arElementData)) return $cnt;

        switch ($catalogParams['REVIEWS_MODE']) {
            case 'blog':
                if (!empty($arElementData['PROPERTIES'][CIBlockPropertyTools::CODE_BLOG_POST]['VALUE']) && \Bitrix\Main\Loader::includeModule('blog')) {

                $cache_id = 'blog_' . $arElementData['PROPERTIES'][CIBlockPropertyTools::CODE_BLOG_POST]['VALUE'] . $catalogParams["DETAIL_BLOG_URL"].$catalogParams["GROUP_ID"];

                    if (self::$_cacheTime > 0 && $cache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
                        $cnt = $cache->GetVars();
                    } else {
                        $arBlog = CBlog::GetByUrl($catalogParams["DETAIL_BLOG_URL"], $catalogParams["GROUP_ID"]);
                        $arBlog = CBlogTools::htmlspecialcharsExArray($arBlog);

                        $arFilter = array("POST_ID" => $arElementData['PROPERTIES'][CIBlockPropertyTools::CODE_BLOG_POST]['VALUE'], "BLOG_ID" => $arBlog['ID'],);
                        $dbBlogComment = CBlogComment::GetList(array(), $arFilter, false, false, array());
                        $cnt = $dbBlogComment->SelectedRowsCount();

                        $cache->StartDataCache();
                        self::SetTag(self::$_cacheDir, "blog_" . $arBlog["ID"]);
                        $cache->EndDataCache($cnt);
                    }
                }
                break;

            case 'forum':
                if (!empty($arElementData['PROPERTIES']["FORUM_TOPIC_ID"]['VALUE']) && \Bitrix\Main\Loader::includeModule('forum')) {
                    $cache_id = 'forum_' . $arElementData['PROPERTIES']["FORUM_TOPIC_ID"]['VALUE'] . $catalogParams["FORUM_ID"];

                    if (self::$_cacheTime > 0 && $cache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
                        $cnt = $cache->GetVars();
                    } else {
                        $arFilter = array("FORUM_ID" => $catalogParams["FORUM_ID"], "TOPIC_ID" => $arElementData['PROPERTIES']["FORUM_TOPIC_ID"]['VALUE'], "!PARAM1" => "IB");
                        $cnt = CForumMessage::GetListEx(array(), $arFilter, true, 0, array());

                        $cache->StartDataCache();
                        self::SetTag(self::$_cacheDir, "forum_" . $catalogParams["FORUM_ID"]);
                        $cache->EndDataCache($cnt);
                    }
                }

                break;
        }

        return $cnt;
    }

    //GET END OF WORDS
    public static function BITGetDeclNum($value = 1, $status = array())
    {
        $status = (empty($status) ? array(GetMessage('BITRONIC2_CATALOG_ITEM'), GetMessage('BITRONIC2_CATALOG_ITEMSA'), GetMessage('BITRONIC2_CATALOG_ITEMS')) : $status);
        $array = array(2, 0, 1, 1, 1, 2);
        return $status[($value % 100 > 4 && $value % 100 < 20) ? 2 : $array[($value % 10 < 5) ? $value % 10 : 5]];
    }

    public static function SetTag($path, $tags)
    {
        global $CACHE_MANAGER;
        if (! defined("BX_COMP_MANAGED_CACHE"))
            return false;
        $CACHE_MANAGER->StartTagCache($path);
        if (is_array($tags))
        {
            foreach ($tags as $tag)
                $CACHE_MANAGER->RegisterTag($tag);
        }
        else
        {
            $CACHE_MANAGER->RegisterTag($tags);
        }
        $CACHE_MANAGER->EndTagCache();
        return true;
    }

    public static function getSectionByElements($arElements,$arParams){
        $cacheID = 'sections_elements_'.implode('_',$arElements);
        $cacheOb = new CPHPCache();
        $arSections = array();
        $arSectionsIDs = array();
        $IBLOCK_ID = 0;

        if ($cacheOb->InitCache(self::$_cacheTime, $cacheID, self::$_cacheDir)){
            $arSections = $cacheOb->GetVars();
        } else{
            $rsElements = CIBlockElement::getList(array(),array('=ID' => $arElements),false,false,array('ID','IBLOCK_ID','IBLOCK_SECTION_ID'),false);

            while ($arElement = $rsElements->Fetch()){
                if (!in_array($arElement['IBLOCK_SECTION_ID'],$arSectionsIDs)) {
                    $arSectionsIDs[] = $arElement['IBLOCK_SECTION_ID'];
                }
            }
            unset($arElement,$rsElements);

            if (!empty($arSectionsIDs)) {
                $rsSections = CIBlockSection::getList(array(), array('=ID' => $arSectionsIDs), true, array('ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL'), false);
                $rsSections->SetUrlTemplates('', $arParams['SECTION_URL']);

                while ($arSection = $rsSections->GetNext()) {
                    $arSections[$arSection['ID']] = $arSection;
                    $IBLOCK_ID = $IBLOCK_ID ?: $arSection['IBLOCK_ID'];
                }

                unset($arSection,$rsSections);

            }

            $arSections = self::cntElmSectionIfOnFilter($arSections,$arParams,$arSectionsIDs,$IBLOCK_ID);

            $cacheOb->StartDataCache();
            self::SetTag(self::$_cacheDir,"iblock_id_" . $IBLOCK_ID);
            $cacheOb->EndDataCache($arSections);
        }
        return $arSections;
    }

    public static function getSectionsByIDs($arSectionsIDs,$arParams){
        $cacheID = 'sections_ids_'.implode('_',$arSectionsIDs);
        $cacheOb = new CPHPCache();
        $arSections = array();
        $IBLOCK_ID = 0;

        if ($cacheOb->InitCache(self::$_cacheTime, $cacheID, self::$_cacheDir)){
            $arSections = $cacheOb->GetVars();
        } else{

            if (!empty($arSectionsIDs)) {
                $rsSections = CIBlockSection::getList(array(), array('=ID' => $arSectionsIDs), true, array('ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL'), false);
                $rsSections->SetUrlTemplates('', $arParams['SECTION_URL']);

                while ($arSection = $rsSections->GetNext()) {
                    $arSections[$arSection['ID']] = $arSection;
                    $IBLOCK_ID = $IBLOCK_ID ?: $arSection['IBLOCK_ID'];
                }

                unset($arSection,$rsSections);

            }

            $arSections = self::cntElmSectionIfOnFilter($arSections,$arParams,$arSectionsIDs,$IBLOCK_ID);

            $cacheOb->StartDataCache();
            self::SetTag(self::$_cacheDir,"iblock_id_" . $IBLOCK_ID);
            $cacheOb->EndDataCache($arSections);
        }
        return $arSections;
    }
	
	public static function getParentElementsByOffer($arOffers)
	{
		$cacheID = 'parentOfOffers_'.implode('_',$arOffers);
        $cacheOb = new CPHPCache();
        $arParentIds = array();
        $IBLOCK_ID = 0;

        if ($cacheOb->InitCache(self::$_cacheTime, $cacheID, self::$_cacheDir)){
            $arParentIds = $cacheOb->GetVars();
        } else{

            if (!empty($arOffers)) {
                $rsParents = CIBlockElement::getList(array(), array('=ID' => $arOffers), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_CML2_LINK'), false);
                while ($obParent = $rsParents->GetNext()) {
                    if(!empty($obParent['PROPERTY_CML2_LINK_VALUE']))
					{
						 $arParentIds[] = $obParent['PROPERTY_CML2_LINK_VALUE'];
					}
                }
                unset($obParent,$rsParents);
            }
        }
		
        return $arParentIds;
	}

    public static function cntElmSectionIfOnFilter($arSections,$arParams,$arSectionsIDs,$IBLOCK_ID){
        $bContinue = $arSections || $arParams || $arSectionsIDs || $IBLOCK_ID ? true : false;

        if (!$bContinue) return $arSections;

        $arFilter = array();
        self::setFilterAvPrFoto($arFilter,$arParams);

        if (!empty($arFilter)){
            $arFilter['=SECTION_ID'] = $arSectionsIDs;
            $arFilter['ACTIVE'] = 'Y';
            $arFilter['IBLOCK_ID'] = $IBLOCK_ID;
            $rsElements = CIBlockElement::getList(array(),$arFilter,false,false,array('ID','IBLOCK_ID','IBLOCK_SECTION_ID'),false);

            while ($arElement = $rsElements->Fetch()){
                if (!empty($arSections[$arElement['IBLOCK_SECTION_ID']])) {
                    $arSections[$arElement['IBLOCK_SECTION_ID']]['COUNT_AFTER_FILTER_ELEMENTS']++;
                }
            }

            unset($arElement,$rsElements);
        }

        return $arSections;
    }

    public static function getParamsForHideItemsInCatalog(){
        global $rz_b2_options;

        $arParams = array();
        $arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $rz_b2_options['hide-not-available'] == 'Y' ? true : false;
        $arParams['HIDE_ITEMS_ZER_PRICE'] = $rz_b2_options['hide-zero-price'] == 'Y' ? true : false;
        $arParams['HIDE_ITEMS_WITHOUT_IMG'] = $rz_b2_options['hide-empty-img'] == 'Y' ? true : false;
        return $arParams;
    }
    public static function setParamsForHideItemsInCatalog(){
        $arParams = self::getParamsForHideItemsInCatalog();
        CRZBitronic2CatalogUtils::reSafeParamsCatalog($arParams);
    }

    public static function reSafeParamsCatalog($arCheckParams){
        if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
            $catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
            $bDiff = false;

           foreach ($arCheckParams as $key => $valueParam){
               if (!$bDiff){
                   $bDiff = $catalogParams[$key] = $valueParam;
               }
               $catalogParams[$key] = $valueParam;
           }

           if ($bDiff){
               \Yenisite\Core\Ajax::saveParams('bitrix:catalog', $catalogParams, $addId = ($catalogParams['CUSTOM_CACHE_KEY'] ? : ''));
           }
        }
    }

    public static function getPropertyEnumData($idProperty, $idIblock)
    {
        $obCache = new CPHPCache;
        $cache_id = 'property_enum_data_' . $idProperty . $idIblock;
        $arPropertyData = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
            $arPropertyData = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $rsPropertyData = CIBlockProperty::GetPropertyEnum($idProperty, array(), array("IBLOCK_ID"=>$idIblock));

            while ($arPropData = $rsPropertyData->GetNext()) {
                $arPropertyData[] = $arPropData;
            }

            if (defined("BX_COMP_MANAGED_CACHE")) {
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $idIblock);
                $CACHE_MANAGER->EndTagCache();
            }

            $obCache->EndDataCache($arPropertyData);
        }
        unset($obCache);

        return $arPropertyData;
    }

    public static function getFilterParams ($value,&$arProp,$arParams){
        if (Loader::IncludeModule('iblock')) {
            CBitrixComponent::includeComponentClass("bitrix:catalog.smart.filter");
            $htmlCodeProp = htmlspecialcharsbx($value);
            $smartFilter = new CBitrixCatalogSmartFilter();
            $smartFilter->fillItemValues($arProp, $value);
            $arResult['LINK'] = $arParams["CATALOG_PATH"]
                . '?' . $arParams['FILTER_NAME']
                . $arProp['VALUES'][$htmlCodeProp]['CONTROL_ID']
                . '=Y&amp;set_filter=y&amp;rz_all_elements=y';

            return $arResult['LINK'];
        }

    }

    public static function getElementAvailableStatus($idElement = ''){
        if (empty($idElement)) return;
        if (CRZBitronic2Settings::getEdition() == 'PRO'){
            return self::getAvailableStatus($idElement);
        } else{
            $dbElement = \CIBlockElement::GetList(array(),array('ID' => $idElement),false,false,array('ID','IBLOCK_ID','PROPERTY_RZ_AVAILABLE'));
            $dbEl = $dbElement->GetNextElement();
            $arProps = $dbEl->GetProperties();
            return $arProps['RZ_AVAILABLE']['VALUE_XML_ID'];
        }
    }

    public static function checkPriceOfGiftedItem(&$arItems = array()){
        if (empty($arItems)) return array();
        if (!empty($arItems['ITEMS'])){
            foreach ($arItems['ITEMS'] as $key => $arItem){
                self::processItemsForGiftPrice($arItems['ITEMS'][$key]);
            }
        } else{
            self::processItemsForGiftPrice($arItems);
        }
    }

    public static function processItemsForGiftPrice (&$arItem){
        $bItemGift = CRZBitronic2CatalogUtils::checkIsGiftedItem($arItem);
        if ($bItemGift){
            $arErrors = array();
            $arOrder = array('SITE_ID' => SITE_ID);
            $arOrder['BASKET_ITEMS'] = CRZBitronic2CatalogUtils::getOptimalPricesOfItemsInBasket();
            CSaleDiscount::DoProcessOrder($arOrder, array(), $arErrors);
            if ($arOrder['BASKET_ITEMS'][$arItem['ID']]['PRICE'] == 0){
                unset($arItem['MIN_PRICE']);
            }
        }
    }

    public static function checkIsGiftedItem($arItem = array()){
        if (empty($arItem) && !self::isSale()) return false;
        global $USER;
        $bItemGift = (bool)\Bitrix\Sale\Discount\Gift\RelatedDataTable::getRow(array(
            'select' => array('ID'),
            'filter' => array(
                array(
                    'LOGIC' => 'OR',

                    '@ELEMENT_ID' => $arItem['ID'],
                    'SECTION_ID' => $arItem['SECTION']['ID']
                ),
                '=DISCOUNT_GROUP.ACTIVE' => 'Y',
                'DISCOUNT_GROUP.GROUP_ID' => $USER->getUserGroupArray(),
            ),
        ));

        return $bItemGift;
    }

    public static function getOptimalPricesOfItemsInBasket (){
        $arBasketItems = array();
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
            false,
            false,
            array("ID", "CALLBACK_FUNC", "MODULE",
                "PRODUCT_ID", "QUANTITY", "DELAY",
                "CAN_BUY", "PRICE", "WEIGHT")
        );
        while ($arItems = $dbBasketItems->Fetch())
        {
            if (strlen($arItems["CALLBACK_FUNC"]) > 0)
            {
                CSaleBasket::UpdatePrice($arItems["ID"],
                    $arItems["CALLBACK_FUNC"],
                    $arItems["MODULE"],
                    $arItems["PRODUCT_ID"],
                    $arItems["QUANTITY"]);
                $arItems = CSaleBasket::GetByID($arItems["ID"]);
            }

            $arBasketItems[$arItems['PRODUCT_ID']] = $arItems;
        }

       return $arBasketItems;
    }

    public static function setOffersPropsCodes (&$arItem, $arParams){

        $arSKU = array();
        $iblock = 0;
        $boolSKU = false;

        if ($iblock != $arItem['IBLOCK_ID']) {
            $iblock = $arItem['IBLOCK_ID'];
            $arSKU = CCatalogSKU::GetInfoByOfferIBlock($arItem['IBLOCK_ID']);
            $boolSKU = !empty($arSKU) && is_array($arSKU);
        }
        if ($boolSKU) $arItem['bOffers'] = true;

        if ($arItem['bOffers'] && empty($arItem['OFFERS_PROP_CODES'])){
            $arUsedFields = array();
            foreach ($arParams['OFFER_TREE_PROPS'] as $prop){
                $arUsedFields[$prop] = true;
            }
            foreach ($arParams['OFFERS_CART_PROPERTIES'] as $prop){
                $arUsedFields[$prop] = true;
            }
            $arItem['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

        }
    }

    public static function checkGoogleCaptchaShow(){
        global $rz_b2_options;
        return ModulesCheck::isGoogleCaptcha($rz_b2_options);
    }
    public static function showGoogleCaptcha(){
        include $_SERVER['DOCUMENT_ROOT'].'/'.SITE_TEMPLATE_PATH.'/include/google_captcha.php';
    }

    public static function setCaptchaForRegistration(){
        global $rz_b2_options;
        $valueRegOption = $rz_b2_options['use_google_captcha'] == 'N' ? $rz_b2_options['captcha-registration'] : 'N';
        COption::SetOptionString("main", "captcha_registration", $valueRegOption);
    }
    public static function setCaptchaBlog(){
        global $rz_b2_options;
        $rz_b2_options['feedback-for-item-on-detail'] == 'Y'  && $rz_b2_options['use_google_captcha'] == 'N' ? COption::SetOptionString("blog", "captcha_choice", "A") : COption::SetOptionString("blog", "captcha_choice", "N");
    }

    public static function GetResizedImg($input, $resizerOpt = false, $noPhotoSrc = '', $svg = false)
    {
        if (empty($noPhotoSrc)) {
            $noPhotoSrc = Resize::GetResizedImg($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/img/no_photo/main.png', $resizerOpt);
        } else {
            $noPhotoSrc = !$svg ? Resize::GetResizedImg($noPhotoSrc, $resizerOpt) : 'svg';
        }
        return Resize::GetResizedImg($input, $resizerOpt, $noPhotoSrc);
    }

    public static function setSectionsOnOneLvlUp(&$arSections)
    {
        if (empty($arSections) && !is_array($arSections)) return $arSections;

        foreach ($arSections as &$arSection) {
            $arSection['DEPTH_LEVEL'] = intval($arSection['DEPTH_LEVEL']) - 1;
        }
    }

    public static function sortSectionsByLvl($arItems = array(), $maxDepth = 3)
    {
        static $arReturn = array();
        if (empty($arItems)) return $arReturn;

        foreach ($arItems as $key => $arItem) {
            if ($arItem['DEPTH_LEVEL'] > $maxDepth) {
                unset($arItems[$key]);
                continue;
            }

            $curLvl = $arItem['DEPTH_LEVEL'];
            $isParent = $curLvl < $arItems[$key + 1]['DEPTH_LEVEL'];

            if ($curLvl == 1) {
                if ($isParent) {
                    $curItemLevel_1 = $arItem['ID'];
                }
                $arReturn[$arItem['ID']] = array();
            } elseif ($curLvl == "2") {
                $arReturn[$curItemLevel_1][$arItem['ID']] = array();
                if ($isParent) {
                    $curItemLevel_2 = $arItem['ID'];
                }
            } elseif ($curLvl == "3") {
                $arReturn[$curItemLevel_1][$curItemLevel_2][] = $arItem['ID'];
            }

        }

        return $arReturn;
    }

    public static function checkActiveValuesInFilter ($arItem, &$arResult){
        foreach($arItem['VALUES'] as $arValue){
            if ($arValue['CHECKED'] || ($arValue['HTML_VALUE'] && $arItem['PROPERTY_TYPE'] == 'N') || $arItem['PRICE']) {
                $arValue['VALUE'] = $arItem['PROPERTY_TYPE'] == 'N' || $arItem['PRICE'] ? $arValue['HTML_VALUE'] : $arValue['VALUE'];
                if (!empty($arValue['VALUE'])) {
                    $arResult['VALUES_CHECKED'][$arItem['ID']]['VALUES'][] = $arValue;
                    $arResult['VALUES_CHECKED'][$arItem['ID']]['NAME'] = $arItem['NAME'];
                }
            }
        }
    }

    public static function getParnetsSectionsServices ($iblock,$sectionID){
        if (!Loader::includeModule('iblock')) return array();

        $arSections = YenIblock::getTreeToSection($iblock,$sectionID);
        $arReturn = array();

        $cacheId = 'services_of_sections_'.$iblock.'_to_section_'.$sectionID;

        if ($arReturn = Tools::getSavedValues($cacheId)){
            return $arReturn;
        }

        if (!empty($arSections)){
            $arSectionsIDs = array();

            foreach ($arSections as $arSection){
                $arSectionsIDs[] = $arSection['ID'];
            }

            $arFilter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => $iblock,'ID' => $arSectionsIDs);
            $dbSections = CIBlockSection::GetList(array(), $arFilter, false, array("ID",'IBLOCK_ID','UF_SERVICE'));

            while ($arCurSection = $dbSections->GetNext()) {
                $arReturn = array_merge($arReturn,$arCurSection['UF_SERVICE']);
            }
        }
        Tools::saveSomeValuesInCache($arReturn,$cacheId,true,$iblock);

        return $arReturn;
    }

    public static function getSliderImages(&$arItem,$resizerID){
        if (empty($arItem)) return array();

        $productSlider = self::getElementPictureArray($arItem);
        if (empty($productSlider)) {
            $productSlider = array(
                0 => 'no_photo'
            );
        } else {
            foreach ($productSlider as $k => $photoId) {
                $productSlider[$k] = CFile::GetFileArray($photoId);
            }
        }
        $arItem['MORE_PHOTO'] = $productSlider;
        $arItem['MORE_PHOTO_COUNT'] = count($productSlider);
        $arPicture = current($arItem['MORE_PHOTO']);
        $arItem['PICTURE_PRINT']['SRC'] = CResizer2Resize::ResizeGD2($arPicture['SRC'], $resizerID);
    }

    public static function showSecondHoverImg($arItem, $resizer){
        if (empty($arItem)) return '';
        if ($arItem['MORE_PHOTO_COUNT'] <= 1) return '';
        $arPicture = next($arItem['MORE_PHOTO']);
        ?>
        <img src="<?= CResizer2Resize::ResizeGD2($arPicture['SRC'], $resizer) ?>" alt="<?=$arItem['NAME']?>" class="hover-img">
        <?
    }

    public static function checkOfferOnSetFilterInCatalog($arOffer,$arDataFromFilter){
        if (empty($arOffer['PROPERTIES'])) return false;

        foreach ($arDataFromFilter as $keyOfProp => $arValuesOfProp){
            if (!empty($arOffer['PROPERTIES'][$keyOfProp]['VALUE']) && !in_array($arOffer['PROPERTIES'][$keyOfProp]['VALUE'],$arValuesOfProp)){
                return true;
                break;
            }
            if ($keyOfProp == 'RZ_AVAILABLE' && CRZBitronic2Settings::isPro($withGeoip = true)){
                global $rz_b2_options,$APPLICATION;
                if(!isset($rz_b2_options['GEOIP'])) {
                    $arRes = $APPLICATION->IncludeComponent('yenisite:geoip.store', 'empty');
                    $rz_b2_options['GEOIP'] = $arRes;
                }
                if (!empty($arOffer['PROPERTIES'][$keyOfProp.'_'.$rz_b2_options['GEOIP']['ITEM']['ID']]['VALUE']) && !in_array($arOffer['PROPERTIES'][$keyOfProp.'_'.$rz_b2_options['GEOIP']['ITEM']['ID']]['VALUE'],$arValuesOfProp)){
                    return true;
                    break;
                }
            }
        }
    }

    public static function setDisabledValForSlidersInFilter(&$arItems, $arSliders, $arFilter, $arParams){
        unset($arFilter['FACET_OPTIONS']);
        if (empty($arItems) || empty($arSliders) || empty($arFilter) || !Loader::includeModule('iblock')) return false;

        $arFilterOfSliders = array();
        $arrFilter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arParams['IBLOCK_ID'],'SECTION_ID' => $arParams['SECTION_ID'], 'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS']);
        $arSelectInElements = array('ID', 'IBLOCK_ID');
        $arFilter = array_merge($arrFilter,$arFilter);

         foreach ($arSliders as $keyProp => $arSlider){
             if (empty($arSlider['VALUES']['MIN']['VALUE']) || empty($arSlider['VALUES']['MAX']['VALUE'])) continue;
             $arFilterOfSliders[] = array('>=PROPERTY_'.$keyProp => $arSlider['VALUES']['MIN']['VALUE'],'<=PROPERTY_'.$keyProp => $arSlider['VALUES']['MAX']['VALUE']);
             $arSelectInElements[] = 'PROPERTY_'.$keyProp;
         }
        $arFilter[] = array_merge(array('LOGIC' => "OR"),$arFilterOfSliders);

        $dbElements = CIBlockElement::GetList(array(),$arFilter,$arSelectInElements);
        $arElements = array();

        while ($arElement = $dbElements->Fetch()){
            $arElements[] = $arElement;
        }
        unset ($arElement, $dbElements);

        foreach ($arSliders as $keyProp => $arSlider){
            $arItems[$keyProp]['DISABLED'] = true;
            foreach ($arElements as $arElement){
                if (!empty($arElement['PROPERTY_'.$keyProp.'_VALUE'])){
                    $arItems[$keyProp]['DISABLED'] = false;
                    break;
                }
            }
        }
    }

    public static function getDataOfGeoIpStore()
    {
        if (Loader::includeModule('catalog') && ModulesCheck::isGeoIPStore() && empty(self::$arGeoStoreData)) {
            global $APPLICATION, $rz_options;

            self::$arGeoStoreData = $APPLICATION->IncludeComponent(
                "yenisite:geoip.store",
                "empty",
                array(
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "0",
                    "COLOR_SCHEME" => '',
                    "INCLUDE_JQUERY" => "N",
                    "NEW_FONTS" => "Y",
                    "ONLY_GEOIP" => 'Y',
                    "DETERMINE_CURRENCY" => 'Y',
                ),
                false
            );
        }

        return self::$arGeoStoreData;
    }

    public static function setFilterByGeoStore(&$filterName)
        {
            self::$tmp_cache_prop_for_filter_of_geo_store = self::$tmp_cache_prop_for_filter_of_geo_store ?: Option::get(CRZBitronic2Settings::getModuleId(), 'prop_of_all_iblocks_for_filter_by_store', self::$prop_for_filter_of_geo_store);
            global $rz_b2_options;


            if (empty($rz_b2_options['GEOIP'])){
                $arGeoStoreData = self::getDataOfGeoIpStore();
                $rz_b2_options['GEOIP']['ITEM']['ID'] = $arGeoStoreData['ITEM']['ID'];
            }

            $arTmpFilter = array(
            "LOGIC" => "OR",
            array('PROPERTY_' . self::$prop_for_filter_of_geo_store => false),
            array('=PROPERTY_' . self::$prop_for_filter_of_geo_store => $rz_b2_options['GEOIP']['ITEM']['ID']),
            array('=PROPERTY_' . self::$prop_for_filter_of_geo_store => '0'),
        );

        $filterName = $filterName ?: 'arrFilter';
        $GLOBALS[$filterName][] = $arTmpFilter;
    }

    public static function setNotAvailbaleStatusForEmptyOffer (&$arItem){
        if (!Loader::includeModule('catalog')) return;
        if (!$arItem['bOffers'] && isset($arItem['OFFERS'])){
            $arItem['bOffers'] = CCatalogSku::IsExistOffers($arItem['ID']);

            if ($arItem['ON_REQUEST'] && $arItem['bOffers'] && empty($arItem['OFFERS'])){
                $arItem['ON_REQUEST'] = false;
            }
        }
    }

}

?>