<?
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin.php";

global $USER;
if (!$USER->IsAdmin())
	return;

IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.resizer2");

CJSCore::Init(array('jquery'));

$action = htmlspecialcharsEx($_REQUEST["action"]);

if ($action) {
	CResizer2Resize::ClearCacheAll(true, isset($_REQUEST['static']));
}

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("CACHE"), "ICON" => "catalog", "TITLE" => "")
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextTab();
?>
<form method="get" id="rz_cache_form">
	<br/>
	<input type="hidden" value="<?= LANGUAGE_ID ?>" name="lang" />
	<label for="rz_cache_static">
		<input type="checkbox" id="rz_cache_static" name="static" value="1" />
		<?= GetMessage('RZ_CACHE_STATIC_LABEL') ?><br/>
	</label>
	<br/>
	<input type="submit" name="action" value="<?= GetMessage('CLEAR') ?>" />
	<br/>
</form>

<script type="text/javascript">
	$('#rz_cache_form').on('submit', function(){
		if (!$('#rz_cache_static').is(':checked')) return true;
		return confirm("<?= str_replace(array("\n", "\r"), '\\n', GetMessage('RZ_CACHE_STATIC_CONFIRM')) ?>");
	});
</script>

<?
$tabControl->End();
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php";
?>
