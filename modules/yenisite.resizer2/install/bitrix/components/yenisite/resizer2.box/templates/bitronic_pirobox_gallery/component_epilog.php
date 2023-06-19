<?php
if (!CModule::IncludeModule("yenisite.bitronic")) {
	$APPLICATION->AddHeadScript('/yenisite.resizer2/js/pirobox/jquery-ui-1.8.2.custom.min.js');
}

switch ($arParams['PIRO_STYLE']) {
	case 'style2': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/pirobox/style2/style.css'); break;
	case 'style1':
	default:       $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/pirobox/style1/style.css'); break;
}
?>