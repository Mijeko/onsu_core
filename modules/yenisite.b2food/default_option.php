<?php
//set constants with module name
include 'constants.php';

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/" . RZ_B2_MODULE_FULL_NAME . "/options.php");

${str_replace('.', '_', RZ_B2_MODULE_FULL_NAME).'_default_option'} = array(
	'button_text_buy'     => GetMessage('B2_BUTTON_TEXT_BUY_VALUE'),
	'button_text_incart'  => GetMessage('B2_BUTTON_TEXT_INCART_VALUE'),
	'button_text_na'      => GetMessage('B2_BUTTON_TEXT_NA_VALUE'),
	'button_text_request' => GetMessage('B2_BUTTON_TEXT_REQUEST_VALUE'),
	'button_text_offers'  => GetMessage('B2_BUTTON_TEXT_OFFERS_VALUE'),
);
