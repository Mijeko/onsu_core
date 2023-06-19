<?
namespace Yenisite\Core\JS;

class Main
{
    public static function includeJavaScript($scriptName = ''){
        $Asset = \Bitrix\Main\Page\Asset::getInstance();
        $Asset->addJs(BX_ROOT.'/js/yenisite.core/lib/'.$scriptName);
    }
}