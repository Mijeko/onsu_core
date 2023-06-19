<?
if (\Bitrix\Main\Loader::includeModule("yenisite.worktime")) {
	$APPLICATION->IncludeComponent(
		"yenisite:bitronic.worktime",
		"bitronic2",
		array(
			"COMPONENT_TEMPLATE" => "bitronic2",
			"LUNCH" => "Lunch from 13:00 to 14:00",
			"CACHE_TYPE" => "Y",
			"CACHE_TIME" => "360000",
			"MONDAY" => "Y",
			"TUESDAY" => "Y",
			"WEDNESDAY" => "Y",
			"THURSDAY" => "Y",
			"FRIDAY" => "Y",
			"SATURDAY" => "N",
			"SUNDAY" => "N",
			"TIME_WORK_FROM" => "08:30",
			"TIME_WORK_TO" => "18:00",
			"TIME_WEEKEND_FROM" => "10:00",
			"TIME_WEEKEND_TO" => "15:00",
			"LUNCH_WEEKEND" => "Work time on weekend from 10 to 15 hours"
		),
		false
	);
}
?>