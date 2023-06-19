<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();


CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["strProfileError"], "TYPE" => "ERROR"));

if ($arResult['DATA_SAVED'] == 'Y') {
    CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => GetMessage('PROFILE_DATA_SAVED'), "TYPE" => "OK"));
}

$arFields = array(
    'MAIN' => array(
        'LOGIN' => array(
            'REQUIRED' => true
        ),
        'EMAIL' => array(
            'REQUIRED' => true
        ),
        'NEW_PASSWORD' => array(
            'REQUIRED' => true
        ),
        'NEW_PASSWORD_CONFIRM' => array(
            'REQUIRED' => true
        ),
        'NAME' => array(
            'REQUIRED' => false
        ),
        'LAST_NAME' => array(
            'REQUIRED' => false
        ),
        'PERSONAL_BIRTHDAY' => array(
            'REQUIRED' => false
        ),
        'PERSONAL_PHONE' => array(
            'REQUIRED' => false
        ),
        'PERSONAL_PHOTO' => array(
            'REQUIRED' => false
        ),
    ),
);
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'path_tu_rules_privacy', SITE_DIR . 'personal/rules/personal_data.php');
?>

    <form method="post" name="form1" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data"
          class="form_account-settings">
        <?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="lang" value="<?= LANG ?>"/>
        <input type="hidden" name="ID" value=<?= $arResult["ID"] ?>/>
        <input type="hidden" name="privacy_policy" value="N"/>

        <div class="update-info">
            <div class="line-wrap">
                <span class="text"><?= GetMessage('LAST_UPDATE') ?></span>
                <span class="content"><?= $arResult['arUser']["TIMESTAMP_X"] ?></span>
            </div>
            <div class="line-wrap">
                <span class="text"><?= GetMessage('LAST_LOGIN') ?></span>
                <span class="content"><?= $arResult['arUser']["LAST_LOGIN"] ?></span>
            </div>
        </div>

        <div class="expandable allow-multiple-expanded expanded">
            <header>
			<span class="text-wrap">
				<span class="text"><?= GetMessage('REG_SHOW_HIDE') ?></span>
			</span>
            </header>
            <div class="expand-content">
                <? foreach ($arFields['MAIN'] as $code => $arFiled):
                    switch ($code):
                        case 'LOGIN':
                            ?>
                            <input type="hidden" name="<?= $code ?>" id="<?= $code ?>"
                                   value="<?= $arResult["arUser"][$code] ?>"/>
                            <? break;
                        case 'NEW_PASSWORD':
                            if ($arResult["SECURE_AUTH"] && $arResult["arUser"]["EXTERNAL_AUTH_ID"] == '') {
                                echo GetMessage("AUTH_SECURE_NOTE");
                            }
                        case 'NEW_PASSWORD_CONFIRM':
                            if ($arResult["arUser"]["EXTERNAL_AUTH_ID"] != '') {
                                break;
                            } ?>
                            <label class="line-wrap" for="<?= $code ?>">
                                <span class="label-text text"><?= GetMessage($code) ?><? if ($arFiled['REQUIRED']):?>
                                        <span class="required-asterisk">*</span><?endif ?>:</span>
                                <span class="content">
								<input
                                        type="password"
                                        name="<?= $code ?>"
                                        id="<?= $code ?>"
                                        class="textinput"
                                        maxlength="50"
                                        value=""
                                        autocomplete="off"
                                >
							</span>
                            </label>
                            <? break;
                        case 'PERSONAL_BIRTHDAY':
                            ?>
                            <label class="line-wrap" for="PERSONAL_BIRTHDAY">
                                <span class="label-text text"><?= GetMessage('USER_BIRTHDAY_DT') ?>:</span>
                                <span class="content">
							<?
                            $APPLICATION->IncludeComponent(
                                'bitrix:main.calendar',
                                '',
                                array(
                                    'SHOW_INPUT' => 'Y',
                                    'FORM_NAME' => 'form1',
                                    'INPUT_NAME' => 'PERSONAL_BIRTHDAY',
                                    'INPUT_VALUE' => $arResult["arUser"]["PERSONAL_BIRTHDAY"],
                                    'SHOW_TIME' => 'N',
                                    'INPUT_ADDITIONAL_ATTR' => 'class="textinput"'
                                ),
                                null,
                                array('HIDE_ICONS' => 'Y')
                            );
                            ?>

							</span>
                            </label>
                            <? break;
                        case 'PERSONAL_PHOTO':
                            ?>
                            <div class="line-wrap avatar">
                                <label for="avatar" class="text"><?= GetMessage("USER_PHOTO") ?>:</label>
                                <div class="content">
                                    <div class="img-container">
                                        <?= $arResult["arUser"]["PERSONAL_PHOTO_HTML"] ?>
                                    </div>
                                    <div class="fileinput-styled">
                                        <input name="PERSONAL_PHOTO" id="PERSONAL_PHOTO" size="20" type="file">
                                        <label for="PERSONAL_PHOTO" class="btn-fileinput">
                                            <span class="text"><?= GetMessage("CHOOSE_FILE") ?></span>
                                        </label>
                                        <div class="clearfix">
                                            <span class="chosen-file"><?= GetMessage("NOT_CHOOSEN_FILE") ?></span>
                                            <div class="item-actions">
                                                <? if (intval($arResult["arUser"]['PERSONAL_PHOTO']) > 0):?>
                                                    <label class="checkbox-styled">
                                                        <input type="checkbox" id="PERSONAL_PHOTO_del"
                                                               name="PERSONAL_PHOTO_del" value="Y">
                                                        <span class="checkbox-content" tabindex="3">
													<i class="flaticon-check14"></i>
                                                            <?= GetMessage("DELETE_FILE") ?>
												</span>
                                                    </label>
                                                <?endif ?>
                                            </div>
                                        </div>
                                    </div><!-- /.fileinput-styled -->
                                </div><!-- /.content -->
                            </div><!-- /.line-wrap.avater -->
                            <? break;
                        default:
                            ?>
                            <label class="line-wrap" for="<?= $code ?>">
                                <span class="label-text text"><?= GetMessage($code) ?><? if ($arFiled['REQUIRED']):?>
                                        <span class="required-asterisk">*</span><?endif ?>:</span>
                                <span class="content">
								<input
                                        type="<?= ($code == 'EMAIL') ? 'email' : 'text' ?>"
                                        name="<?= $code ?>"
                                        id="<?= $code ?>"
                                        class="textinput"
                                        maxlength="50"
                                        value="<?= $arResult["arUser"][$code] ?>"
                                >
							</span>
                            </label>
                        <?endswitch;
                endforeach ?>
            </div><!-- /.expand-content -->
        </div><!-- /.expandable.allow-multiple-expanded -->
        <div>
            <label class="checkbox-styled">
                <input value="Y" type="checkbox" name="privacy_policy">
                <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?= GetMessage('BITRONIC2_I_ACCEPT') ?>
                    <a href="<?= $pathToRules ?>" class="link"><span
                                class="text"><?= GetMessage('BITRONIC2_POLITIC_PRIVICE') ?></span></a>
    </span>
            </label>
        </div>
        <button class="btn-main disabled" name="save" value="Y"><span class="text"><?= GetMessage('SAVE') ?></span></button>

        <div class="form-footnote"><span
                    class="required-asterisk">*</span> &mdash; <?= GetMessage('REQUIRED_DESCRIPTION') ?></div>
    </form>
<?
if ($arResult["SOCSERV_ENABLED"]) {
    $APPLICATION->IncludeComponent(
        "bitrix:socserv.auth.split",
        "bitronic2",
        array(
            "SHOW_PROFILES" => "Y",
            "ALLOW_DELETE" => "Y",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );
}
?>