<?php
namespace Yenisite\Core;

use \Bitrix\Main\Loader;

class ModulesCheck {
   public static $module_google_captcha = 'yenisite.googlecaptcha';
   public static $module_seo_filter = 'yenisite.seofilter';
   public static $module_geo_ip_store = 'yenisite.geoipstore';


    public static function isGoogleCaptcha($arParams = array()){
        if (!empty($arParams)){
            return $arParams['use_google_captcha'] == 'Y' && Loader::includeModule(self::$module_google_captcha);
        } else{
            return Loader::includeModule(self::$module_google_captcha);
        }
    }

    public static function isSeoFilter(){
        return Loader::includeModule(self::$module_seo_filter);
    }
    public static function isGeoIPStore(){
        return Loader::includeModule(self::$module_geo_ip_store);
    }
}