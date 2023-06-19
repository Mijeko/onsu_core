<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
// echo "<pre style='text-align:left;'>";print_r($arPageCount);echo "</pre>";
?>
<span class="show-by disabled">
	<span class="text"><?= GetMessage('BITRONIC2_VIEW_BY') ?></span>
	<select name="show-by" class="select-styled show-by">
		<? // $arPageCount & $page_count set in include/service_var.php
		foreach ($arPageCount as $value):?>
			<option value="<?= $value ?>" <?= ($page_count == $value ? 'selected' : '') ?>><?= $value ?></option>
		<? endforeach ?>
	</select>
</span>
