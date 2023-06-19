<?
include_once "include_stop_statistic.php";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use \Yenisite\Favorite\Favorite;
use Bitrix\Main\Loader;

include_once "include_module.php";

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");
include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/ajax.php");

include_once "include_options.php";

include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/header/favorites.php";