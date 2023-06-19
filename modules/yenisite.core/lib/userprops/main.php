<?
namespace Yenisite\Core\Userprops;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Main {

    private static $arUserPropsClasses = array(
        'DISCOUNT_CLASS' => array (
            'NAME_CLASS' => '\\Yenisite\\Core\\Userprops\\Props\\Typediscount',
            'EVENT_METHOD_ADMIN_SECTION' => 'GetUserTypeDescription',
            'EVENT_METHOD_IBLOCK_SECTION' => 'GetIBlockPropertyDescription'),
        'GEO_STORE_CLASS' => array (
            'NAME_CLASS' => '\\Yenisite\\Core\\Userprops\\Props\\Typegeostore',
            'EVENT_METHOD_ADMIN_SECTION' => 'GetUserTypeDescription',
            'EVENT_METHOD_IBLOCK_SECTION' => 'GetIBlockPropertyDescription'),
    );

    static public function loadUserProp ($nameClassInArray = '', $admin = true, $iblock = true) {
        if (!strlen($nameClassInArray) > 0) return GetMessage('EMPTY_OR_NOT_STRING_CLASS');

        if (empty(self::$arUserPropsClasses[$nameClassInArray])) return GetMessage('WRONG_CLASS');

        $nameClass = self::$arUserPropsClasses[$nameClassInArray]['NAME_CLASS'];

        if ($admin){
            $nameMethod = self::$arUserPropsClasses[$nameClassInArray]['EVENT_METHOD_ADMIN_SECTION'];
            UnRegisterModuleDependences ("main","OnUserTypeBuildList",'yenisite.core',$nameClass, $nameMethod);
            RegisterModuleDependences("main", "OnUserTypeBuildList", 'yenisite.core', $nameClass, $nameMethod, 190);
        }

        if ($iblock){
            $nameMethod = self::$arUserPropsClasses[$nameClassInArray]['EVENT_METHOD_IBLOCK_SECTION'];
            UnRegisterModuleDependences ("iblock","OnIBlockPropertyBuildList",'yenisite.core',$nameClass, $nameMethod);
            RegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", 'yenisite.core', $nameClass, $nameMethod, 190);
        }
    }
}