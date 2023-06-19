<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<? if (!empty($arSort)): ?>
	<span class="text"><?= GetMessage('BITRONIC2_CATALOG_SORT_BY') ?></span>
	<!-- FOR MOBILE -->
	<select name="sort-by" id="sort-by" class="sort-by-select">
		<? foreach ($arSort as $sortValue): ?>
			<option data-sort="<?= $sortValue ?>"
					data-sort-by="asc" <?= ($sort['ACTIVE'] == $sortValue && $by == 'asc') ? 'selected' : '' ?>><?= GetMessage('BITRONIC2_CATALOG_SORT_BY_' . $sortValue) ?> &uarr;</option>
			<option data-sort="<?= $sortValue ?>"
					data-sort-by="desc" <?= ($sort['ACTIVE'] == $sortValue && $by == 'desc') ? 'selected' : '' ?>><?= GetMessage('BITRONIC2_CATALOG_SORT_BY_' . $sortValue) ?> &darr;</option>
		<? endforeach ?>
	</select>
	<!-- FOR DESKTOP -->
    <?/*
	<ul class="sort-list disabled"><?
		foreach ($arSort as $sortValue):
			$bActive = ($sort['ACTIVE'] == $sortValue);
			$bAsc = ($by == 'asc');
			?>
			<li class="sort-list-item <?= $bActive ? ' active' : '' ?> <?= ($bActive && $bAsc) ? ' sort-up' : '' ?>"
				data-sort="<?= $sortValue ?>" data-sort-by="<?= ($bAsc) ? 'asc' : 'desc' ?>">
			<span class="text"><?= (GetMessage('BITRONIC2_CATALOG_SORT_BY_' . $sortValue) ?: htmlspecialcharsBx($sortValue)) ?></span>
			</li><?
		endforeach;
		?></ul> */?>
<? endif ?>