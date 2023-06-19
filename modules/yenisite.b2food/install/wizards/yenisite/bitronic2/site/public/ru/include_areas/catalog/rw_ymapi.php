<?if(CModule::IncludeModule('yenisite.yandexreviewsmodel')):?>
<? $APPLICATION->IncludeComponent( 
	"yenisite:yandex.market_reviews_model", ".default", array( 
		"MODEL" => $arParams["ELEMENT_ID"],
		"ACCESSTOKEN" => "Access token",
		"HEAD" => "",
		"HEAD_SIZE" => "h2",
		"SORT" => "date",
		"HOW" => "desc",
		"GRADE" => "0",
		"COUNT" => "5",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"INCLUDE_JQUERY" => "N"
	),
	false
); ?>
<?endif;?>