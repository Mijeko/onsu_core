<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($rz_b2_options['block_list-view-block'] == 'Y'
	|| $rz_b2_options['block_list-view-list'] == 'Y'
	|| $rz_b2_options['block_list-view-table'] == 'Y'
):?>
<? $arGetParams = array_keys($arAjaxParams) ?>
	<span class="view-type">
	<? if($rz_b2_options['block_list-view-block'] == 'Y'):?>
		<? $newParams = http_build_query(array_merge( $arAjaxParams, array('view'=>'blocks') ), '', '&amp;') ?>
		<a href="<?= $APPLICATION->GetCurPageParam($newParams, $arGetParams, false) ?>" rel="nofollow"
		   class="v-blocks <?= $view == 'blocks' ? 'active' : '' ?>" data-view="blocks" data-tooltip
		   title="<?= GetMessage('BITRONIC2_SECTION_VIEW_BLOCKS'); ?>">
			<span class="svg-wrap icon">
			<svg>
				<use xlink:href="#svg-catalog-grid"></use>
			</svg>
		</span>
            <!-- <i class="flaticon-visualization5"></i> -->
		</a>
	<? endif ?>
	<? if($rz_b2_options['block_list-view-list'] == 'Y'):?>
		<? $newParams = http_build_query(array_merge( $arAjaxParams, array('view'=>'list') ), '', '&amp;') ?>
		<a href="<?= $APPLICATION->GetCurPageParam($newParams, $arGetParams, false) ?>" rel="nofollow"
		   class="v-list <?= $view == 'list' ? 'active' : '' ?>" data-view="list" data-tooltip
		   title="<?= GetMessage('BITRONIC2_SECTION_VIEW_LIST'); ?>">
			<span class="svg-wrap icon">
			<svg>
				<use xlink:href="#svg-catalog-list"></use>
			</svg>
		</span>
            <!-- <i class="flaticon-list79"></i> -->
		</a>
	<?endif?>
	<? if($rz_b2_options['block_list-view-table'] == 'Y'):?>
		<? $newParams = http_build_query(array_merge( $arAjaxParams, array('view'=>'table') ), '', '&amp;') ?>
		<a href="<?= $APPLICATION->GetCurPageParam($newParams, $arGetParams, false) ?>" rel="nofollow"
		   class="v-table <?= $view == 'table' ? 'active' : '' ?>" data-view="table" data-tooltip
		   title="<?= GetMessage('BITRONIC2_SECTION_VIEW_TABLE'); ?>">
			<span class="svg-wrap icon">
			<svg>
				<use xlink:href="#svg-catalog-table"></use>
			</svg>
		</span>
            <!-- <i class="flaticon-webpage3"></i> -->
		</a>
	<? endif ?>
</span>
<? unset($arGetParams) ?>
<? endif ?>