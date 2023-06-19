<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['STEP'] == 'PERSON_TYPE') {
	$first = false;
	$bChecked = false;
	foreach ($arResult[$arResult['STEP']] as &$res) {
		if (!is_array($res) || $res['INPUT']['TYPE'] !== 'RADIO') continue;
		if ($first === false) {
			$first = &$res;
		}
		if ($res['INPUT']["CHECKED"] == "Y") {
			$bChecked = true;
			break;
		}
	}
	if (isset($res)) {
		unset($res);
	}
	if (!$bChecked) {
		$first['INPUT']['CHECKED'] = 'Y';
	}
}
