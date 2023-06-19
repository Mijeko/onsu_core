<?
$MODULE_ID = "yenisite.favorite";
global $MOD_PREFIX;
$MOD_PREFIX = $MODULE_ID . '_OPT';

if (!$USER->CanDoOperation($MODULE_ID . '_settings')) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CModule::IncludeModule($MODULE_ID);

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arAllOptions = array(
	array("allow_guest", GetMessage('YNS_FAVORITE_OPTIONS_PROP_ALLOWGUEST'), array("checkbox"), "Y"),
);

$aTabs = array(
	array(
		"DIV" => "edit2",
		"TAB" => GetMessage("MAIN_TAB_SET"),
		"ICON" => $MODULE_ID . '_settings',
		"TITLE" => GetMessage("MAIN_TAB_TITLE_SET"),
		'TYPE' => 'options', //options || rights || user defined
	),
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("MAIN_TAB_RIGHTS"),
		"ICON" => $MODULE_ID . '_settings',
		"TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS"),
		'TYPE' => 'rights', //options || rights || user defined
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update . $Apply . $RestoreDefaults) > 0 && check_bitrix_sessid()) {
	if (strlen($RestoreDefaults) > 0) {
		COption::RemoveOption($MODULE_ID);
		$z = CGroup::GetList($v1 = "id", $v2 = "asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while ($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($MODULE_ID, array($zr["ID"]));
	} else {
		foreach ($arAllOptions as $arOption) {
			$name = $arOption[0];
			$val = $_POST[$name];
			if ($arOption[2][0] == "checkbox" && $val != "Y")
				$val = "N";

			COption::SetOptionString($MODULE_ID, $name, $val, $arOption[1]);
		}
		//manual for reset defaults
		//COption::SetOptionString($MODULE_ID, 'prop_name', 'propvalue');
	}
}

$tabControl->Begin();
?>
<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&amp;lang=<?= LANG ?>"
	  name="<?= $MODULE_ID ?>_settings">
	<?= bitrix_sessid_post(); ?>
	<?
	foreach ($aTabs as $tab) {
		$tabControl->BeginNextTab();
		switch ($tab['TYPE']) {
			case 'rights':
				require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights2.php");
				break;
			case 'options':
			default :
				foreach ($arAllOptions as $arOption):
					$val = COption::GetOptionString($MODULE_ID, $arOption[0], $arOption[3]);
					$type = $arOption[2];
					?>
					<tr>
						<td valign="top" width="50%"><?
							if ($type[0] == "checkbox")
								echo "<label for=\"" . htmlspecialcharsbx($arOption[0]) . "\">" . $arOption[1] . "</label>";
							else
								echo $arOption[1];
							?>:
						</td>
						<td valign="top" width="50%"><?
							if ($type[0] == "checkbox"):
								?><input type="checkbox" name="<?= htmlspecialcharsbx($arOption[0]) ?>"
										 id="<?= htmlspecialcharsbx($arOption[0]) ?>"
										 value="Y"<?if ($val == "Y") echo " checked";?> /><?
							elseif ($type[0] == "text"):
								?><input type="text" size="<?= $type[1] ?>" maxlength="255" value="<?= htmlspecialcharsbx($val) ?>"
										 name="<?= htmlspecialcharsbx($arOption[0]) ?>" /><?
							elseif ($type[0] == "textarea"):
								?><textarea rows="<?= $type[1] ?>" cols="<?= $type[2] ?>"
											name="<?= htmlspecialcharsbx($arOption[0]) ?>"><?= htmlspecialcharsbx($val) ?></textarea><?
							endif;
							?></td>
					</tr>
				<?
				endforeach;
		}
	}

	$tabControl->Buttons(); ?>
	<script language="javascript">
		function confirmRestoreDefaults() {
			return confirm('<?= AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
		}
	</script>
	<input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
	<input type="hidden" name="Update" value="Y">
	<input type="reset" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">
	<input type="submit" name="RestoreDefaults" title="<?= GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
		   OnClick="return confirmRestoreDefaults();" value="<?= GetMessage("MAIN_RESTORE_DEFAULTS") ?>">
	<? $tabControl->End(); ?>
</form>
