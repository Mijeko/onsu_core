<?php
global $APPLICATION;

switch($arParams['THEME'])
{
	case 'skin1': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin1/colorbox.css'); break;
	case 'skin2': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin2/colorbox.css'); break;
	case 'skin3': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin3/colorbox.css'); break;
	case 'skin4': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin4/colorbox.css'); break;
	case 'skin5': $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin5/colorbox.css'); break;
	default:      $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin1/colorbox.css');
}
?>