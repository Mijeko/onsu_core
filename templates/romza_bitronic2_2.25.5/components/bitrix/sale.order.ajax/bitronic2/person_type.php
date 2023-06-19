<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (count($arResult["PERSON_TYPE"]) > 1) {
    ?>
    <div class="title-h3"><?= GetMessage("BITRONIC2_SOA_TEMPL_PERSON_TYPE") ?></div>

    <div class="payer-type">
        <? foreach ($arResult["PERSON_TYPE"] as $v):?>
            <label class="radio-styled">
                <input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE"
                       value="<?= $v["ID"] ?>"<? if ($v["CHECKED"] == "Y") echo " checked=\"checked\""; ?>
                       onClick="submitForm()">
                <span class="radio-content">
					<span class="radio-fake"></span>
					<span class="text"><?= $v["NAME"] ?></span>
				</span>
            </label>
        <?endforeach; ?>
        <input type="hidden" name="PERSON_TYPE_OLD" value="<?= $arResult["USER_VALS"]["PERSON_TYPE_ID"] ?>"/>
    </div>
    <?
} else {
    if (IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0) {
        //for IE 8, problems with input hidden after ajax
        ?>
        <span style="display:none;">
		<input type="text" name="PERSON_TYPE" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>"/>
		<input type="text" name="PERSON_TYPE_OLD" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>"/>
		</span>
        <?
    } else {
        foreach ($arResult["PERSON_TYPE"] as $v) {
            ?>
            <input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?= $v["ID"] ?>"/>
            <input type="hidden" name="PERSON_TYPE_OLD" value="<?= $v["ID"] ?>"/>
            <?
        }
    }
}
?>