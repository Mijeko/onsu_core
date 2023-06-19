<?php
namespace Yenisite\Core\Orm;

/**
 * Class AdminHelper
 * @package Yenisite\Core\Orm
 */
class AdminHelper {
	protected static $arCache;
	const U_EDIT = 1;
	const U_SHOW = 2;

	/**
	 * разбивает строку на переменные по #FILED_NAME#
	 * и возвращает FIELD_NAME в массиве
	 * @param $string
	 * @return array
	 */
	public static function GetFieldsFromUrl($string) {
		$arResult = array();
		$matches = array();
		preg_match_all("/#(.*?)#/", $string, $matches);
		foreach ($matches[1] as $key => $name) {
			$arResult[$matches[0][$key]] = $name;
		}
		return $arResult;
	}

	/**
	 * ѕолучает ссылку по $ID из сущности методом $callable
	 * с аргументами $arArgs, получаемы пол€ берутс€ из $needField
	 * (возможно указание нескольких полей через зап€тую)
	 * и формируетс€ по ссылке $link
	 * пример:
	 * GetHrefFromID(11 , array('CIBlockElement', 'GetByID'), array(), 'NAME', 'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#')
	 * получает ссылку вида [<a>11</a>] »м€Ёлемента из метода CIBlockElement::GetByID в который при пустом $arArgs передаетс€ только $ID
	 *   еще при этом из ссылки $link берутс€ пол€ выделенные в #FIELD_NAME# и ищютс€ в резульате выполнени€ текущего метода
	 *
	 * @param $ID - ID
	 * @param callable $callable
	 * @param array $arArgs
	 * @param string $needField
	 * @param string $link
	 * @return string
	 */
	public static function GetHrefFromID($ID, $callable, $arArgs = array(), $needField = 'NAME', $link = '') {
		// смотрим есть ли в $link дополнительные GET параметры
		$linkHasQ = (strpos($link, '?') !== false);
		$result = '';
		$content = '';
		//если у нас нет особых аргументов дл€ передачи, закидываем только $ID
		if (empty($arArgs)) {
			$arArgs = array($ID);
		}
		// хэшируем дл€ кеша
		$hash = md5(serialize($callable) . serialize($arArgs));
		// если есть в кеше берем оттуда
		if (!empty(self::$arCache[$hash])) {
			$content = self::$arCache[$hash];
		} else { // если нет в кеше - получаем
			$arResult = false;

			//вызываем наш метод
			$rs = call_user_func_array($callable, $arArgs);
			if ($rs) {
				if (is_array($rs)) {
					$arResult = $rs;
				} else if ($rs instanceof \CDBResult) { // если возвращаетс€ CDBResult объект мы его фетчим
					$arResult = $rs->Fetch();
				}
				// если у нас есть данные тогда
				if(is_array($arResult) && count($arResult) > 0) {
					// получаем ## доп пол€ из ссылки
					$arFieldsFromURL = self::GetFieldsFromUrl($link);
					if (!empty($arFieldsFromURL)) {
						foreach ($arFieldsFromURL as $key => $val) {
							// если в результате есть тогда записываем
							if (!empty($arResult[$val])) {
								$arFieldsFromURL[$key] = $arResult[$val];
							} else { // иначе убираем из массива
								unset($arFieldsFromURL[$key]);
							}
						}
						// замен€ем наши ## доп пол€ на полученные значение
						$link = str_replace(array_keys($arFieldsFromURL), array_values($arFieldsFromURL), $link);
					}
					// получаем массив нужных полей
					$arNeed = explode(',', $needField);
					foreach ($arNeed as $fName) {
						$fName = trim($fName);
						// если у нас поле LOGIN то дополнительно оборачиваем его в (), как в стандартном Ѕитриксе
						if ($fName == 'LOGIN') {
							$content .= '(' . $arResult[$fName] . ')';
						} else {
							$content .= ' ' . $arResult[$fName];
						}
					}
					$content = trim($content);
					// отправл€ем в кеш
					self::$arCache[$hash] = $content;
				}
			}
		}
		if (strlen($content) > 0) {
			$result = '[<a href="' . $link . (($linkHasQ) ? '&' : '?') .
				'lang=' . LANGUAGE_ID . '&ID=' . $ID . '">' . $ID . '</a>]' .
				'&nbsp;' . $content;
		}
		return $result;
	}

	/**
	 * ‘ункци€ дл€ вывода линкованных полей дл€ списка в админке
	 *
	 * @param \CAdminListRow $row - строка
	 * @param string $fieldName - »м€ пол€ в сущности
	 * @param string | array $callable - откуда получаем даные
	 * @param array $arArgs - агрументы
	 * @param string $needField - необходимые пол€
	 * @param string $link - ссылка
	 * @return bool
	 */
	public static function LinkListProp(&$row, $fieldName, $callable, $arArgs = array(), $needField = 'NAME', $link = '') {
		$arRes = $row->arRes;
		if (empty($arRes[$fieldName])) return false;
		if (intval($arRes[$fieldName]) == $arRes[$fieldName]) {
			if ($arRes[$fieldName] == 0) return false;
		}
		$resLink = self::GetHrefFromID($arRes[$fieldName], $callable, $arArgs, $needField, $link);
		if (strlen($resLink) > 0) {
			$row->AddViewField($fieldName, $resLink);
			return true;
		}
		return false;
	}
}