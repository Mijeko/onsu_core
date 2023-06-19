<?
$MESS["CATALOG_BUY"] = "Купить";
$MESS["CATALOG_ADD"] = "В корзину";
$MESS["CATALOG_COMPARE"] = "Сравнить";
$MESS["CATALOG_QUANTITY"] = "Количество";
$MESS["CATALOG_QUANTITY_FROM_TO"] = "От #FROM# до #TO#";
$MESS["CATALOG_QUANTITY_FROM"] = "От #FROM#";
$MESS["CATALOG_QUANTITY_TO"] = "До #TO#";
$MESS["CT_BCS_QUANTITY"] = "Количество";
$MESS["CT_BCS_ELEMENT_DELETE_CONFIRM"] = "Будет удалена вся информация, связанная с этой записью. Продолжить?";
$MESS["PODROBNEE"] = "Подробнее";
$MESS["REVIEW"] = "отзывов";

$MESS["FOR_ADMIN_ONLY"] = "Видно только администраторам:";
$MESS["SELLERS"] = "Продажи:";
$MESS["WEEK_CNT"] = "Хиты:";
$MESS["NEW_DAYS"] = "Новинка:";

$MESS["CHOOSE"] = "Выбрать";

global $is_bitronic;
if($is_bitronic)
{
	$MESS['RUB'] = "Р";
	$MESS['RUB_REPLACE'] = "руб";
}


if(!$MESS["FOR_ORDER"]) $MESS["FOR_ORDER"]	= 'под заказ';
if(!$MESS["CATALOG_AVAILABLE"]) $MESS["CATALOG_AVAILABLE"] = "товар в наличии";
if(!$MESS["CATALOG_NOT_AVAILABLE"]) $MESS["CATALOG_NOT_AVAILABLE"] = "товара нет в наличии";

if(!$MESS["STICKER_NEW"]) $MESS["STICKER_NEW"] = "NEW";
if(!$MESS["STICKER_HIT"]) $MESS["STICKER_HIT"] = "HIT";
if(!$MESS["STICKER_BESTSELLER"]) $MESS["STICKER_BESTSELLER"] = "BEST SELLER";


?>