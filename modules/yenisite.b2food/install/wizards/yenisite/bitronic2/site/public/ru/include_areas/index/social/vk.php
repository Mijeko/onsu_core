<? if (IsModuleInstalled('primepix.vkontakte')): ?>
<div class="social">
	<? $APPLICATION->IncludeComponent(
		"primepix:vkontakte.group", 
		".default", 
		array(
			"ID_GROUP" => "74168407",
			"TYPE_FORM" => "0",
			"WIDTH_FORM" => "265",
			"HEIGHT_FORM" => "335"
		),
		false
	); ?>
</div>
<? endif ?>
