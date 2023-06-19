<?
global $rz_b2_options;
$pf = '';
if ('Y' == $rz_b2_options['change_contacts']) {
	$pf = $rz_b2_options['GEOIP']['INCLUDE_POSTFIX'];
	if(!empty($pf)) {
		$pf = '_' . $pf;
	}
}
if (strlen($address = $APPLICATION->GetFileContent($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/footer/address' . $pf . '.php')) > 0): ?>
	<? $APPLICATION->IncludeComponent(
		"bitrix:map.yandex.view",
		"popup",
		array(
			"ADDRESS_STR" => $address,
			"COMPONENT_TEMPLATE" => ".default",
			"INIT_MAP_TYPE" => "MAP",
			"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:56.001068917913365;s:10:\"yandex_lon\";d:92.94698873051944;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:92.94707456120791;s:3:\"LAT\";d:56.00092162447842;s:4:\"TEXT\";s:17:\"2nd floor, office 2-10\";}}}",
			"MAP_WIDTH" => "600",
			"MAP_HEIGHT" => "500",
			"CONTROLS" => array(
				0 => "ZOOM",
				1 => "MINIMAP",
				2 => "TYPECONTROL",
				3 => "SCALELINE",
			),
			"OPTIONS" => array(
				0 => "ENABLE_SCROLL_ZOOM",
				1 => "ENABLE_DBLCLICK_ZOOM",
				2 => "ENABLE_DRAGGING",
			),
			"MAP_ID" => "address_modal_map"
		),
		false
	); ?>
<? endif ?>