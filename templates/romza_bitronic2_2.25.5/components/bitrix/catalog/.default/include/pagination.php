<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Yenisite\Core\Tools;
use Bitronic2\Mobile;

CModule::IncludeModule('yenisite.core');
global $arPagination;
if ($bPaginationSecond) {
	if (mobile::isMobile() && $rz_b2_options['pagination_type'] != 'inf-button' && $rz_b2_options['pagination_type'] != 'inf') {
		$rz_b2_options['pagination_type'] = 'inf-button';
	}
    if (mobile::isMobile() && intval($arPagination['END_PAGE']) == intval($arPagination['PAGEN'])){
        $rz_b2_options['pagination_type'] = 'default';
    }
	switch ($rz_b2_options['pagination_type']) {
		case 'inf-button':
			?>
			<script type="text/javascript">
				RZB2.ajax.params.inf_button = true;
			</script>
		<?
		case 'inf':
			?>
			<div class="more-catalog-wrap">
				<? $isDisabled = $arPagination['PAGEN'] >= $arPagination['END_PAGE'];?>
				<a<?= ($isDisabled) ? '' : ' href="' . $APPLICATION->GetCurPageParam('PAGEN_' . $arPagination['NUM'] . '=' . ($arPagination['PAGEN'] + 1),
						array('PAGEN_' . $arPagination['NUM'])) . '"' ?>
				class="more-catalog<?= ($isDisabled) ? ' disabled' : '' ?>"
				data-pagen-key="<?= 'PAGEN_' . $arPagination['NUM'] ?>" data-page="<?= $arPagination['PAGEN'] + 1 ?>">
				<span class="btn-plus">+</span>
				<? if (!$isDisabled): ?>
					<span class="text"><?= GetMessage("BITRONIC2_CATALOG_POKAZAT_ESHE") ?> <?= $page_count ?></span>
				<? endif ?>
				</a>
				<span class="text"><?= GetMessage("BITRONIC2_CATALOG_POKAZANO") ?> <?= $arPagination['SELECT'] ?>
					<?= Tools::rusQuantity($arPagination['SELECT'], GetMessage('BITRONIC2_CATALOG_TOVAR')), GetMessage("BITRONIC2_CATALOG_IZ") ?>
					<?= $arPagination['COUNT'] ?>
				</span>
			</div>
			<script type="text/javascript">
				if (!('inf_button' in RZB2.ajax.params)) {
                    RZB2.ajax.initInfPaginatore = function() {
                        $('.more-catalog-wrap').data('is_scrolling',false)
                        $('.more-catalog-wrap').RMZinfScroll(
                            function () {
                                var $this = $('.more-catalog');
                                if (!$this.hasClass('disabled')) {
                                    var params = {};
                                    params[$this.attr('data-pagen-key')] = $this.attr('data-page');
                                    params['MORE_CLICK'] = 1;
                                    return RZB2.ajax.CatalogSection.Start($this.find('.btn-plus'), params);
                                }
                            },
                            {paddingTop: $('.more-catalog-wrap').outerHeight(true) + 100}
                        );
                    }
					jQuery(window).load(function(){
						require(['back-end/inf_scroll'], function(){
                            RZB2.ajax.initInfPaginatore();
						});
					});
				}
			</script>
			<?
			break;
		case 'default':
		default:
			?>
			<form action="#" method="post" class="pagination-wrap">
				<?
                if (!mobile::isMobile()){
                    include 'page_count.php';
                }
				$APPLICATION->ShowViewContent('catalog_paginator');
				?>
			</form>
		<?
	}
} else {
	?>
	<form action="#" method="post" class="pagination-wrap">
		<?
        if (!mobile::isMobile()){
            include 'page_count.php';
        }
		$APPLICATION->ShowViewContent('catalog_paginator');
		?>
	</form>
<?
}