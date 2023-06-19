<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $USER;
if($USER->IsAdmin()):?>
<div class="info4admin">
	<header><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO');?></header>
	<div class="content">
		<?if(false):?>
			<span class="info4admin-item">
				<i class="flaticon-shopping109"></i>
				<span class="text"><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO_SALE_EXT')?>:</span>
				<strong class="value">---</strong>
			</span>
			<span class="info4admin-item">
				<i class="flaticon-shopping109"></i>
				<span class="text"><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO_SALE_INT')?>:</span>
				<strong class="value">---</strong>
			</span>
		<?endif?>
		
		<?if($arItem['SORT']):?>
			<span class="info4admin-item">
				<i class="flaticon-7-1"></i>
				<span class="text"><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO_INDEX_SORT')?>:</span>
				<strong class="value"><?=$arItem["SORT"]?></strong>
			</span>
		<?endif?>
		
		<?if(false):?>
			<span class="info4admin-item">
				<i class="flaticon-433"></i>
				<span class="text"><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO_SHOW_WEEK')?>:</span>
				<strong class="value">---</strong>
			</span>
			<div class="views-by-day">
				<span class="text"><?=GetMessage('BITRONIC2_BLOCKS_ADMIN_INFO_SHOW_DAYS')?>:</span>
				<span class="item">
					<span class="date">---</span> &ndash;
					<strong class="value">---</strong>
				</span>
			</div>
		<?endif?>
	</div>
</div>
<?endif?>