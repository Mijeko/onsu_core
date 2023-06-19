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
	 * ��������� ������ �� ���������� �� #FILED_NAME#
	 * � ���������� FIELD_NAME � �������
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
	 * �������� ������ �� $ID �� �������� ������� $callable
	 * � ����������� $arArgs, ��������� ���� ������� �� $needField
	 * (�������� �������� ���������� ����� ����� �������)
	 * � ����������� �� ������ $link
	 * ������:
	 * GetHrefFromID(11 , array('CIBlockElement', 'GetByID'), array(), 'NAME', 'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#')
	 * �������� ������ ���� [<a>11</a>] ����������� �� ������ CIBlockElement::GetByID � ������� ��� ������ $arArgs ���������� ������ $ID
	 *   ��� ��� ���� �� ������ $link ������� ���� ���������� � #FIELD_NAME# � ������ � ��������� ���������� �������� ������
	 *
	 * @param $ID - ID
	 * @param callable $callable
	 * @param array $arArgs
	 * @param string $needField
	 * @param string $link
	 * @return string
	 */
	public static function GetHrefFromID($ID, $callable, $arArgs = array(), $needField = 'NAME', $link = '') {
		// ������� ���� �� � $link �������������� GET ���������
		$linkHasQ = (strpos($link, '?') !== false);
		$result = '';
		$content = '';
		//���� � ��� ��� ������ ���������� ��� ��������, ���������� ������ $ID
		if (empty($arArgs)) {
			$arArgs = array($ID);
		}
		// �������� ��� ����
		$hash = md5(serialize($callable) . serialize($arArgs));
		// ���� ���� � ���� ����� ������
		if (!empty(self::$arCache[$hash])) {
			$content = self::$arCache[$hash];
		} else { // ���� ��� � ���� - ��������
			$arResult = false;

			//�������� ��� �����
			$rs = call_user_func_array($callable, $arArgs);
			if ($rs) {
				if (is_array($rs)) {
					$arResult = $rs;
				} else if ($rs instanceof \CDBResult) { // ���� ������������ CDBResult ������ �� ��� ������
					$arResult = $rs->Fetch();
				}
				// ���� � ��� ���� ������ �����
				if(is_array($arResult) && count($arResult) > 0) {
					// �������� ## ��� ���� �� ������
					$arFieldsFromURL = self::GetFieldsFromUrl($link);
					if (!empty($arFieldsFromURL)) {
						foreach ($arFieldsFromURL as $key => $val) {
							// ���� � ���������� ���� ����� ����������
							if (!empty($arResult[$val])) {
								$arFieldsFromURL[$key] = $arResult[$val];
							} else { // ����� ������� �� �������
								unset($arFieldsFromURL[$key]);
							}
						}
						// �������� ���� ## ��� ���� �� ���������� ��������
						$link = str_replace(array_keys($arFieldsFromURL), array_values($arFieldsFromURL), $link);
					}
					// �������� ������ ������ �����
					$arNeed = explode(',', $needField);
					foreach ($arNeed as $fName) {
						$fName = trim($fName);
						// ���� � ��� ���� LOGIN �� ������������� ����������� ��� � (), ��� � ����������� ��������
						if ($fName == 'LOGIN') {
							$content .= '(' . $arResult[$fName] . ')';
						} else {
							$content .= ' ' . $arResult[$fName];
						}
					}
					$content = trim($content);
					// ���������� � ���
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
	 * ������� ��� ������ ����������� ����� ��� ������ � �������
	 *
	 * @param \CAdminListRow $row - ������
	 * @param string $fieldName - ��� ���� � ��������
	 * @param string | array $callable - ������ �������� �����
	 * @param array $arArgs - ���������
	 * @param string $needField - ����������� ����
	 * @param string $link - ������
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