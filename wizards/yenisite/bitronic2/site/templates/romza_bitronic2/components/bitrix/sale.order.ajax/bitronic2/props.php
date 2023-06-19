<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php");
?>
<div class="title-h3"><?= GetMessage("BITRONIC2_SOA_TEMPL_PROP_INFO") ?></div>

<?
$bHideProps = true;

if (is_array($arResult["ORDER_PROP"]["USER_PROFILES"]) && !empty($arResult["ORDER_PROP"]["USER_PROFILES"])):
    if ($arParams["ALLOW_NEW_PROFILE"] == "Y"):
        ?>
        <div class="delivery-info">
            <span class="text"><?= GetMessage("BITRONIC2_SOA_TEMPL_PROP_CHOOSE") ?></span>

            <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)" class="select-styled">
                <option value="0"><?= GetMessage("BITRONIC2_SOA_TEMPL_PROP_NEW_PROFILE") ?></option>
                <?
                foreach ($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {
                    ?>
                    <option value="<?= $arUserProfiles["ID"] ?>"<? if ($arUserProfiles["CHECKED"] == "Y") echo " selected"; ?>><?= $arUserProfiles["NAME"] ?></option>
                    <?
                }
                ?>
            </select>
        </div>
        <?
    else:
        ?>
        <div class="delivery-info">
            <span class="text"><?= GetMessage("BITRONIC2_SOA_TEMPL_EXISTING_PROFILE") ?></span>

            <?
            if (count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1) {
                foreach ($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {
                    echo "<strong>" . $arUserProfiles["NAME"] . "</strong>";
                    ?>
                    <input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?= $arUserProfiles["ID"] ?>"/>
                    <?
                }
            } else {
                ?>
                <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)" class="select-styled">
                    <?
                    foreach ($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {
                        ?>
                        <option value="<?= $arUserProfiles["ID"] ?>"<? if ($arUserProfiles["CHECKED"] == "Y") echo " selected"; ?>><?= $arUserProfiles["NAME"] ?></option>
                        <?
                    }
                    ?>
                </select>
                <?
            }
            ?>
        </div>
        <?
    endif;
else:
    $bHideProps = false;
endif;
?>

<? $bExpanded = (!$bHideProps || $_POST["showProps"] == "Y"); ?>
<? if (!$arParams['SHOW_H3']): ?>
<div class="title-h3 buyer-info-header">
    <?
    else: ?>
    <div class="title-h3 buyer-info-header">
        <? endif; ?>

        <?= GetMessage("BITRONIC2_SOA_TEMPL_BUYER_INFO") ?>
        <?
        if (array_key_exists('ERROR', $arResult) && is_array($arResult['ERROR']) && !empty($arResult['ERROR'])) {
            $bHideProps = false;
        }
        ?>
        <button type="button" class="buyer-info-toggle btn-expand <?= $bExpanded ? 'expanded' : '' ?>"></button>
        <input type="hidden" name="showProps" id="showProps" value="<?= ($_POST["showProps"] == 'Y' ? 'Y' : 'N') ?>"/>
<? if (!$arParams['SHOW_H3']): ?>
    </div>
<?
else: ?>
    </div>
<? endif; ?>
<div id="sale_order_props" class="buyer-info" <?= !$bExpanded ? "style='display:none;'" : '' ?>>
    <?
    PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
    PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
    ?>
</div>

<? if (!CSaleLocation::isLocationProEnabled()): ?>
    <div style="display:none;">

        <? $APPLICATION->IncludeComponent(
            "bitrix:sale.ajax.locations",
            $arParams["TEMPLATE_LOCATION"],
            array(
                "AJAX_CALL" => "N",
                "COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
                "REGION_INPUT_NAME" => "REGION_tmp",
                "CITY_INPUT_NAME" => "tmp",
                "CITY_OUT_LOCATION" => "Y",
                "LOCATION_VALUE" => "",
                "ONCITYCHANGE" => "submitForm()",
            ),
            null,
            array('HIDE_ICONS' => 'Y')
        ); ?>

    </div>
<? endif ?>
