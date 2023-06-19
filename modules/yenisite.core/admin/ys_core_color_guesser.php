<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global array $FIELDS */
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager as BxMM;
use Yenisite\Core\ColorGuess;
use Yenisite\Core\Tools;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Loc::loadMessages(__FILE__);

// Check rights
$POST_RIGHT = $APPLICATION->GetGroupRight('yenisite.core');
if(
	!BxMM::isModuleInstalled('yenisite.bitronic2pro')/* &&
	!BxMM::isModuleInstalled('yenisite.shinmarketpro') &&
	!BxMM::isModuleInstalled('yenisite.apparelpro')*/
) {
	$POST_RIGHT = 'D';
}
if ($POST_RIGHT == 'D')
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
?>
<?
if (!Loader::includeModule('yenisite.core')) {
	die('Module yenisite.core must be installed');
}
if (!Loader::includeModule('highloadblock')) {
	die('Module highloadblock must be installed');
}
if (!Loader::includeModule('iblock')) {
	die('Module iblock must be installed');
}
$isAjax = Tools::isAjax();
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("REINDEX_TAB"), "ICON" => "main_user_edit", "TITLE" => GetMessage("REINDEX_TAB_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);

$arErrors = array();
$arSettings = unserialize(COption::GetOptionString('yenisite.core', 'color_setts', 'a:0:{}'));
if (!is_array($arSettings)) $arSettings = array();

if (isset($arSettings['reference'])) {
	$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
		"filter" => array(
			"=ID" => $arSettings['reference'],
		)))->fetch();
	unset($arSettings['reference']);
}
if (count($arSettings) < 1 || !$hlblock) {
	$arErrors[] = array(
		'MESSAGE' => GetMessage('GUESSER_ERROR_EMPTY_SETTINGS'),
		'DETAILS' => GetMessage('GUESSER_ERROR_EMPTY_SETTINGS_DETAILS')
	);
}

$FREQUENCY_ORDER = intval($_REQUEST['FREQUENCY_ORDER']);
$IBLOCK_ID = intval($_REQUEST['IBLOCK_ID']);
$SECTION_ID = intval($_REQUEST['SECTION_ID']);

do {
	if (!$isAjax) {
		CJSCore::init(array('jquery'));
		$APPLICATION->SetTitle(GetMessage("PAGE_TITLE"));
		require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
		break;
	}
	if (!isset($_REQUEST['submit'])) break;

	@set_time_limit(0);
	$arFilter = array(
		'IBLOCK_ID' => $IBLOCK_ID
	);

	if ($SECTION_ID > 0) {
		$arFilter['SECTION_ID'] = $SECTION_ID;
		$arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
	}
	$arItems = array();
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rs = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID', 'IBLOCK_ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE'));
	while ($ar = $rs->GetNext()) {
		$arItems[$ar['ID']] = $ar;
	}
	if(empty($arItems))
	{
		CAdminMessage::ShowMessage(array(
			'MESSAGE' => GetMessage('REINDEX_PROCESS_ERROR'),
			'DETAILS' => GetMessage('REINDEX_PROCESS_ERROR_EMPTY'),
			'TYPE' => 'ERROR',
			'HTML' => true
		));
		break;
	}
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$bError = false;

	if (empty($arSettings[$IBLOCK_ID])) {
		$bError = true;
	} else {
		$obGuesser = new ColorGuess($hlblock['ID']);
		$obGuesser->handleIblock($IBLOCK_ID, $arSettings[$IBLOCK_ID]);

		if ($FREQUENCY_ORDER < 1 || $FREQUENCY_ORDER > 3) {
			$FREQUENCY_ORDER = 1;
		}

		$guessCount = 0;
		foreach ($arItems as $elementId => $arFields) {
			if ($obGuesser->guessColorForElement($arFields, $FREQUENCY_ORDER)) $guessCount++;
		}
	}

	if (!$bError) {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		CAdminMessage::ShowMessage(array(
			'MESSAGE' => GetMessage('GUESS_COMPLETE'),
			'DETAILS' => GetMessage('GUESS_RESULT', array('#COUNT#' => $guessCount, '#TOTAL#' => count($arItems))),
			'TYPE' => 'OK',
			'HTML' => true
		));
		CIBlock::clearIblockTagCache($IBLOCK_ID);
	} else {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		CAdminMessage::ShowMessage(array(
			'MESSAGE' => GetMessage('REINDEX_PROCESS_ERROR'),
			'DETAILS' => GetMessage('REINDEX_PROCESS_ERROR_DETAIL'),
			'TYPE' => 'ERROR',
			'HTML' => true
		));
	}
} while (0);
?>
<? if (!$isAjax): ?>
	<form method="POST" name="core_color_guess_form" id="core_color_guess_form"
		  action="<?= $APPLICATION->GetCurPage() ?>?lang=<?= htmlspecialcharsbx(LANG) ?>">
<? endif ?>
		<?= BeginNote(), GetMessage("REINDEX_HARD_WORK"), EndNote(); ?>
		<?
		foreach ($arErrors as $arError) {
			CAdminMessage::ShowMessage(array(
				'MESSAGE' => $arError['MESSAGE'],
				'DETAILS' => $arError['DETAILS'],
				'TYPE' => 'ERROR',
				'HTML' => true
			));
		}
		?>
		<?
		$tabControl->Begin();
		$tabControl->BeginNextTab();
		?>
		<tr>
			<td><?= GetMessage('GUESSER_HLBLOCK') ?>:</td>
			<td>
				<?if($hlblock):?><a href="<?=BX_ROOT?>/admin/highloadblock_rows_list.php?ENTITY_ID=<?=$hlblock['ID']?>"><?=$hlblock['NAME']?></a><?else:?><?=GetMessage('GUESSER_HLBLOCK_NOTSET')?>.<?endif?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="FREQUENCY_ORDER"><?=GetMessage('GUESSER_FREQUENCY')?>:</label>
			</td>
			<td>
				<select name="FREQUENCY_ORDER" id="FREQUENCY_ORDER">
<? for ($i=1; $i<4; $i++): ?>
					<option value="<?=$i, ($i==$FREQUENCY_ORDER?'" selected="selected':'')?>"><?=GetMessage('GUESSER_FREQUENCY_'.$i)?></option>
<? endfor ?>
				</select>
				&mdash; <?=GetMessage('GUESSER_FREQUENCY_NOTE')?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><small id="color-guess-frequency-info"><?=GetMessage('GUESSER_FREQUENCY_INFO')?></small></td>
		</tr>
		<tr>
			<td>
				<label for="IBLOCK_ID"><?= GetMessage("REINDEX_IBLOCK_ID") ?>:</label></td>
			<td>
				<select name="IBLOCK_ID" id="IBLOCK_ID">
					<option value="0"><?= GetMessage("REINDEX_IBLOCK_ID_CHOOSE") ?></option>
					<?
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$rs = CIBlock::getList(array(), array('ID' => array_keys($arSettings)?:0));
					while ($ar = $rs->GetNext(false, false)):?>
						<option <?= ($IBLOCK_ID == $ar['ID']) ? 'selected' : '' ?>
							value="<?= $ar['ID'] ?>"><?= $ar['NAME'] ?></option>
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
						id="SECTION_ID" <? if ($bIB) : ?><?= (count($arSections) > 10) ? 'size="12"' : 'size="3"' ?><? else: ?>disabled<? endif ?>>
					<? if (!$bIB): ?>
						<option value="0"><?= GetMessage('REINDEX_SECTION_ID_CHOOSE_IBLOCK_FIRST') ?></option>
					<? else: ?>
						<option value="ALL"<?=($_REQUEST['SECTION_ID']==='ALL'?' selected="selected"':'')?>><?= GetMessage('REINDEX_SECTION_ID_CHOOSE_ALL') ?></option>
						<?
						foreach ($arSections as $ar):?>
							<option value="<?= $ar['ID'] ?>"<?=($SECTION_ID==$ar['ID']?' selected="selected"':'')?>><?= str_repeat('. ', $ar['DEPTH_LEVEL']), $ar['NAME'] ?></option>
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
			var $form = $('#core_color_guess_form');

			var sendForm = function (add_data) {
				$('#start_button').prop('disabled', true);
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
						window.scrollTo(0, 0);
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
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
?>