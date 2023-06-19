<? if (CModule::IncludeModule('yenisite.fpcomments')): ?>
    <div class="social">
        <? $APPLICATION->IncludeComponent(
	"yenisite:flamp.comments", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"WIDGET_ID" => "70000001027298676",
		"HREF_FOR_WIDGET_SUBSCRIBE" => "https://krasnoyarsk.flamp.ru/firm/romza_studiya_tirazhnykh_veb_reshenijj_ip_zabrodin_roman_aleksandrovich-70000001027298676",
		"WIDGET_TYPE" => "responsive-new",
		"COMMENTS_COUNT" => "1",
		"UNIT_MEASURE" => "px",
		"WIDTH" => "300",
		"HEIGHT" => "335",
		"MAIN_TEXT" => "Отзывы о нас на Флампе",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
    </div>
<?endif?>
