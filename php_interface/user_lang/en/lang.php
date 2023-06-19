<?
if (Bitrix\Main\Loader::includeModule('intervolga.recaptcha'))
{
	$message = 'Not passed verification reCaptcha';

	$MESS['/bitrix/modules/main/lang/en/classes/general/user.php']["main_user_captcha_error"] = $message;
	$MESS['/bitrix/modules/form/lang/en/include.php']["FORM_WRONG_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/iblock.element.add.form/lang/en/component.php']["IBLOCK_FORM_WRONG_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/blog.post.comment/lang/en/component.php']["B_B_PC_CAPTCHA_ERROR"] = $message;
	$MESS['/bitrix/components/bitrix/catalog.product.subscribe/lang/en/ajax.php']["CPSA_INCCORECT_INPUT_CAPTHA"] = $message;
	$MESS['/bitrix/components/bitrix/forum.comments/lang/en/class.php']["POSTM_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/forum.message.send/lang/en/component.php']["F_BAD_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/forum.topic.reviews/lang/en/action.php']["POSTM_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/idea.comment.list/lang/en/component.php']["B_B_PC_CAPTCHA_ERROR"] = $message;
	$MESS['/bitrix/components/bitrix/infoportal.element.add.form/lang/en/component.php']["IBLOCK_FORM_WRONG_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/main.feedback/lang/en/component.php']["MF_CAPTCHA_WRONG"] = $message;
	$MESS['/bitrix/components/bitrix/main.register/lang/en/component.php']["REGISTER_WRONG_CAPTCHA"] = $message;
	$MESS['/bitrix/components/bitrix/sale.notice.product/lang/en/component.php']["NOTIFY_NO_CAPTHA"] = $message;
	$MESS['/bitrix/components/bitrix/socialnetwork.blog.post.comment/lang/en/component.php']["B_B_PC_CAPTCHA_ERROR"] = $message;
	$MESS['/bitrix/modules/main/lang/en/classes/general/user.php']['MAIN_FUNCTION_REGISTER_CAPTCHA'] = $message;
	$MESS['/bitrix/components/bitrix/main.feedback/lang/ru/component.php']['MF_CAPTHCA_EMPTY'] = $message;
}