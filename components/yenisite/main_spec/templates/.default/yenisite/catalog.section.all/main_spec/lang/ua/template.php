<?
$MESS["CATALOG_BUY"] = "Купити";
$MESS["CATALOG_ADD"] = "В кошику";
$MESS["CATALOG_COMPARE"] = "Порівняти";
$MESS["CATALOG_QUANTITY"] = "Кількість";
$MESS["CATALOG_QUANTITY_FROM_TO"] = "Від #FROM# до #TO#";
$MESS["CATALOG_QUANTITY_FROM"] = "Від #FROM#";
$MESS["CATALOG_QUANTITY_TO"] = "До #TO#";
$MESS["CT_BCS_QUANTITY"] = "Кількість";
$MESS["CT_BCS_ELEMENT_DELETE_CONFIRM"] = "Буде видалена вся інформація, пов'язана з цим записом. продовжити?";
$MESS["PODROBNEE"] = "Докладніше";
$MESS["REVIEW"] = "відгуків";

$MESS["FOR_ADMIN_ONLY"] = "Видно тільки адміністраторам:";
$MESS["SELLERS"] = "Продажі:";
$MESS["WEEK_CNT"] = "Хіти:";
$MESS["NEW_DAYS"] = "Новинка:";

$MESS["CHOOSE"] = "Вибрати";

global $is_bitronic;
if($is_bitronic)
{
	$MESS['RUB'] = "грн";
	$MESS['RUB_REPLACE'] = "грн";
}


if(!$MESS["FOR_ORDER"]) $MESS["FOR_ORDER"]	= 'під замовлення';
if(!$MESS["CATALOG_AVAILABLE"]) $MESS["CATALOG_AVAILABLE"] = "товар в наявності";
if(!$MESS["CATALOG_NOT_AVAILABLE"]) $MESS["CATALOG_NOT_AVAILABLE"] = "товару немає";

if(!$MESS["STICKER_NEW"]) $MESS["STICKER_NEW"] = "NEW";
if(!$MESS["STICKER_HIT"]) $MESS["STICKER_HIT"] = "HIT";
if(!$MESS["STICKER_BESTSELLER"]) $MESS["STICKER_BESTSELLER"] = "BEST SELLER";


?>