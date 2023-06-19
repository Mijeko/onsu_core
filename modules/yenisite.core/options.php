<?php

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager as BxMM;

/**
 * @var CUser $USER
 * @var CMain $APPLICATION
 * @var string $REQUEST_METHOD
 * @var string $RestoreDefaults
 * @var string $colorHLID
 * @var string $colorGuess
 * @var array $arhlblock
 * @var array $arIBlock
 * @var array $arGuess
 *
 */

CJSCore::Init(array('jquery'));

$MODULE_ID = 'yenisite.core';
global $MOD_PREFIX;
$MOD_PREFIX = $MODULE_ID . '_OPT';

if (!$USER->CanDoOperation($MODULE_ID . '_settings')) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loader::includeModule($MODULE_ID);
Loader::includeModule('iblock');
Loader::includeModule('highloadblock');

$bColorGuess = (
BxMM::isModuleInstalled('yenisite.bitronic2pro')/* ||
	BxMM::isModuleInstalled('yenisite.shinmarketpro') ||
	BxMM::isModuleInstalled('yenisite.apparelpro')*/
);

$bDifSettings = !empty($_REQUEST['use_dif_settings']) && $_REQUEST['use_dif_settings'] == 'Y';

if (empty($_REQUEST['use_dif_settings'])) {
    $bDifSettings = (COption::GetOptionString($MODULE_ID, 'different_settings', 'N') === 'Y');
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arSiteList = array();
$rs = \Bitrix\Main\SiteTable::getList(array(
    'select' => array('LID', 'NAME'),
    'order' => array('SORT' => 'ASC'),
));
while ($ar = $rs->fetch()) {
    $arSiteList[] = array('ID' => $ar['LID'], 'NAME' => $ar['NAME']);
}
unset($ar, $rs);

$arTabs = array(
    array(
        "DIV" => "edit2",
        "TAB" => GetMessage("RZ_ADMIN_MAIN_TAB_TITLE"),
        "ICON" => $MODULE_ID . '_settings',
        "TITLE" => GetMessage("RZ_ADMIN_MAIN_TAB_TITLE"),
        'TYPE' => 'options', //options || rights || user defined
    ),
);

$arSettings = array(
    'edit2' => array(
        array('title' => GetMessage('CORE_CAPTCHA_SETTINGS'),),
        'captcha_refresh' => array(
            'type' => 'checkbox',
            'title' => GetMessage('CORE_CAPTCHA_ENABLE_REFRESH'),
            'default' => 'Y',
        ),
        array('title' => GetMessage('RZ_IMAGES_TITLE'),),
        'image_priority' => array(
            'type' => 'image_priority',
            'title' => GetMessage('RZ_IMAGE_PRIORITY_TITLE'),
            'values' => array(
                0 => GetMessage('RZ_IMAGE_NONE'),
                'DETAIL_PICTURE' => GetMessage('RZ_IMAGE_DETAIL_PICTURE'),
                'MORE_PHOTO' => GetMessage('RZ_IMAGE_MORE_PHOTO'),
                'PREVIEW_PICTURE' => GetMessage('RZ_IMAGE_PREVIEW_PICTURE'),
            ),
            'default' => array(
                0 => 'DETAIL_PICTURE',
                1 => 'MORE_PHOTO',
                2 => 'PREVIEW_PICTURE',
            ),
        ),
    ),
);
$arProps = array();

if ($bColorGuess):
    $arTabs[] = array(
        "DIV" => "color_guess_tab",
        "TAB" => GetMessage("RZ_ADMIN_COLOR_GUESS_TAB"),
        "ICON" => $MODULE_ID . '_settings',
        "TITLE" => GetMessage("RZ_ADMIN_COLOR_GUESS_TAB"),
        'TYPE' => 'options',
    );
    // Retrieve stored values

    foreach ($arSiteList as &$arSite) {
        $arSite['color_guess'] = COption::GetOptionString($MODULE_ID, 'color_guess_'.$arSite['ID'], 'N');
        $arSite['color_sets'] = COption::GetOptionString($MODULE_ID, 'color_setts_'.$arSite['ID'], 'a:0:{}');
        $arSite['ar_guess'] = unserialize($arSite['color_sets']);
        $arSite['color_HLID'] = intval($arSite['ar_guess']['reference']);
        unset($arSite['ar_guess']['reference']);

        // Update values from request
        if (isset($_REQUEST['color_guess_hlblock_' . $arSite['ID']]) && ($hlID = intval($_REQUEST['color_guess_hlblock_'. $arSite['ID']])) > 0) {
            $arSite['color_HLID'] = $hlID;
        }

        if (isset($_REQUEST['color_guess_iblock_' . $arSite['ID']]) && is_array($_REQUEST['color_guess_iblock_' . $arSite['ID']])) {
            $arID = $_REQUEST['color_guess_iblock_' . $arSite['ID']];
            foreach ($arID as $key => $id) {
                $arID[$key] = $id = intval($id);
                if ($id < 1) unset($arID[$key]);
            }
            $arSite['ar_guess'] = array_flip($arID);
        }

        foreach ($arSite['ar_guess'] as $iblockId => &$propId) {
            if (!isset($_REQUEST['color_guess_prop_' . $arSite['ID'] . $iblockId])) continue;
            $propId = intval($_REQUEST['color_guess_prop_' . $arSite['ID'] . $iblockId]);
        }
        if (isset($propId)) {
            unset($propId);
        }

        // Fetch HighLoad Blocks
        $arSite['ar_hlblock'] = array();
        $rshlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            'select' => array('*'),
        ));

        while ($arHL = $rshlblock->fetch()) {
            $dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => 'HLBLOCK_' . $arHL['ID'], "FIELD_NAME" => 'UF_RGB'));
            $arHL['RGB'] = (!!$dbRes->Fetch());
            $arHL['SELECTED'] = ($arHL['ID'] == $arSite['color_HLID']);
            $arSite['ar_hlblock'][$arHL['ID']] = $arHL;
        }

        // Fetch ALL InfoBlocks
        $arIBlock = array();
        $arFilter = array('ACTIVE' => 'Y', 'USER_TYPE' => 'directory');
        $rsIBlock = CIBlock::GetList();
        while ($arBlock = $rsIBlock->Fetch()) {
            $arIBlock[$arBlock['ID']] = $arBlock['NAME'];
            if (!array_key_exists($arBlock['ID'], $arSite['ar_guess'] )) continue;

            // Fetch iblock directory properties
            $arFilter['IBLOCK_ID'] = $arBlock['ID'];
            $arProps[$arBlock['ID']] = array();
            $bFound = false;
            $dbRes = CIBlockProperty::GetList(array(), $arFilter);
            while ($arProp = $dbRes->Fetch()) {
                if (isset( $arSite['ar_hlblock'][$colorHLID]) && $arProp['USER_TYPE_SETTINGS']['TABLE_NAME'] !=  $arSite['ar_hlblock'][$colorHLID]['TABLE_NAME']) continue;
                $arProp['SELECTED'] = ($arSite['ar_guess'][$arBlock['ID']] == $arProp['ID']);
                $arProps[$arBlock['ID']][] = $arProp;
                $bFound = $arProp['SELECTED'] ?: $bFound;
            }
            if (!$bFound) {
                $arSite['ar_guess'] [$arBlock['ID']] = 0;
            }
        }

        //unset non existing iblocks from settings array
        foreach ($arSite['ar_guess']  as $iblockId => $propId) {
            if (array_key_exists($iblockId, $arIBlock)) continue;
            unset($arSite['ar_guess'] [$iblockId]);
        }
        if (!$bDifSettings) break;
    }
    unset($arSite);
endif; //$bColorGuess

$arError = array();
$tabControl = new CAdminTabControl("tabControl", $arTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update . $Apply . $RestoreDefaults) > 0 && check_bitrix_sessid()) {
    if (strlen($RestoreDefaults) > 0) {
        COption::RemoveOption($MODULE_ID);
        $colorHLID = 0;
        $arGuess = $arProps = array();
        // $z = CGroup::GetList($v1 = "id", $v2 = "asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
        // while ($zr = $z->Fetch())
        // 	$APPLICATION->DelGroupRight($MODULE_ID, array($zr["ID"]));
    } else {
        if ($bColorGuess):
            foreach ($arSiteList as &$arSite) {
                if (!array_key_exists($arSite['color_HLID'], $arSite['ar_hlblock'])) {
                    $arError[$arSite['ID']]['hlblock'] = GetMessage('CORE_ERROR_HLBLOCK_ID');
                } elseif (!$arSite['ar_hlblock'][$arSite['color_HLID']]['RGB']) {
                    $arError[$arSite['ID']]['hlblock'] = GetMessage('CORE_ERROR_HLBLOCK_RGB');
                }
                if (empty($arSite['ar_guess'])/* && $_REQUEST['color_guess'] === 'Y' /* do you wanna empty iblock list? */) {
                    $arError[$arSite['ID']]['iblock'] = GetMessage('CORE_ERROR_IBLOCK_EMPTY');
                } else {
                    foreach ($arSite['ar_guess']  as $iblockId => $propId) {
                        if (!empty($propId)) continue;
                        $arError[$arSite['ID']]['prop' . $iblockId] = GetMessage('CORE_ERROR_PROP_EMPTY');
                    }
                }
                if (empty($arError[$arSite['ID']])) {
                    $arSite['ar_guess']['reference'] = $arSite['color_HLID'];
                    COption::SetOptionString($MODULE_ID, 'color_setts_'.$arSite['ID'], serialize($arSite['ar_guess']));
                }
                if ($_REQUEST['color_guess_'.$arSite['ID']] === 'N' || ($_REQUEST['color_guess_'.$arSite['ID']] === 'Y' && empty($arError[$arSite['ID']]))) {
                    $arSite['color_guess'] = $colorGuess = $_REQUEST['color_guess_'.$arSite['ID']];
                    COption::SetOptionString($MODULE_ID, 'color_guess_'.$arSite['ID'], $colorGuess);
                    if ($colorGuess == 'N') {
                        UnRegisterModuleDependences("iblock", "OnBeforeIBlockElementAdd", 'yenisite.core', '\Yenisite\Core\ColorGuess',
                            'staticHandler', '',array($arSite['ID']));
                        UnRegisterModuleDependences("iblock", "OnBeforeIBlockElementUpdate", 'yenisite.core', '\Yenisite\Core\ColorGuess', 'staticHandler', '',array($arSite['ID']));
                    } else {
                        RegisterModuleDependences("iblock", "OnBeforeIBlockElementAdd", 'yenisite.core', '\Yenisite\Core\ColorGuess',
                            'staticHandler',100, '',array($arSite['ID']));
                        RegisterModuleDependences("iblock", "OnBeforeIBlockElementUpdate", 'yenisite.core', '\Yenisite\Core\ColorGuess', 'staticHandler',100, '', array($arSite['ID']));
                    }
                } elseif ($colorGuess === 'N' && $_REQUEST['color_guess_'.$arSite['ID']] === 'Y') {
                    $arError[$arSite['ID']]['cguess'] = GetMessage('CORE_ERROR_COLOR_OFF');
                }
                if (!$bDifSettings) break;
            }
            unset($arSite);
        endif; //$bColorGuess
    }
    // determine site dependance of options
    COption::SetOptionString($MODULE_ID, 'different_settings', $bDifSettings ? 'Y' : 'N');
    $site_id = $_REQUEST['current_site'];

    function rz_SaveSetting($MODULE_ID, $code, $val, $title, $SITE_ID = null)
    {
        if (empty($val)) return;
        if ('image_priority' == $code) {
            $val = json_encode($val);
        }
        if (!empty($SITE_ID)) {
            COption::SetOptionString($MODULE_ID, $code, $val, $title, $SITE_ID);
        } else {
            COption::SetOptionString($MODULE_ID, $code, $val, $title);
        }
    }

    // save site dependent options
    foreach ($arSettings as $arTabOptions) {
        foreach ($arTabOptions as $code => $arOption) {
            if (is_int($code)) continue;
            COption::RemoveOption($MODULE_ID, $code);
            if ($bDifSettings) {
                foreach ($arSiteList as $arSite) {
                    if ($arSite['ID'] == $_REQUEST['site']) {
                        rz_SaveSetting($MODULE_ID, $code, $_POST[$code][$arSite['ID']], $arOption['title'], $arSite['ID']);
                    }
                }
            } else {
                rz_SaveSetting($MODULE_ID, $code, $_POST[$code][$site_id], $arOption['title']);
            }
        }
    }
}

if (!empty($arError)) {
    /** @noinspection PhpDynamicAsStaticMethodCallInspection */
    CAdminMessage::ShowMessage(array(
        'MESSAGE' => GetMessage('CORE_ERROR_SAVE'),
        'DETAILS' => GetMessage('CORE_ERROR_SAVE_DETAILS'),
        'TYPE' => 'ERROR',
        'HTML' => false,
    ));
}

$bDifSettings = (COption::GetOptionString($MODULE_ID, 'different_settings', 'N') === 'Y');
?>
<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&amp;lang=<?= LANG ?>"
      name="core_settings" id="core_settings">
    <?= bitrix_sessid_post(); ?>
    <label for="use_dif_settings">
        <input type="hidden"  name="use_dif_settings" value="N">
        <input value="Y" type="checkbox" id="use_dif_settings" name="use_dif_settings"<?= ($bDifSettings ? ' checked="checked"' : '') ?>>
        <?= GetMessage('RZ_DIF_SETTINGS_LABEL') ?>
    </label>
    <br><br>
    <label for="options_site_id"><?= GetMessage('RZ_SITE_SELECT_LABEL') ?>:</label>
    <select name="site" id="options_site_id"<?= ($bDifSettings ? '' : ' disabled="disabled"') ?>>
        <? foreach ($arSiteList as $arSite): ?>
            <option value="<?= $arSite['ID'] ?>"><?= $arSite['NAME'] ?></option>
        <? endforeach ?>
    </select>
    <input type="hidden" name="current_site" id="options_current_site" value="<?= $arSiteList[0]['ID'] ?>"/>
    <br><br>
    <? $tabControl->Begin() ?>
    <? foreach ($arTabs as $tabName => $tab):
        $tabControl->BeginNextTab(); ?>
        <tr>
            <td valign="top" colspan="2">
                <? foreach ($arSiteList as $arSite): ?>
                    <?$siteId = $bDifSettings ? '' : $arSite['ID']?>
                    <div class="adm-site-settings site-<?= $arSite['ID'] ?>">
                        <table cellpadding="0" cellspacing="2" class="adm-detail-content-table edit-table">
                            <? if ($bColorGuess && $tab['DIV'] == 'color_guess_tab'): ?>
                                <tr class="heading">
                                    <td colspan="2"><strong><?= GetMessage('CORE_COLOR_DETECTION') ?></strong></td>
                                </tr>
                                <tr>
                                    <td valign="middle" width="50%">
                                        <label for="color_guess_<?=$arSite['ID']?>"><?= GetMessage('CORE_COLOR_AUTO_DETECTION') ?>:</label>
                                    </td>
                                    <td valign="bottom" width="50%">
                                        <input type="hidden" name="color_guess_<?=$arSite['ID']?>" value="N">
                                        <input type="checkbox" id="color_guess_<?=$arSite['ID']?>" value="Y"
                                               name="color_guess_<?=$arSite['ID']?>" <?= ($arSite['color_guess'] === 'Y' ? ' checked="checked"' : '') ?>>
                                        <span class="errortext"><?= $arError[$arSite['ID']]['cguess'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center"
                                        colspan="2"><?= BeginNote(), GetMessage('CORE_COLOR_DETECTION_INFO'), EndNote() ?></td>
                                </tr>
                                <tr>
                                    <td valign="middle" width="50%">
                                        <label for="color_guess_hlblock"><?= GetMessage('CORE_COLOR_HLBLOCK') ?>:</label>
                                    </td>
                                    <td>
                                        <select id="color_guess_hlblock" name="color_guess_hlblock_<?=$arSite['ID']?>">
                                            <? foreach ($arSite['ar_hlblock'] as $blockId => $arblock):
                                                $bSelected = ($blockId == $arSite['color_HLID']); ?>
                                                <option value="<?= $blockId ?>"<?= ($bSelected ? ' selected="selected"' : '') ?>
                                                        data-rgb="<?= $arblock['RGB'] ? 'Y' : 'N' ?>"
                                                        data-table="<?= $arblock['TABLE_NAME'] ?>"><?= $arblock['NAME'] ?></option>
                                            <? endforeach ?>
                                        </select>
                                        <a id="fillColorLink" class="fillColorLink"
                                           href="<?= BX_ROOT ?>/js/<?= $MODULE_ID ?>/ajax/options.php?ACTION=HLBLOCK_FILL_COLORS">
                                            <span class="rgb-no"><?= GetMessage('CORE_COLOR_HLBLOCK_INIT') ?></span>
                                            <span class="rgb-yes"><?= GetMessage('CORE_COLOR_HLBLOCK_UPDATE') ?></span>
                                        </a>
                                        <div class="errortext"><?= $arError[$arSite['ID']]['hlblock'] ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="middle" width="50%">
                                        <label for="color_guess_iblock"><?= GetMessage('CORE_COLOR_IBLOCKS') ?>:</label>
                                    </td>
                                    <td>
                                        <input type="hidden" name="color_guess_iblock_<?=$arSite['ID']?>[]" value="0">
                                        <select id="color_guess_iblock" name="color_guess_iblock_<?=$arSite['ID']?>[]" size="<?= min(10, count($arIBlock)) ?>"
                                                multiple="multiple">
                                            <? foreach ($arIBlock as $blockId => $blockName):
                                                $bSelected = array_key_exists($blockId, $arSite['ar_guess']); ?>
                                                <option value="<?= $blockId ?>"<?= ($bSelected ? ' selected="selected"' : '') ?>><?= $blockName ?></option>
                                            <? endforeach ?>
                                        </select>
                                        <div class="errortext"><?= $arError[$arSite['ID']]['iblock'] ?></div>
                                    </td>
                                </tr>
                                <? foreach ($arProps as $iblockId => $arCurProps): ?>
                                    <tr class="colorProps">
                                        <td valign="middle">
                                            <label for="color_guess_prop_<?=$arSite['ID']?>_<?= $iblockId ?>"><?= GetMessage('CORE_COLOR_PROPERTY',
                                                    array('#IBLOCK_NAME#' => $arIBlock[$iblockId])) ?></label>
                                        </td>
                                        <td valign="bottom">
                                            <select id="color_guess_prop_<?= $iblockId ?>" name="color_guess_prop_<?=$arSite['ID']?><?= $iblockId ?>">
                                                <? foreach ($arCurProps as $arProp): ?>
                                                    <option value="<?= $arProp['ID'] ?>"<?= ($arProp['SELECTED'] ? ' selected="selected"' : '') ?>><?= $arProp['CODE'] ?>
                                                        :: <?= $arProp['NAME'] ?></option>
                                                <? endforeach ?>
                                            </select>
                                            <span class="errortext"><?= $arError[$arSite['ID']]['prop' . $iblockId] ?></span>
                                        </td>
                                    </tr>
                                <? endforeach ?>
                                <tr class="colorEnd">
                                    <td></td>
                                    <td></td>
                                </tr>
                            <? endif ?>
                            <? foreach ($arSettings[$tab['DIV']] as $code => $arOption):
                                if (is_int($code)): ?>
                                    <tr class="heading">
                                        <td colspan="2"><strong><?= $arOption['title'] ?></strong></td>
                                    </tr>
                                <? else: ?>
                                    <tr>
                                        <?
                                        $val = COption::GetOptionString($MODULE_ID, $code, $arOption['default'], $arSite['ID']);
                                        $type = $arOption['type'];
                                        $title = $arOption['title'];
                                        ?>
                                        <td valign="middle" width="50%" class="adm-detail-content-cell-l"><?
                                            if ($type == 'checkbox') {
                                                echo '<label for="', htmlspecialcharsbx($code), '_', $arSite['ID'], '">', $arOption['title'], '</label>';
                                            } else {
                                                echo $arOption['title'];
                                            }
                                            ?>:
                                        </td>
                                        <td valign="bottom" width="50%" class="adm-detail-content-cell-r">
                                            <? switch ($type):
                                                case 'checkbox': ?>
                                                    <input type="hidden" name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>]"
                                                           value="N"/>
                                                    <input type="checkbox" name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>]"
                                                           id="<?= htmlspecialcharsbx($code), '_', $arSite['ID'] ?>"
                                                           value="Y"<? if ($val == 'Y') echo ' checked'; ?> /><?
                                                    break;
                                                case 'text': ?>
                                                    <input type="text" size="<?= $arOption['size'] ?>" maxlength="255"
                                                           value="<?= htmlspecialcharsbx($val) ?>"
                                                           name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>]" /><?
                                                    break;
                                                case 'select': ?>
                                                    <select name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>]"
                                                            id="<?= htmlspecialcharsbx($code) ?>_<?= $arSite['ID'] ?>">
                                                        <? foreach ($arOption['values'] as $value => $name): ?>
                                                            <option
                                                                    value="<?= $value ?>" <? if ($val == $value): ?> selected<? endif ?>><?= $name ?></option>
                                                        <? endforeach; ?>
                                                    </select>
                                                    <?
                                                    break;
                                                case 'image_priority':
                                                    if (!is_array($val) && is_string($val)) {
                                                        $val = json_decode($val);
                                                    }
                                                    for ($f = -1; $f++ < count($arOption['values']) - 2;):?>
                                                        <br>
                                                        <select name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>][<?= $f ?>]"
                                                                id="<?= htmlspecialcharsbx($code) ?>_<?= $arSite['ID'], '_', $f ?>">
                                                            <? foreach ($arOption['values'] as $value => $name): ?>
                                                                <option
                                                                        value="<?= $value ?>" <? if ($val[$f] == $value): ?> selected<? endif ?>><?= $name ?></option>
                                                            <? endforeach ?>
                                                        </select>
                                                    <? endfor ?>
                                                    <? break;
                                                case 'textarea':
                                                default: ?>
                                                    <textarea rows="<?= $arOption['rows'] ?>" cols="<?= $arOption['cols'] ?>"
                                                              name="<?= htmlspecialcharsbx($code) ?>[<?= $arSite['ID'] ?>]"><?= htmlspecialcharsbx($val) ?></textarea>
                                                <? endswitch ?>
                                        </td>
                                    </tr>
                                <? endif ?>
                            <? endforeach ?>
                        </table>
                    </div>
                    <?if (!$bDifSettings) break;?>
                <? endforeach ?>
            </td>
        </tr>
    <? endforeach ?>
    <?
    $tabControl->Buttons(); ?>
    <script language="javascript">
        function confirmRestoreDefaults() {
            return confirm('<?= addslashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
        }
    </script>
    <input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
    <input type="hidden" name="Update" value="Y">
    <input type="reset" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">
    <input type="submit" name="RestoreDefaults" title="<?= GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
           onclick="return confirmRestoreDefaults();" value="<?= GetMessage("MAIN_RESTORE_DEFAULTS") ?>">
    <? $tabControl->End(); ?>
</form>
<div id="save_others_sites">

</div>

<script type="text/javascript">
    var ajaxURL = '<?=BX_ROOT?>/js/<?=$MODULE_ID?>/ajax/options.php';
    $(function ($) {
        $('#use_dif_settings').on('change', function () {
            $('#options_site_id').prop('disabled', !$(this).is(':checked'));
        });

        $('#options_site_id').on('change', function () {
            var siteId = $(this).val();
            $('.adm-site-settings').hide();
            $('.site-' + siteId).show();
            $('#options_current_site').val(siteId);
        }).trigger('change');

    });
    <? if($bColorGuess): ?>
    function toggleButtons(enabled) {
        if (typeof toggleButtons.$obj == "undefined") {
            toggleButtons.$obj = $('input[type="submit"], input[type="reset"]');
        }
        if (typeof toggleButtons.count == "undefined") {
            toggleButtons.count = 0;
        }
        var disabled = !enabled;
        toggleButtons.count += disabled ? 1 : -1;
        if (disabled || toggleButtons.count < 1) {
            toggleButtons.$obj.prop('disabled', disabled);
        }
        if (toggleButtons.count < 0) {
            toggleButtons.count = 0;
        }
    }

    function updateProps($this) {
        if (typeof updateProps.timeout == "undefined") {
            updateProps.timeout = false;
        }
        if (updateProps.timeout) {
            clearTimeout(updateProps.timeout);
        } else {
            toggleButtons(false);
        }

        updateProps.timeout = setTimeout(function () {
            updateProps.timeout = false;
            BX.showWait();

            var $iblock = $this.closest('.adm-detail-content-table.edit-table').find('#color_guess_iblock').prop('disabled',true);
            var $hlblock = $this.prop('disabled', true);
            var params = {
                'iblock_id': $iblock.val(),
                'reference_table': $hlblock.find(':selected').data('table')
            };
            if (params.iblock_id != null) {
                for (var i = 0; i < params.iblock_id.length; i++) {
                    var iblock = params.iblock_id[i];
                    var $prop = $('#color_guess_prop_' + iblock);
                    if ($prop.length > 0) {
                        if (typeof params.prop_id == "undefined") params.prop_id = {};
                        params.prop_id[iblock] = $prop.val();
                    }
                }
            }
            params.ACTION = 'GET_IBLOCK_DIRECTORY_PROPS';
            params.SITE_ID = $('#options_site_id').val();

            $.post(ajaxURL, params, function (data) {
                if (!!data.success) {
                    $this.closest('.adm-detail-content-table.edit-table').find('tr.colorProps').remove();
                    $this.closest('.adm-detail-content-table.edit-table').find('tr.colorEnd').replaceWith(data.msg);
                } else {
                    alert('Error: ' + data.msg);
                }
                $iblock.add($hlblock).prop('disabled', false);
                toggleButtons(true);
                BX.closeWait();
            }, 'json');
        }, 700);
    }

    function updateColorLink($this) {
        var $hl = $this;
        $this.siblings('#fillColorLink').data('hlblock', $hl.val())
            .closest('td')
            .toggleClass('has-rgb', ($hl.find(':selected').data('rgb') === 'Y'));
    }
    $(document).ready($('#options_site_id').trigger('change'));

    $('#options_site_id').on('change', function(){
        var className = $(this).val(),
            $this = $('.adm-site-settings.site-' + className).find('[name=color_guess_hlblock_' + className + ']');

        updateColorLink($this);
        updateProps($this);
    });

    $('[name*=color_guess_hlblock_]').on('change', function () {
        updateColorLink($(this));
        updateProps($(this));
    });

    $('.fillColorLink').on('click', function (e) {
        e.preventDefault();
        BX.showWait();
        toggleButtons(false);

        var $_ = $(this),
            $hlBlock = $_.siblings('#color_guess_hlblock'),
            params = $_.data();

        params['ACTION'] = 'HLBLOCK_FILL_COLORS';

        $_.css('pointer-events', 'none');
        $hlBlock.prop('disabled', true);

        $.post($_.attr('href'), params, function (data) {
            if (!!data.success) {
                $hlBlock
                    .find('option[value="' + params.hlblock + '"]').data('rgb', 'Y')
                    .end().prop('disabled', false);
            }
            updateColorLink($_.siblings('#color_guess_hlblock'));
            $_.css('pointer-events', '');
            BX.closeWait();
            toggleButtons(true);
        }, 'json');
    });

    $('[name*="color_guess_iblock_"]').on('change', function () {
        var siteId = $('#options_site_id').val();
        updateProps($(this).closest('.adm-detail-content-table.edit-table').find('[name=color_guess_hlblock_' + siteId + ']'))
    });

    $('#core_settings').on('reset', function () {
        var $hlblock = $('#color_guess_hlblock');
        var hlblock = $hlblock.val();
        setTimeout(function () {
            if ($hlblock.val() != hlblock) {
                $hlblock.trigger('change');
                return;
            }
            var $colorGuessIblock = $('#color_guess_iblock');
            var iblocks = $colorGuessIblock.val();
            if (!iblocks) {
                iblocks = [];
            }
            if (iblocks.length != $('tr.colorProps').length) {
                $colorGuessIblock.trigger('change');
            } else {
                for (var i = 0; i < iblocks.length; i++) {
                    if ($('#color_guess_prop_' + iblocks[i]).length == 1) continue;
                    $colorGuessIblock.trigger('change');
                    break;
                }
            }
        }, 1);
    });
    <? endif ?>
</script>