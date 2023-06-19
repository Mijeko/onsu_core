<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
 <?$APPLICATION->IncludeComponent(
	"yenisite:feedback",
	"",
	Array(
		"IBLOCK_TYPE" => "#IBLOCK_TYPE#",
		"IBLOCK" => "#IBLOCK_ID#",
		"NAME_FIELD" => "name",
		"SUCCESS_TEXT" => "",
		"ACTIVE" => "Y",
                "ALLOW_RESPONSE" => "Y",
		"COLOR_SCHEME" => "green",
		"USE_CAPTCHA" => "Y",
                "TEXT_SHOW" => "Y",
		"TEXT_REQUIRED" => "Y",
		"EVENT_NAME" => "#EVENT_NAME#",
		"PRINT_FIELDS" => array(),
		"MESS_PER_PAGE" => "10",
                "ALWAYS_SHOW_PAGES" => "N",
                "NAME" => 'name',
		"EMAIL" => 'email',
		"PHONE" => 'phone',
		"MESSAGE" => $_POST["romza_feedback"]["text"],
		"AJAX_MODE" => "Y",
		"SEF_MODE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"VARIABLE_ALIASES" => Array(
		)
	)
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>