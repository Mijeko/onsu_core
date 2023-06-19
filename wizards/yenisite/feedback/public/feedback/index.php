<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
 <?$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"",
	Array(
		"IBLOCK_TYPE" => "#IBLOCK_TYPE#",
		"IBLOCK" => "#IBLOCK_ID#",
		"NAME_FIELD" => "name",
                "COLOR_SCHEME" => "green",
		"SUCCESS_TEXT" => "",
		"USE_CAPTCHA" => "Y",
                "SHOW_SECTIONS" => "Y",
		"PRINT_FIELDS" => "",
		"AJAX_MODE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"ACTIVE" => "Y",
		"EVENT_NAME" => "#EVENT_NAME#",
		"TEXT_REQUIRED" => "Y",
                "TEXT_SHOW" => "Y",
                "NAME" => 'name',
		"EMAIL" => 'email',
		"PHONE" => 'phone',
		"MESSAGE" => $_POST["romza_feedback"]["text"],
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>