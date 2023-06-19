<?
if(\Bitrix\Main\Loader::includeModule('yenisite.worktime'))
{
	$APPLICATION->IncludeComponent(
		"yenisite:bitronic.worktime", 
		"bitronic2", 
		array(
			"COMPONENT_TEMPLATE" => "bitronic2",
			"LUNCH" => "Обед с 13:00 до 14:00",
			"CACHE_TYPE" => "A",
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
			"LUNCH_WEEKEND" => "Рабочее время в выходные – с 10 до 15 часов"
		),
		false
	);
}