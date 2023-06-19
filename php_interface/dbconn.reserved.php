<?php
define("BX_USE_MYSQLI", true);
define("DBPersistent", true);
$DBType = "mysql";
$DBHost = "127.0.0.1";
$DBLogin = "root";
$DBPassword = "";
$DBName = "good_begin";
$DBDebug = true;
$DBDebugToFile = false;

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0755);
@umask(~BX_DIR_PERMISSIONS);
@ini_set("memory_limit", "512M");
@ini_set("sendmail_path", "/usr/local/sbin/exim");
define("BX_DISABLE_INDEX_PAGE", true);
define("BX_CRONTAB", false);
define("BX_CRONTAB_SUPPORT", false);
define("BX_TEMPORARY_FILES_DIRECTORY", '/var/www/good_begin/application/upload/tmp');
// Griff 06.09.2018 добавленно для исправления ошибки интеграции с 1С
//$remote_user = $_SERVER["REMOTE_USER"] ? $_SERVER["REMOTE_USER"] : $_SERVER["REDIRECT_REMOTE_USER"];
//$strTmp = base64_decode(substr($remote_user,6));
//if ($strTmp)
//    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $strTmp);
