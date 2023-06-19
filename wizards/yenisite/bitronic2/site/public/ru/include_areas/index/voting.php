<?if(CModule::IncludeModule('vote')):?>
<? global $rz_b2_options;?>
<?$dynamicArea = new \Bitrix\Main\Page\FrameStatic("catalog_hits_dynamic");
$dynamicArea->setAnimation(true);
$dynamicArea->startDynamicArea();?>
<?$APPLICATION->IncludeComponent("bitrix:voting.current", "b2_main_page", Array(
	"CHANNEL_SID" => "BITRONIC2",	// Группа опросов
		"VOTE_ID" => "",	// ID опроса
		"VOTE_ALL_RESULTS" => "N",	// Показывать варианты ответов для полей типа Text и Textarea
		"AJAX_MODE" => "Y",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"COMPONENT_TEMPLATE" => ".default",
	),
	false
);?>
<?$dynamicArea->finishDynamicArea();?>
<?endif;?>
