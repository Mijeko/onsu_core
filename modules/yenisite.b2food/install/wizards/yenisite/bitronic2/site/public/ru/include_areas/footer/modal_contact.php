<?
if (\Bitrix\Main\Loader::IncludeModule("yenisite.feedback")) {
    global $rz_b2_options;
	$APPLICATION->IncludeComponent('yenisite:feedback.add', 'modal_contact',
		array(
			"IBLOCK_TYPE" => "#FEEDBACK_IBLOCK_TYPE#",
			"IBLOCK" => "#IBLOCK_ID#",
			'SUCCESS_TEXT' => 'Спасибо! Наши менеджеры свяжутся с вами в ближайшее время.',
            "USE_CAPTCHA" => $rz_b2_options['use_google_captcha'] == 'N' ? $rz_b2_options["captcha-feedback"] : 'N',
            "USE_GOOGLE_CAPTCHA" => $rz_b2_options['use_google_captcha'] == 'Y' ? $rz_b2_options["captcha-feedback"] : 'N',
			"SHOW_SECTIONS" => "N",
			'PRINT_FIELDS' => array(
				0 => 'NAME',
				1 => 'EMAIL',
				2 => 'PHONE',
				3 => 'PRODUCT',
				4 => 'QUANTITY',
				5 => 'COMMENT'
			),
			'TITLE' => "Оставить заявку",
			'ACTIVE' => 'Y',
			'EVENT' => 'ELEMENT_CONTACT',
			'EMAIL' => 'EMAIL',
			"NAME" => "NAME",
			"PHONE" => "PHONE",
			"FORM" => "form_feedback",
			"EMPTY" => $arParams["EMPTY"],
		),
		false);
}