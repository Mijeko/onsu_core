<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!CRZBitronic2Settings::isPro()) return;?>
<?
if(strlen($arResult["ERROR_MESSAGE"])>0) {
	echo CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["ERROR_MESSAGE"], "TYPE" => "ERROR"));
}
/*
<?if(strlen($arResult["NAV_STRING"]) > 0):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?endif?>
*/
?>

<table class="table_account-profiles">
	<thead>
		<tr>
			<th><?=GetMessage("P_ID")?><br /><?=SortingEx("ID")?></th>
			<th>
				<span class="hidden-xs"><?=GetMessage("P_DATE_UPDATE")?></span>
				<span class="visible-xs"><?=GetMessage("P_DATE_UPDATE_XS")?></span>
				<?=SortingEx("DATE_UPDATE")?>
			</th>
			<th><?=GetMessage("P_NAME")?><?=SortingEx("NAME")?></th>
			<th><?=GetMessage("P_PERSON_TYPE")?><span class="hidden-xs"><?=GetMessage("P_PERSON_TYPE_XS")?></span><?=SortingEx("PERSON_TYPE_ID")?></th>
			<th><span class="hidden-xs"><?=GetMessage("SALE_ACTION")?></span></th>
		</tr>
	</thead>
	<tbody>
	<?foreach($arResult["PROFILES"] as $val):?>

		<tr>
			<td><b><?=$val["ID"]?></b></td>
			<td><span class="update-time"><?=$val["DATE_UPDATE"]?></span></td>
			<td><span class="name"><?=$val["NAME"]?></span></td>
			<td>
				<span class="payer-type hidden-xs"><?=$val["PERSON_TYPE"]["NAME"]?></span>
				<span class="payer-type visible-xs-inline"><?=$val["PERSON_TYPE"]["ACRONYM"]?></span>
			</td>
			<td class="profile-actions">
				<a title="<?= GetMessage("SALE_DETAIL_DESCR") ?>" href="<?=$val["URL_TO_DETAIL"]?>"><i class="flaticon-pencil72 profile-action edit"></i></a>
				<a title="<?= GetMessage("SALE_DELETE_DESCR") ?>" href="javascript:if(confirm('<?= GetMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?=$val["URL_TO_DETELE"]?>'"><i class="flaticon-trash29 profile-action delete"></i></a></td>
			</td>
		</tr>
	<?endforeach;?>

	</tbody>
</table>
<?if(strlen($arResult["NAV_STRING"]) > 0):?>
<div class="static-pagination-wrap">
	<?=$arResult["NAV_STRING"]?>
</div>
<?endif?>
<a href="<?=str_replace('#ID#', 'add', $arParams['PATH_TO_DETAIL'])?>" class="btn-main btn-add-account-profile"><span class="text"><?= GetMessage("SALE_ADD_DESCR") ?></span></a>
