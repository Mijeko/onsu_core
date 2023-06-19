<?
include_once "include_stop_statistic.php";

require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";
/**
 * @todo close session after fix resizer 3.0 problem with parallel work
 */
//@session_write_close();

include_once "include_module.php";

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");

include_once "include_options.php";

//show menu hits
include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/header/menu_catalog.php";
