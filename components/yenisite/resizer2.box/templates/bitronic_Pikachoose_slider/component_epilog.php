<?
switch( $arParams["THUMB"] )
{
	case "left":   $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Pikachoose/styles/left.css'); break;
	case "right":  $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Pikachoose/styles/right.css'); break;
	case "bottom":
	default:       $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Pikachoose/styles/bottom.css'); break;
}
?>