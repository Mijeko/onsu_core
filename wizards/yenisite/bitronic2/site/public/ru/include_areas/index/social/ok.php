<? if (CModule::IncludeModule('yenisite.okgroup')): ?>
<div class="social">
	<? $APPLICATION->IncludeComponent("yenisite:odnoklassniki.group_widget", ".default", array(
		"GROUP_ID" => "54188100943986",
		"WIDTH_SCHEME" => "0",
		"WIDTH" => "265",
		"HEIGHT_SCHEME" => "5"
		),
		false
	);?>
</div>
<?endif?>
