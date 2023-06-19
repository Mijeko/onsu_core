<?php
//set constants with module name
include 'constants.php';

use \Yenisite\Core\ModulesCheck;

$MODULE_ID = RZ_B2_MODULE_FULL_NAME;
global $MOD_PREFIX;
$MOD_PREFIX = $MODULE_ID . '_OPT';

if (!$USER->CanDoOperation($MODULE_ID . '_settings')) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CModule::IncludeModule($MODULE_ID);
if (!CRZBitronic2Settings::isCore()){
    die('please install yenisite.core');
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arAllOptions = array(
	'edit2' => array(
		array("store_prop_sync", GetMessage('YNS_BITRONIC2_OPTION_STORE_PROP_SYNC'), array("checkbox"), "Y"),
		array("google_key", GetMessage('YNS_BITRONIC2_GOOGLE_KEY'), array("text"), ""),
		array("path_tu_rules_privacy", GetMessage('B2_PATH_TO_RULES'), array("text"), "/personal/rules/personal_data.php"),
	),
    'edit3' => array(
        array("button_text_buy",     GetMessage('B2_BUTTON_TEXT_BUY_TITLE'),     array("text"), GetMessage('B2_BUTTON_TEXT_BUY_VALUE')),
        array("button_text_incart",  GetMessage('B2_BUTTON_TEXT_INCART_TITLE'),  array("text"), GetMessage('B2_BUTTON_TEXT_INCART_VALUE')),
        array("button_text_na",      GetMessage('B2_BUTTON_TEXT_NA_TITLE'),      array("text"), GetMessage('B2_BUTTON_TEXT_NA_VALUE')),
        array("button_text_request", GetMessage('B2_BUTTON_TEXT_REQUEST_TITLE'), array("text"), GetMessage('B2_BUTTON_TEXT_REQUEST_VALUE')),
        array("button_text_offers",  GetMessage('B2_BUTTON_TEXT_OFFERS_TITLE'),  array("text"), GetMessage('B2_BUTTON_TEXT_OFFERS_VALUE')),
        array("button_text_subscribe",  GetMessage('B2_BUTTON_TEXT_SUBSCRIBE_TITLE'),  array("text"), GetMessage('B2_BUTTON_TEXT_SUBSCRIBE')),
        array("tab_text_characteristics", GetMessage('B2_TAB_TEXT_CHARACTERISTICS_TITLE'), array("text"), GetMessage('B2_TAB_TEXT_CHARACTERISTICS_VALUE')),
        array("tab_text_reviews",         GetMessage('B2_TAB_TEXT_REVIEWS_TITLE'),         array("text"), GetMessage('B2_TAB_TEXT_REVIEWS_VALUE')),
        array("tab_text_video",           GetMessage('B2_TAB_TEXT_VIDEO_TITLE'),           array("text"), GetMessage('B2_TAB_TEXT_VIDEO_VALUE')),
        array("tab_text_documentation",   GetMessage('B2_TAB_TEXT_DOCUMENTATION_TITLE'),   array("text"), GetMessage('B2_TAB_TEXT_DOCUMENTATION_VALUE')),
        array("tab_text_stores",          GetMessage('B2_TAB_TEXT_STORES_TITLE'),          array("text"), GetMessage('B2_TAB_TEXT_STORES_VALUE')),
        array("characteristics_header",   GetMessage('B2_CHARACTERISTICS_HEADER_TITLE'),   array("text"), GetMessage('B2_CHARACTERISTICS_HEADER_VALUE')),
        array("rub_lang",   GetMessage('B2_PUBLIC_RUB_LANG'),   array("text"), GetMessage('B2_PUBLIC_RUB_LANG_VALUE')),
    )
);

if (ModulesCheck::isGeoIPStore()){
    $arAllOptions['edit2'][] =  array("prop_of_all_iblocks_for_filter_by_store",     GetMessage('FILTER_PROP_FOR_GEO_STORE'),     array("text"), GetMessage('DEF_FILTER_PROP_FOR_GEO_STORE'));
}

$arTabs = array(
	array(
		"DIV" => "edit2",
		"TAB" => GetMessage("MAIN_TAB_SET"),
		"ICON" => $MODULE_ID . '_settings',
		"TITLE" => GetMessage("MAIN_TAB_TITLE_SET"),
		'TYPE' => 'options', //options || rights || user defined
	),
	array(
		"DIV" => "edit3",
		"TAB" => GetMessage("B2_LANG_SET"),
		"ICON" => $MODULE_ID . '_settings',
		"TITLE" => GetMessage("B2_LANG_TITLE_SET"),
		'TYPE' => 'options'
	)
);

if (ModulesCheck::isGoogleCaptcha()){
    $arTabs[] = array(
        "DIV" => "edit4",
        "TAB" => GetMessage("SETTINGS_GOOGLE_CAPTCHA"),
        "ICON" => $MODULE_ID . '_settings',
        "TITLE" => GetMessage("SETTINGS_GOOGLE_CAPTCHA_TITLE"),
        'TYPE' => 'options'
    );

    $arAllOptions['edit4'] = array(
        array("google_captcha_re_sec_key",     GetMessage('KEY_FOR_SECURITY'),     array("text"), ''),
        array("google_captcha_re_site_key",     GetMessage('KEY_FOR_SITE'),     array("text"), ''),
    );
}

$tabControl = new CAdminTabControl("tabControl", $arTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update . $Apply . $RestoreDefaults) > 0 && check_bitrix_sessid()) {
	if (strlen($RestoreDefaults) > 0) {
		COption::RemoveOption($MODULE_ID);
		$z = CGroup::GetList($v1 = "id", $v2 = "asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while ($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($MODULE_ID, array($zr["ID"]));
	} else {
		foreach ($arAllOptions as $arTabOptions) {
			foreach ($arTabOptions as $arOption) {
				$name = $arOption[0];
				$val = $_POST[$name];
				if ($arOption[2][0] == "checkbox" && $val != "Y")
					$val = "N";

				COption::SetOptionString($MODULE_ID, $name, $val, $arOption[1]);
			}
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
	foreach ($arTabs as $tab) {
		$tabControl->BeginNextTab();
		switch ($tab['TYPE']) {
			case 'rights':
				require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights2.php");
				break;
			case 'options':
			default :
				foreach ($arAllOptions[$tab['DIV']] as $arOption):
					$val = COption::GetOptionString($MODULE_ID, $arOption[0], $arOption[3]);
					$type = $arOption[2];
					?>
					<tr>
						<td valign="middle" width="50%"><?
							if ($type[0] == "checkbox")
								echo '<label for="', htmlspecialcharsbx($arOption[0]), '">', $arOption[1], '</label>';
							else
								echo $arOption[1];
							?>:
						</td>
						<td valign="bottom" width="50%"><?
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
