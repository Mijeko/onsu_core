<?
use \Yenisite\Core\Catalog;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CModule::IncludeModule('yenisite.core');
//delayed function must return a string
if (empty($arResult))
	return "";
global $rz_b2_options, $rz_current_sectionID;
$bShowBacknav = ($rz_b2_options['backnav_enabled'] == 'Y') && (int)$rz_current_sectionID > 0;
$strReturn = '<div class="breadcrumbs-content"><ul class="b-list" itemscope itemtype="http://schema.org/BreadcrumbList">';
$strReturn .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
$strReturn .= '<a href="' . SITE_DIR . '"><i class="flaticon-home1"></i><meta itemprop="name" content="' . SITE_SERVER_NAME . '" /></a>';
$strReturn .= '<i class="separator flaticon-right20"></i> <meta itemprop="position" content="1" /></li> ';
$num_items = count($arResult);
$strBacknav = '';
for ($index = 0, $itemSize = $num_items; $index < $itemSize; $index++) {
	$arSiblings = array();
	if ($bShowBacknav) {
		$arSiblings = Catalog::getChainSiblings($rz_current_sectionID, $arResult[$index]["LINK"]);
	}
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
		$strReturn .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$strReturn .= '<a itemprop="item" href="' . $arResult[$index]["LINK"] . '" title="' . $title . '"';
		if ($arSiblings) {
			$strReturn .= ' data-popup="^.breadcrumbs-content>.backnav.backnav_'. $index .'"';
			if (count($arSiblings) > 1 && $arSiblings['LINK'] != $arResult[$index]["LINK"]) {
                $strBacknav .= '<ul class="backnav backnav_' . $index . '">';
                $backnavIndex = 0;
                $j = 0;
                foreach ($arSiblings as $arSibling) {
                    if ($arSibling['LINK'] == $arResult[$index]['LINK']) {
                        $backnavIndex = $j;
                    }
                    $strBacknav .= '<li><a href="' . $arSibling["LINK"] . '">' . $arSibling["NAME"] . '<i class="flaticon-arrow492 arrow arrow-up"></i></a></li>';
                    ++$j;
                }
                $strBacknav .= '</ul>';
                $strReturn .= ' data-backnav="' . $backnavIndex . '"';
            }
		}
		$strReturn .= '><span itemprop="name">' . $title . '</span><i class="flaticon-arrow486 arrow arrow-down"></i></a><i class="separator flaticon-right20"></i>';
		$strReturn .= '<meta itemprop="position" content="' . ($index + 2) . '" />';
		$strReturn .= '</li> ';
	} else {
		$strReturn .= '<li><span>' . $title . '</span></li>';
	}
}

$strReturn .= '</ul>' . $strBacknav .'</div>';

return $strReturn;
