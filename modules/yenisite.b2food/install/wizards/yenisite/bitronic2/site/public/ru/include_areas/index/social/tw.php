<? if (CModule::IncludeModule('yenisite.twittertimelines')): ?>
<div class="social">
	<? $APPLICATION->IncludeComponent("yenisite:twitter.timelines", "bitronic2", array(
		"WIDGET_ID" => "367161119997050880",
		"USERNAME" => "romza_bx",
		"RELATED" => "twitterapi,twitter",
		"LANG" => "RU",
		"WIDTH" => "265",
		"HEIGHT" => "350",
		"COLOR_SCHEME" => "light",
		"LINK_COLOR" => "cc0000",
		"BORDER_COLOR" => "#00AEEF",
		"CHROME" => array(
		),
		"TWEET_LIMIT" => "0"
		),
		false
	);?>
</div>
<?endif?>
