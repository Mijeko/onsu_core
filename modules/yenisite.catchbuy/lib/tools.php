<?php
/*************************************
 ** @product shinmarket.loc        **
 ** @authors                        **
 **         Morozov P. Artem        **
 ** @license MIT                    **
 ** @mailto tashiro@ya.ru           **
 *************************************/

namespace Yenisite\Catchbuy;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

IncludeModuleLangFile(__FILE__);

class Tools {
	protected static $arCache;
	const U_EDIT = 1;
	const U_SHOW = 2;

	public static function GetFieldsFromUrl($string) {
		$arResult = array();
		$matches = array();
		preg_match_all("/#(.*?)#/", $string, $matches);
		foreach ($matches[1] as $key => $name) {
			$arResult[$matches[0][$key]] = $name;
		}
		return $arResult;
	}

	public static function GetHrefFromID($value, $callable, $arArgs = array(), $needField = 'NAME', $link = '') {
		$linkHasQ = (strpos($link, '?') !== false);
		$result = '';

		$content = '';
		if (empty($arArgs)) {
			$arArgs = array($value);
		}
		$hash = md5(serialize($callable) . serialize($arArgs));
		if (!empty(self::$arCache[$hash])) {
			$content = self::$arCache[$hash];
		} else {
			$rs = call_user_func_array($callable, $arArgs);
			if ($rs) {
				if (is_array($rs)) {
					$content = $rs[$needField];
				} else if ($rs instanceof \CDBResult) {
					if ($ar = $rs->Fetch()) {

						$arFieldsFromURL = self::GetFieldsFromUrl($link);
						if (!empty($arFieldsFromURL)) {
							foreach ($arFieldsFromURL as $key => $val) {
								if (!empty($ar[$val])) {
									$arFieldsFromURL[$key] = $ar[$val];
								} else {
									unset($arFieldsFromURL[$key]);
								}
							}
							$link = str_replace(array_keys($arFieldsFromURL), array_values($arFieldsFromURL), $link);
						}
						$arNeed = explode(',', $needField);
						foreach ($arNeed as $fName) {
							$fName = trim($fName);
							if ($fName == 'LOGIN') {
								$content .= '(' . $ar[$fName] . ')';
							} else {
								$content .= ' ' . $ar[$fName];
							}
						}
						$content = trim($content);
						self::$arCache[$hash] = $content;
					}
				}
			}
		}
		if (strlen($content) > 0) {
			$result = '[<a href="' . $link . (($linkHasQ) ? '&' : '?') .
				'lang=' . LANGUAGE_ID . '&ID=' . $value . '">' . $value . '</a>]' .
				'&nbsp;' . $content;
		}
		return $result;
	}

	/**
	 * @param \CAdminListRow $row
	 * @param string $fieldName
	 * @param string | array $callable
	 * @param array $arArgs
	 * @param string $needField
	 * @param string $link
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

	/**
	 * @var \CAdminForm
	 */
	private $tabControl;
	/**
	 * @var \Bitrix\Main\Entity\DataManager
	 */
	private $Entity;
	/**
	 * @var array
	 */
	private $arCurVals;
	/**
	 * @var int
	 */
	private $countRow = 0;

	private $arMap = array();

	/**
	 * @param \CAdminForm $tabControl
	 * @param \Bitrix\Main\Entity\DataManager $Entity
	 * @param array $arCurVals
	 * @return $this
	 */
	public function InitAdminHelper(\CAdminForm $tabControl, $Entity, $arCurVals) {
		global $APPLICATION;
		if (!method_exists($Entity, 'getFields')) {
			$APPLICATION->ThrowException(GetMessage('RZ_ENTITY_CLASS_HAS_NO_METHOD_GET_FIELDS'));
		}
		if (!is_array($arCurVals)) {
			$arCurVals = array();
		}
		$this->tabControl = $tabControl;
		$this->Entity = $Entity;
		$this->arCurVals = $arCurVals;
		$this->arMap = $Entity->getFields();
		return $this;
	}

	/**
	 * @param string $fieldID
	 * @param bool| string $content
	 * @param bool $bReset
	 */
	public function AddDetailField($fieldID, $content = false, $bReset = false) {
		if ($bReset) {
			$this->countRow = 0;
		}
		$curVal = NULL;
		if (isset($this->arCurVals[$fieldID])) {
			$curVal = $this->arCurVals[$fieldID];
		} elseif (isset($this->arMap[$fieldID]['default'])) {
			$curVal = $this->arMap[$fieldID]['default'];
		}
		$viewVal = $curVal;
		if ($content) {
			$viewVal = $curVal;
		}

		$fieldMap = $this->arMap[$fieldID];

		if (!isset($fieldMap) && (empty($fieldMap['title']) || empty($fieldMap['input']) || !$fieldMap['admin_edit'])) {
			return;
		}
		$tabControl = $this->tabControl;
		$bHidden = false;
		$bShowOnly = true;
		if ($fieldMap['admin_edit'] === false) {
			$bHidden = true;
		}
		if ($fieldMap['admin_edit'] & self::U_EDIT) {
			$bShowOnly = false;
		}

		if (!$bShowOnly) {
			switch ($fieldMap['input']) {
				case 'checkbox':
					$tabControl->AddCheckBoxField($fieldID, $fieldMap['title'] . ' :', $fieldMap['required'], array('Y', 'N'), ($curVal == 'Y'));
					break;
				case 'select':
					$arSelect = array();
					if (!$fieldMap['required']) {
						$arSelect['NULL'] = '--';
					}
					if (!empty($fieldMap['values_descr'])) {
						foreach ($fieldMap['values_descr'] as $key => $value) {
							$arSelect[$fieldMap['values'][$key]] = '[' . $fieldMap['values'][$key] . '] ' . $value;
						}
					} else {
						foreach ($fieldMap['values'] as $key => $value) {
							$arSelect[$fieldMap['values'][$key]] = $value;
						}
					}
					$tabControl->AddDropDownField($fieldID, $fieldMap['title'] . ':', $fieldMap['required'], $arSelect, $curVal);
					break;
				case 'date':
					$tabControl->BeginCustomField($fieldID, $fieldMap['title'], $fieldMap['required']);
					?>
					<tr id="tr_<?= $fieldID ?>">
						<td><?= $tabControl->GetCustomLabelHTML() ?>:</td>
						<td><?= \CAdminCalendar::CalendarDate($fieldID, $curVal, 19, true) ?></td>
					</tr>
					<?
					$tabControl->EndCustomField($fieldID, '<input type="hidden" id="' . $fieldID . '" name="' . $fieldID . '" value="' . $curVal . '">');
					break;
				case 'product':
					if (!Loader::includeModule('iblock')) {
						\CAdminMessage::ShowMessage(GetMessage('RZ_ERROR_MODULE_IBLOCK_NOT_INSTALLED'));
					}
					$tabControl->BeginCustomField($fieldID, $fieldMap['title'], $fieldMap['required']);
					?>
					<tr id="tr_<?= $fieldID ?>">
						<td><?= $tabControl->GetCustomLabelHTML() ?>:</td>
						<td>
							<span id="show_<?= $fieldID ?>">
								<?= self::GetHrefFromID($curVal, array('CIBlockElement', 'GetByID'), array(), 'NAME',
									'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#'); ?>
							</span>
							&nbsp;<a href="javascript:;" class="adm-btn"
									 onclick="<?= $fieldID ?>_makeDialog()"><?= GetMessage('RZ_SELECT_BUTTON') ?></a>
							<input type="hidden" id="<?= $fieldID ?>" name="<?= $fieldID ?>" value="<?= $curVal ?>">
							<script type="text/javascript">
								function <?=$fieldID?>_makeDialog() {
									var caller = '<?= $tabControl->GetFormName()?>',
										lang = '<?= LANGUAGE_ID ?>',
										site_id = '',
										callback = '<?= $fieldID ?>_FillField',
										store_id = '0';
									var popup = new BX.CDialog({
										content_url: '/bitrix/admin/cat_product_search_dialog.php?lang='
										+ lang + '&LID=' + site_id + '&caller=' + caller + '&func_name=' + callback + '&STORE_FROM_ID=' + store_id,
										height: Math.max(500, window.innerHeight - 400),
										width: Math.max(800, window.innerWidth - 400),
										draggable: true,
										resizable: true,
										min_height: 500,
										min_width: 800
									});
									if (typeof window.rz_popups != 'object') {
										window.rz_popups = {};
									}
									window.rz_popups.<?= $fieldID ?> = popup;
									BX.addCustomEvent(popup, 'onWindowRegister', BX.defer(function () {
										popup.Get().style.position = 'fixed';
										popup.Get().style.top = (parseInt(popup.Get().style.top) - BX.GetWindowScrollPos().scrollTop) + 'px';
									}));
									popup.Show();
								}
								function <?=$fieldID?>_FillField(arParams, iblockID) {
									BX('<?=$fieldID?>').value = arParams['id'];
									BX('show_<?=$fieldID?>').innerHTML = '[' + arParams['id'] + '] ' + arParams['name'];
									window.rz_popups.<?= $fieldID ?>.Close();
								}
							</script>
						</td>
					</tr>
					<?
					$tabControl->EndCustomField($fieldID);
					break;
				case 'discount':
					if (!Loader::includeModule('catalog')) {
						\CAdminMessage::ShowMessage(GetMessage('RZ_ERROR_MODULE_CATALOG_NOT_INSTALLED'));
					}
					$arSelect = array();
					if (!$fieldMap['required']) {
						$arSelect['NULL'] = GetMessage('RZ_FIELD_DISCOUNT_NEW');
					}
					if (!empty($fieldMap['values_descr'])) {
						foreach ($fieldMap['values_descr'] as $key => $value) {
							$arSelect[$fieldMap['values'][$key]] = '[' . $fieldMap['values'][$key] . '] ' . $value;
						}
					} else {
						foreach ($fieldMap['values'] as $key => $value) {
							$arSelect[$fieldMap['values'][$key]] = $value;
						}
					}
					$tabControl->BeginCustomField($fieldID, $fieldMap['title'], $fieldMap['required']);
					?>
					<tr id="tr_<?= $fieldID ?>">
						<td><?= $tabControl->GetCustomLabelHTML() ?>:</td>
						<td>
							<select name="<?= $fieldID ?>" id="<?= $fieldID ?>" title="<?= $fieldMap['title'] ?>"
									onchange="<?= $fieldID ?>_OnChange(this)">
								<? foreach ($arSelect as $key => $value): ?>
									<option value="<?= $key ?>"<?= ($curVal == $key) ? ' selected' : '' ?>><?= $value ?></option>
								<? endforeach;?>
							</select>
							<script type="text/javascript">
								function <?=$fieldID?>_OnChange(field) {
									var val = field.value | 0;
									var $form = $(field).closest('form');
									if (val > 0) {
										$.ajax({
											url: 'catchbuy_discount.php',
											type: 'GET',
											data: {'ID': val},
											dataType: 'json',
											success: function (result) {
												if (result.success) {
													var arResult = result.msg;
													for (var key in arResult) {
														var $elem = $form.find('[name="DISCOUNT[' + key + ']"]');
														if ($elem.length > 0) {
															$elem.val(arResult[key]);
														}
													}
												}
											}
										});
									} else {
										$form.find('[name^=DISCOUNT_]').val('');
									}
								}
							</script>
						</td>
					</tr>
					<?
					$tabControl->EndCustomField($fieldID);
					break;
				case 'user':
					$tabControl->BeginCustomField($fieldID, $fieldMap['title'], $fieldMap['required']);
					?>
					<tr id="tr_<?= $fieldID ?>">
						<td><?= $tabControl->GetCustomLabelHTML() ?>:</td>
						<td>
							<span><?= self::GetHrefFromID($curVal, array('CIBlockElement', 'GetByID'), array(), 'LOGIN, NAME, LAST_NAME', 'user_edit.php'); ?></span>
							<a href="javascript:;" class="adm-btn"><?= GetMessage('RZ_SELECT_BUTTON') ?></a>
						</td>
					</tr>
					<?
					$tabControl->EndCustomField($fieldID, '<input type="hidden" id="' . $fieldID . '" name="' . $fieldID . '" value="' . $curVal . '">');
					break;
				case 'text':
				default:
					$tabControl->AddEditField($fieldID, $fieldMap['title'] . ' :', $fieldMap['required'], array(), $curVal);
			}
		} else {
			if (!$bHidden) {
				switch ($fieldMap['input']) {
					case 'product':
						$string = self::GetHrefFromID($curVal, array('CIBlockElement', 'GetByID'), array(), 'NAME',
							'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#');
						$tabControl->AddViewField($fieldID, $fieldMap['title'] . ' :', $string, $fieldMap['required']);
						break;
					case 'user':
						$string = self::GetHrefFromID($curVal, array('CUser', 'GetByID'), array(), 'LOGIN, NAME, LAST_NAME', 'user_edit.php');
						$tabControl->AddViewField($fieldID, $fieldMap['title'] . ' :', $string, $fieldMap['required']);
						break;
					default:
						$tabControl->AddViewField($fieldID, $fieldMap['title'] . ' :', $viewVal, $fieldMap['required']);
				}
			} else {
				$tabControl->tabs[$tabControl->tabIndex]["FIELDS"][$fieldID] = array(
					"id" => $fieldID,
					"required" => false,
					"content" => '',
					"html" => '',
					"hidden" => '<input type="hidden" name="' . $fieldID . '" value="' . $curVal . '">',
				);
			}
		}
		++$this->countRow;
	}
}