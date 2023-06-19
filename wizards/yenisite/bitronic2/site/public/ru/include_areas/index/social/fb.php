<? if (CModule::IncludeModule('yenisite.fblikebox')): ?>
<div class="social">
	<? $APPLICATION->IncludeComponent(
		"yenisite:facebook.like_box", 
		".default", 
		array(
			"PAGE_URL" => "https://www.facebook.com/romza.marketplace",
			"WIDTH" => "267",
			"HEIGHT" => "335",
			"FACES" => "Y",
			"COLOR_SCHEME" => "light",
			"STREAM" => "Y",
			"BORDER" => "Y",
			"HEADER" => "N"
		),
		false
	);?>
</div>
<?endif?>
