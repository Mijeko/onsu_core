<?php
namespace Yenisite\Core\Orm;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class AdminHelperDetail
 * @package Yenisite\Core\Orm
 */
class AdminHelperDetail extends AdminHelper
{
	/**
	 * @var \CAdminForm
	 */
	protected $tabControl;
	/**
	 * @var \Bitrix\Main\Entity\DataManager
	 */
	protected $Entity;
	/**
	 * @var array
	 */
	protected $arCurVals;
	/**
	 * @var int
	 */
	protected $countRow = 0;

	/**
	 * @var array
	 */
	public $arMap = array();

	protected static $defaultFieldType = 'text';

	/**
	 * @param \CAdminForm $tabControl
	 * @param \Bitrix\Main\Entity\DataManager $Entity - наша сущность
	 * @param array $arCurVals - текущие значения
	 */
	final public function __construct(\CAdminForm $tabControl, $Entity, $arCurVals)
	{
		if (!method_exists($Entity, 'getFields')) {
			throw new \BadMethodCallException(GetMessage('YNS_CORE_ERROR_ENTITY_CLASS_HAS_NO_METHOD',
					array(
							'#CLASS#' => __CLASS__,
							'#METHOD#' => 'getFields',
					)));
		}
		if (!is_array($arCurVals)) {
			$arCurVals = array();
		}
		$this->tabControl = $tabControl;
		$this->Entity = $Entity;
		$this->arCurVals = $arCurVals;
		/** @noinspection PhpUndefinedMethodInspection */
		$this->arMap = $Entity->getFields();
	}

	/**
	 * Мета-метод для добавления поля в детальный просмотр админки
	 * @param string $fieldID
	 * @param array $arParams - additional parameter to be included in render function
	 */
	final public function AddField($fieldID, $arParams = array())
	{
		$curVal = null;
		// смотрим текущие значения
		if (isset($this->arCurVals[$fieldID])) {
			$curVal = $this->arCurVals[$fieldID];
		} elseif (isset($this->arMap[$fieldID]['default'])) { // если их нет тогда пытаемся брать default значение
			$curVal = $this->arMap[$fieldID]['default'];
		}
		// получаем "карту" поля нашей сущности
		$fieldMap = $this->arMap[$fieldID];
		// если карты нет, либо не заданы поля заголовка, типа ввода, маски редактирования, возвращаемся
		if (!isset($fieldMap) && (empty($fieldMap['title']) || empty($fieldMap['input']) || !$fieldMap['admin_edit'])) {
			return;
		}
		// поле скрытое
		$bHidden = false;
		// только просмотр
		$bShowOnly = true;

		// если админу запрещено редактировать - делаем поле скрытым
		if ($fieldMap['admin_edit'] === false || $fieldMap['hidden']) {
			$bHidden = true;
		}
		// если админу разрешено редактировать - снимаем флаг только просмотра
		if ($fieldMap['admin_edit'] & self::U_EDIT) {
			$bShowOnly = false;
		}
		$fieldMap['value'] = $curVal;
		$fieldMap['id'] = $fieldID;
		// вызываем суперметод для вывода
		$this->AddFieldExec($fieldMap['input'], $fieldMap, $bShowOnly, $bHidden, $arParams);
	}

	/**
	 * Метод пытается найти у экземпляра метод с Add . ИмяТипПоля . Field и вызвать его
	 *
	 * @param $type
	 * @param array $arField
	 * @param boolean $bShowOnly
	 * @param boolean $bHidden
	 * @param array $arParams
	 */
	final protected function AddFieldExec($type, array $arField, $bShowOnly, $bHidden, $arParams)
	{
		$type = str_replace(' ', '', strtolower(trim($type)));
		if (empty($type)) {
			$type = self::$defaultFieldType;
		}
		$type = ucwords($type);
		$methodName = 'Add' . $type . 'Field';
		if (is_callable(array($this, $methodName))) {
			$this->$methodName($arField, $bShowOnly, $bHidden, $arParams);
		} else {
			throw new \BadMethodCallException(GetMessage('YNS_CORE_ERROR_ADD_FIELD_EXEC_OBJECT_HAS_NO_METHOD_FOR',
							array(
									'#CLASS#' => __CLASS__,
									'#FIELD#' => $type,
									'#METHOD#' => $methodName,
							))
			);
		}
	}

	/**
	 * Типовое Скрытое поле
	 * @param $fieldID
	 * @param $value
	 * @param array $arParams
	 */
	protected function AddFieldHidden($fieldID, $value, $arParams = array())
	{
		$this->tabControl->tabs[$this->tabControl->tabIndex]["FIELDS"][$fieldID] = array(
				"id" => $fieldID,
				"required" => false,
				"content" => '',
				"html" => '',
				"hidden" => '<input type="hidden" name="' . $fieldID . '" value="' . $value . '">',
		);
	}

	/**
	 * Типовое поле только для просмотра
	 * @param array $arField
	 * @param array $arParams
	 */
	protected function AddFieldShowOnly(array $arField, $arParams = array())
	{
		$this->tabControl->AddViewField($arField['id'], $arField['title'], $arField['value'], $arField['required']);
	}

	/**
	 * Типовое поле для чекбокса
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddCheckboxField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%" class="adm-detail-content-cell-r">
					<div class="adm-list">
						<input type="hidden" name="<?= $arField['id'] ?>" value="N">
						<input type="checkbox" title="<?= $arField['title'] ?>" name="<?= $arField['id'] ?>" id="<?= $arField['id'] ?>" value="Y"<?= ($arField['value'] == 'Y') ? ' checked' : '' ?>/>
					</div>
				</td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	/**
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddPropertyField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%">
					<div class="adm-list">
						<input type="hidden" name="<?= $arField['id'] ?>[]" value=""/>
						<? foreach ($arField['values'] as $key => $val):
							if (is_array($arField['value'])) {
								$checked = in_array($val, $arField['value']);
							} else {
								$checked = $arField['value'] == $val;
							}
							$name = $arField['values_descr'][$key] ?>
							<div class="adm-list-item">
								<div class="adm-list-control">
									<input type="checkbox" name="<?= $arField['id'] ?>[]" id="<?= $arField['id'] ?>_<?= $val ?>" value="<?= $val ?>"<?= $checked ? ' checked' : '' ?>/>
								</div>
								<div class="adm-list-label">
									<label for="<?= $arField['id'] ?>_<?= $val ?>">[<?= $val ?>]&nbsp;<?= $name ?></label>
								</div>
							</div>
						<? endforeach ?>
					</div>
				</td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}

	}

	/**
	 * Типовое поле для селекта
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddSelectField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		$fieldMap = $this->arMap[$arField['id']];
		$arField['title'] .= ':';
		if (!$bShowOnly && !$bHidden) {
			$arSelect = array();
			if (!$arField['required']) {
				$arSelect['NULL'] = '--';
			}
			if (!empty($fieldMap['values_descr'])) {
				foreach ($arField['values_descr'] as $key => $value) {
					$arSelect[$fieldMap['values'][$key]] = '[' . $fieldMap['values'][$key] . '] ' . $value;
				}
			} else {
				foreach ($fieldMap['values'] as $key => $value) {
					$arSelect[$fieldMap['values'][$key]] = $value;
				}
			}
			$this->tabControl->AddDropDownField($arField['id'], $arField['title'], $arField['required'], $arSelect, $arField['value']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	/**
	 * Типовое поле для выбора даты\веремени
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddDateField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%"><?= /** @noinspection PhpDynamicAsStaticMethodCallInspection */
					\CAdminCalendar::CalendarDate($arField['id'], $arField['value'], 19, true) ?></td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id'], '<input type="hidden" id="' . $arField['id'] . '" name="' . $arField['id'] .
					'" value="' . $arField['value'] . '">');
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	/**
	 * Product select field
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddProductField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			if (!Loader::includeModule('iblock')) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				\CAdminMessage::ShowMessage(GetMessage('YNS_CORE_ERROR_MODULE_NOT_INSTALLED', array('#MODULE#' => 'iblock')));
			}
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%">
							<span id="show_<?= $arField['id'] ?>">
								<?= self::GetHrefFromID($arField['value'], array('CIBlockElement', 'GetByID'), array(), 'NAME',
										'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#'); ?>
							</span>
					&nbsp;<a href="javascript:;" class="adm-btn"
							 onclick="<?= $arField['id'] ?>_makeDialog()"><?= GetMessage('YNS_CORE_SELECT_BUTTON') ?></a>
					<input type="hidden" id="<?= $arField['id'] ?>" name="<?= $arField['id'] ?>" value="<?= $arField['value'] ?>">
					<script type="text/javascript">
						function <?= $arField['id'] ?>_makeDialog() {
							var caller = '<?= $this->tabControl->GetFormName()?>',
									lang = '<?= LANGUAGE_ID ?>',
									site_id = '',
									callback = '<?= $arField['id'] ?>_FillField',
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
							window.rz_popups.<?= $arField['id'] ?> = popup;
							BX.addCustomEvent(popup, 'onWindowRegister', BX.defer(function () {
								popup.Get().style.position = 'fixed';
								popup.Get().style.top = (parseInt(popup.Get().style.top) - BX.GetWindowScrollPos().scrollTop) + 'px';
							}));
							popup.Show();
						}
						function <?= $arField['id'] ?>_FillField(arParams) {
							BX('<?= $arField['id'] ?>').value = arParams['id'];
							BX('show_<?= $arField['id'] ?>').innerHTML = '[' + arParams['id'] + '] ' + arParams['name'];
							window.rz_popups.<?= $arField['id'] ?>.Close();
						}
					</script>
				</td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$string = self::GetHrefFromID($arField['value'], array('CIBlockElement', 'GetByID'), array(), 'NAME',
						'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#');
				$this->tabControl->AddViewField($arField['id'], $arField['title'] . ' :', $string, $arField['required']);
			}
		}
	}

	/**
	 * Section select field
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddSectionField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			if (!Loader::includeModule('iblock')) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				\CAdminMessage::ShowMessage(GetMessage('YNS_CORE_ERROR_MODULE_NOT_INSTALLED', array('#MODULE#' => 'iblock')));
			}
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%">
							<span id="show_<?= $arField['id'] ?>">
								<?= self::GetHrefFromID($arField['value'], array('CIBlockSection', 'GetByID'), array(), 'NAME',
										'iblock_section_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#'); ?>
							</span>
					&nbsp;<a href="javascript:;" class="adm-btn"
							 onclick="<?= $arField['id'] ?>_makeDialog()"><?= GetMessage('YNS_CORE_SELECT_BUTTON') ?></a>
					<input type="hidden" id="<?= $arField['id'] ?>" name="<?= $arField['id'] ?>" value="<?= $arField['value'] ?>">
					<?
					$caller = $this->tabControl->GetFormName() . $arField['id'] . '_FillField';
					?>
					<script type="text/javascript">
						function <?= $arField['id'] ?>_makeDialog() {
							var lang = '';
							if(typeof window.rz_popups == 'undefined') {
								window.rz_popups = {};
							}
							window.rz_popups.<?= $arField['id'] ?> = window.open('/bitrix/admin/cat_section_search.php?lang=<?= LANGUAGE_ID ?>&m=y&n=<?=$caller?>','choose category','width=850,height=600');
						}
						function InS<?=md5($caller) ?>(id, name) {
							BX('<?= $arField['id'] ?>').value = id;
							BX('show_<?= $arField['id'] ?>').innerHTML = '[' + id + '] ' + name;
							window.rz_popups.<?= $arField['id'] ?>.close();
						}
					</script>
				</td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$string = self::GetHrefFromID($arField['value'], array('CIBlockSection', 'GetByID'), array(), 'NAME',
						'iblock_section_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#');
				$this->tabControl->AddViewField($arField['id'], $arField['title'] . ' :', $string, $arField['required']);
			}
		}
	}

	/**
	 * Типовое поле для выбора скидки
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddDiscountField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$fieldMap = $this->arMap[$arField['id']];
			if (!Loader::includeModule('catalog')) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				\CAdminMessage::ShowMessage(GetMessage('YNS_CORE_ERROR_MODULE_NOT_INSTALLED', array('#MODULE#' => 'catalog')));
			}
			$arSelect = array();
			if (!$arField['required']) {
				$arSelect['NULL'] = GetMessage('YNS_CORE_FIELD_DISCOUNT_NEW');
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
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%">
					<select name="<?= $arField['id'] ?>" id="<?= $arField['id'] ?>" title="<?= $arField['title'] ?>"
							onchange="<?= $arField['id'] ?>_OnChange(this)">
						<? foreach ($arSelect as $key => $value): ?>
							<option value="<?= $key ?>"<?= ($arField['value'] == $key) ? ' selected' : '' ?>><?= $value ?></option>
						<? endforeach; ?>
					</select>
					<script type="text/javascript">
						function <?=$arField['id']?>_OnChange(field) {
							var val = field.value | 0;
							var $form = $(field).closest('form');
							if (val > 0) {
								$.ajax({
									url: '/bitrix/modules/yenisite.core/ajax/admin.php',
									type: 'GET',
									data: {'ACTION': 'GET_DISOUNT_BY_ID', 'ID': val},
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
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	/**
	 * Типовое поле для выбора пользователя
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddUserField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%">
					<span><?= self::GetHrefFromID($arField['value'], array('CIBlockElement', 'GetByID'), array(),
								'LOGIN, NAME, LAST_NAME', 'user_edit.php'); ?></span>
					<a href="javascript:;" class="adm-btn"><?= GetMessage('YNS_CORE_SELECT_BUTTON') ?></a>
				</td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id'], '<input type="hidden" id="' . $arField['id'] . '" name="' . $arField['id'] .
					'" value="' . $arField['value'] . '">');
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$string = self::GetHrefFromID($arField['value'], array('CUser', 'GetByID'), array(), 'LOGIN, NAME, LAST_NAME',
						'user_edit.php');
				$this->tabControl->AddViewField($arField['id'], $arField['title'] . ' :', $string, $arField['required']);
			}
		}
	}

	/**
	 * Типовое поле для текста
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddTextField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td width="60%"><input type="text" name="<?= $arField['id'] ?>" title="<?= $arField['title'] ?>" value="<?= $arField['value'] ?>"/></td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	/**
	 * Textarea Field
	 * @param array $arField
	 * @param $bShowOnly
	 * @param $bHidden
	 * @param array $arParams
	 */
	protected function AddTextareaField(array $arField, $bShowOnly, $bHidden, $arParams = array())
	{
		if (!$bShowOnly && !$bHidden) {
			$this->tabControl->AddTextField($arField['id'], $arField['title'], $arField['value'], array(), $arField['required']);
			$this->tabControl->BeginCustomField($arField['id'], $arField['title'], $arField['required']);
			$hint = $this->getHint($arField);
			?>
			<tr id="tr_<?= $arField['id'] ?>">
				<td width="40%"><?= $this->tabControl->GetCustomLabelHTML() ?><?= $hint ?>:</td>
				<td><textarea name="<?= $arField['id'] ?>" title="<?= $arField['title'] ?>"><?= $arField['value'] ?></textarea></td>
			</tr>
			<?
			$this->tabControl->EndCustomField($arField['id']);
		} else {
			if ($bHidden) {
				$this->AddFieldHidden($arField['id'], $arField['value'], $arParams);
			} else {
				$this->AddFieldShowOnly($arField, $arParams);
			}
		}
	}

	protected function getHint($arProp)
	{
		ob_start();
		if (!empty($arProp['hint'])):?>
			<span id="rz_hint_<?= $arProp['id'] ?>"></span>
			<script type="text/javascript">BX.hint_replace(BX('rz_hint_<?= $arProp['id'] ?>'), '<?= \CUtil::JSEscape(htmlspecialcharsbx($arProp['hint']))?>');</script>&nbsp;<?
		endif;
		$content = ob_get_clean();
		return trim($content);
	}
}