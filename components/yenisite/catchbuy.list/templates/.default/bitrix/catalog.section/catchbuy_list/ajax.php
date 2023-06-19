<?
define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
if(!empty($_REQUEST['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = $_REQUEST['REQUEST_URI'];
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$bIsLite = false;
if (!CModule::IncludeModule('catalog') || !CModule::IncludeModule('sale')) {
	if (
		!function_exists('Add2BasketByProductID') && CModule::IncludeModule('yenisite.market')
	) {
		function Add2BasketByProductID($prodId, $q, $arProps = array()) {
			//check available quantity
			return !!CMarketBasket::Add($prodId, $arProps, $q);
		}
	} else {
		die();
	}
	$bIsLite = true;
}
?>
<?
$arResult = array(
	'success' => false,
	'msg' => ''
);

$arProps = array();
if (!empty($_REQUEST['PROP'])) {
	if (!CModule::IncludeModule('iblock')) die();
	if (!empty($_REQUEST['VARID'])) {
		$ID = intval($_REQUEST[$_REQUEST['VARID']]);
	} else {
		$ID = intval($_REQUEST['ID']);
	}
	$IBLOCK_ID = CIBlockElement::GetIBlockByID($ID);
	$rsProps = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $IBLOCK_ID));
	while ($ar = $rsProps->Fetch()) {
		if (isset($_REQUEST['PROP'][$ar['CODE']])) {
			if ($bIsLite) {
				$arProps[$ar['CODE']] = $_REQUEST['PROP'][$ar['CODE']];
			} else {
				$arProps[] = array(
					'NAME' => $ar['NAME'],
					'CODE' => $ar['CODE'],
					'VALUE' => $_REQUEST['PROP'][$ar['CODE']]
				);

			}
		}
	}
}


$q = intval($_REQUEST['Q']);
$result = Add2BasketByProductID($_REQUEST['id'], ($q > 0) ? $q : 1, $arProps);
if ($result !== false) {
	$arResult['success'] = true;
} else {
	$arResult['msg'] = 'error';
}
echo json_encode($arResult);
?>