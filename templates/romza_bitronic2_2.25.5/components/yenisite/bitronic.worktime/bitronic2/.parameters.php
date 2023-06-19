<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$arDays = array('MONDAY' => GetMessage('MONDAY'), 'TUESDAY' => GetMessage('TUESDAY'), 'WEDNESDAY' => GetMessage('WEDNESDAY'), 'THURSDAY' => GetMessage('THURSDAY'), 'FRIDAY' => GetMessage('FRIDAY'),'SATURDAY' => GetMessage('SATURDAY'), 'SUNDAY' => GetMessage('SUNDAY'));

$arTemplateParameters['LUNCH_WEEKEND'] = array(
	'PARENT' => 'BASE',
	'NAME' => GetMessage('LUNCH_WEEKEND'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => GetMessage('LUNCH_WEEKEND_DEF'),
);

$arTemplateParameters['TIME_WORK_FROM'] = array(
	'PARENT' => 'BASE',
	'NAME' => GetMessage('TIME_WORK_FROM'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => '08:00',
);
$arTemplateParameters['TIME_WORK_TO'] = array(
	'PARENT' => 'BASE',
	'NAME' => GetMessage('TIME_WORK_TO'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => '18:00',
);
$arTemplateParameters['TIME_WEEKEND_FROM'] = array(
	'PARENT' => 'BASE',
	'NAME' => GetMessage('TIME_WEEKEND_FROM'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => '10:00',
);
$arTemplateParameters['TIME_WEEKEND_TO'] = array(
	'PARENT' => 'BASE',
	'NAME' => GetMessage('TIME_WEEKEND_TO'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => '15:00',
);
$arTemplateParameters['HALF_DAYS'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('HALF_DAYS'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'Y',
    'VALUES' => $arDays,
    'DEFAULT' => 'SATURDAY',
);
$arTemplateParameters['LUNCH_HALF_DAY'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('LUNCH_HALF_DAY'),
    'TYPE' => 'STRING',
    'MULTIPLE' => 'N',
    'DEFAULT' => GetMessage('LUNCH_HALF_DAY_DEF'),
);
$arTemplateParameters['TIME_NOT_FULL_DAY_FROM'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('TIME_NOT_FULL_DAY_FROM'),
    'TYPE' => 'STRING',
    'MULTIPLE' => 'N',
    'DEFAULT' => '10:00',
);
$arTemplateParameters['TIME_NOT_FULL_DAY_TO'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('TIME_NOT_FULL_DAY_TO'),
    'TYPE' => 'STRING',
    'MULTIPLE' => 'N',
    'DEFAULT' => '13:00',
);




$arTemplateParameters["LUNCH"]['NAME'] = GetMessage('LUNCH');
$arTemplateParameters["LUNCH"]['PARENT'] = 'BASE';
$arTemplateParameters["TIME_WORK"]['HIDDEN'] = 'Y';
$arTemplateParameters["TIME_WEEKEND"]['HIDDEN'] = 'Y';