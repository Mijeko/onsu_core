<?
use Bitrix\Main\Loader;

Loader::includeModule('yenisite.core');

$moduleId = "yenisite.b2food";	/// !!!!!!!!!!!!!!
$moduleCode = 'b2food'; // !!!!!!!!
$settingsClass = 'CRZBitronic2Settings';

if (!Loader::includeModule($moduleId)) die("Module {$moduleId} not installed!");
