<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global array $FIELDS */
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Yenisite\Core\Tools;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

if (Loader::includeModule('yenisite.core')){
    $dirBitronic = Tools::findNeedDir('yenisite.bitronic2', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules') ;
    $dirBitronic = $dirBitronic ? : Tools::findNeedDir('yenisite.b2', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules') ;
}

require_once(__DIR__ . '/../../'.$dirBitronic.'/constants.php');
if (!defined('RZ_B2_MODULE_FULL_NAME')) {
	throw new ErrorException('Need RZ_B2_MODULE_FULL_NAME constant must be defined!');
};

Loc::loadMessages(__FILE__);

// Check rights
$POST_RIGHT = $APPLICATION->GetGroupRight(RZ_B2_MODULE_FULL_NAME);
if ($POST_RIGHT == 'D')
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
?>
<?
if (!Loader::includeModule(RZ_B2_MODULE_FULL_NAME)) {
	die('Module ' . RZ_B2_MODULE_FULL_NAME . ' must be installed');
}
if (!Loader::includeModule('yenisite.core')) {
	die('Module yenisite.core must be installed');
}
if (!Loader::includeModule('catalog')) {
	die('Module catalog must be installed');
}
if (!Loader::includeModule('iblock')) {
	die('Module iblock must be installed');
}
$isAjax = Tools::isAjax();
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("REINDEX_TAB"), "ICON" => "main_user_edit", "TITLE" => GetMessage("REINDEX_TAB_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);
?>
<? if (!$isAjax): ?>
	<?
	CJSCore::init(array('jquery'));
	$APPLICATION->SetTitle(GetMessage("PAGE_TITLE"));
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
	?>
<? elseif ($isAjax && isset($_REQUEST['submit'])):
	@set_time_limit(0);
	$arFilter = array(
		'IBLOCK_ID' => $_REQUEST['IBLOCK_ID'],
	);

	// to fix before 2.9.0 prop type
	$rsProp = CIBlockProperty::GetByID('RZ_AVAILABLE', $_REQUEST['IBLOCK_ID']);
	if ($arProp = $rsProp->GetNext(false, false)) {
		if ($arProp['PROPERTY_TYPE'] != 'L') {
			CIBlockProperty::Delete($arProp['ID']);
		}
	}

	if ((int)$_REQUEST['SECTION_ID'] > 0) {
		$arFilter['SECTION_ID'] = $_REQUEST['SECTION_ID'];
		$arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
	}
	$arItems = array();
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rs = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID'));
	while ($ar = $rs->GetNext()) {
		$arItems[] = $ar['ID'];
	}
	if(empty($arItems))
	{
		CAdminMessage::ShowMessage(array(
			'MESSAGE' => GetMessage('REINDEX_PROCESS_ERROR'),
			'DETAILS' => GetMessage('REINDEX_PROCESS_ERROR_EMPTY'),
			'TYPE' => 'ERROR',
			'HTML' => true
		));
		die();
	}
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rs = CCatalogProduct::GetList(array(), array('ID' => $arItems));
	$rsIblock = CIBlockElement::GetList(array(), array('ID' => $arItems));
	$arElementsActive = array();
	while ($arIblockElements = $rsIblock->GetNext()){
        $arElementsActive[$arIblockElements['ID']] = $arIblockElements['ACTIVE'];
    }
	$bError = false;
	$isPro = CRZBitronic2Settings::isPro($withGeoip = true);

	$arEvents = array();
	foreach (GetModuleEvents("catalog", "OnPriceUpdate", true) as $arEvent) {
		if ($arEvent['TO_CLASS'] != 'CRZBitronic2Handlers') continue;
		if ($arEvent['TO_METHOD'] != 'OnPriceChangeCheckOnRequest') continue;
		$arEvents[] = $arEvent;
	}

	$arPriceIDs = array();
	$rsPriceTypes = CCatalogGroup::GetList();
	while ($arPriceType = $rsPriceTypes->Fetch()) {
		$arPriceIDs[$arPriceType['ID']] = $arPriceType['ID'];
	}

	$arPrice = array('VALUE' => '1');
	while ($ar = $rs->GetNext(false, false)) {
		if (!\Yenisite\Core\Events\Catalog::SetAvailableStatus(
			$ar['ID'], $ar, array(), 'RZ_AVAILABLE', null, $isPro
		)) {
			$bError = true;
		}
        if (!\Yenisite\Core\Events\Catalog::updateElements($ar,$arElementsActive)){
            $bError = true;
        }
		$arPrice['PRODUCT_ID'] = $ar['ID'];
		foreach ($arPriceIDs as &$priceTypeId) {
			$arPrice['CATALOG_GROUP_ID'] = $priceTypeId;
			foreach ($arEvents as &$arEvent) {
				ExecuteModuleEventEx($arEvent, array(0, &$arPrice));
			}
		}
	}

	$rs = CCatalogStoreProduct::GetList(array(),
		array('PRODUCT_ID' => $arItems),
		false,
		false,
		array('PRODUCT_ID', 'STORE_ID', 'AMOUNT')
	);

	while ($ar = $rs->GetNext(false, false)) {
		if (!\Yenisite\Core\Events\Catalog::StoreInProperties(null, $ar)) {
			$bError = true;
		}
	}

	if (!$bError) {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		CAdminMessage::ShowNote(GetMessage('REINDEX_COMPLETE'));
		CAdminNotify::DeleteByTag('RZ_BITRONIC2_REINDEX_MSG');
	} else {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		CAdminMessage::ShowMessage(array(
			'MESSAGE' => GetMessage('REINDEX_PROCESS_ERROR'),
			'DETAILS' => GetMessage('REINDEX_PROCESS_ERROR_DETAIL'),
			'TYPE' => 'ERROR',
			'HTML' => true
		));
	}
	die();
endif ?>
<? if (!$isAjax): ?>
	<form method="POST" name="b2_reindex_avail" id="b2_reindex_avail"
		  action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= htmlspecialcharsbx(LANG) ?>">
		<? endif ?>
		<?= BeginNote(), GetMessage("REINDEX_HARD_WORK"), EndNote(); ?>
		<?
		$tabControl->Begin();
		$tabControl->BeginNextTab();

		$IBLOCK_ID = (int)$_REQUEST['IBLOCK_ID'];
		?>
		<tr>
			<td>
				<label for="IBLOCK_ID"><?= GetMessage("REINDEX_IBLOCK_ID") ?>:</label></td>
			<td>
				<select name="IBLOCK_ID" id="IBLOCK_ID">
					<option value="0"><?= GetMessage("REINDEX_IBLOCK_ID_CHOOSE") ?></option>
					<?
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$rs = CCatalog::getList();
					while ($ar = $rs->GetNext(false, false)):?>
						<option <?= ($IBLOCK_ID == $ar['IBLOCK_ID']) ? 'selected' : '' ?>
							value="<?= $ar['IBLOCK_ID'] ?>"><?= $ar['NAME'] ?></option>
					<? endwhile ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="SECTION_ID"><?= GetMessage("REINDEX_SECTION_ID") ?>:</label></td>
			<td>
				<?
				$bIB = $IBLOCK_ID > 0;
				$arSections = array();
				if ($bIB) {
					$rs = CIBlockSection::GetTreeList(array('IBLOCK_ID' => $_REQUEST['IBLOCK_ID']));
					while ($ar = $rs->GetNext()) {
						$arSections[] = $ar;
					}
				}
				?>
				<select name="SECTION_ID"
						id="SECTION_ID" <? if ($bIB) : ?> multiple <?= (count($arSections) > 10) ? 'size="15"' : 'size="3"' ?><? else: ?> disabled <? endif ?>>
					<? if (!$bIB): ?>
						<option value="0"><?= GetMessage('REINDEX_SECTION_ID_CHOOSE_IBLOCK_FIRST') ?></option>
					<? else: ?>
						<option value="ALL"><?= GetMessage('REINDEX_SECTION_ID_CHOOSE_ALL') ?></option>
						<?
						foreach ($arSections as $ar):?>
							<option value="<?= $ar['ID'] ?>"><?= str_repeat('. ', $ar['DEPTH_LEVEL']), $ar['NAME'] ?></option>
						<? endforeach ?>
					<? endif ?>
				</select>
			</td>
		</tr>
		<?
		$tabControl->Buttons();
		?>
		<input type="submit" id="start_button" value="<?= GetMessage("REINDEX_BTN_START") ?>" class="adm-btn-save">
		<?
		$tabControl->End();
		?>
		<? if (!$isAjax): ?>
	</form>
	<script type="text/javascript">
		if (typeof(jQuery) == 'undefined') jQuery = false;
		(function ($) {
			var $doc = $(document);
			var $form = $('#b2_reindex_avail');

			var sendForm = function (add_data) {
				ShowWaitWindow();
				var data = $form.serializeArray();
				if (!$.isArray(add_data)) {
					add_data = [];
				}
				data = $.merge(data, add_data);
				$.ajax({
					url: $form.attr('action'),
					type: 'POST',
					data: data,
					success: function (msg) {
						$form.html(msg);
						CloseWaitWindow();
					}
				});
			};

			$form.on('submit', function (e) {
				e.preventDefault();
				sendForm([{'name': 'submit', 'value': '1'}]);
				return false;
			});

			$doc.on('change', '#IBLOCK_ID', function () {
				sendForm();
			})
		})(jQuery);
	</script>
<? endif ?>
<?
if ($isAjax) die();
?>
<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php'); ?>