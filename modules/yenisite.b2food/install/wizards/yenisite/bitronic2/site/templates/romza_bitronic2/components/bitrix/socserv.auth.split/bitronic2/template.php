<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<br><br>
<?
if($arResult['ERROR_MESSAGE'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
<?
$arServices = $arResult["AUTH_SERVICES_ICONS"];
if(!empty($arResult["AUTH_SERVICES"]))
{
	?>
	
	<div class="soc-serv-main">
		<div class="soc-serv-title-grey">
			<?=GetMessage("SS_GET_COMPONENT_INFO")?>
			<br><br>
		</div>
	<?
	$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
		array(
			"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
			"CURRENT_SERVICE"=>$arResult["CURRENT_SERVICE"],
			"AUTH_URL"=>$arResult['CURRENTURL'],
			"POST"=>$arResult["POST"],
			"SHOW_TITLES"=>'N',
			"FOR_SPLIT"=>'Y',
			"AUTH_LINE"=>'N',
		),
		$component,
		array("HIDE_ICONS"=>"Y")
	);
	?>
	<?
}

if(isset($arResult["DB_SOCSERV_USER"]) && $arParams["SHOW_PROFILES"] != 'N')
{
	?>
	<form class="form-profile-settings">
	<div class="settings-list">
		<label><?=GetMessage("SS_YOUR_ACCOUNTS");?></label>
		<ul class="social-list">
			<?
			foreach($arResult["DB_SOCSERV_USER"] as $key => $arUser)
			{
				if(!$icon = htmlspecialcharsbx($arResult["AUTH_SERVICES_ICONS"][$arUser["EXTERNAL_AUTH_ID"]]["ICON"]))
					$icon = 'openid';
				$authID = ($arServices[$arUser["EXTERNAL_AUTH_ID"]]["NAME"]) ? $arServices[$arUser["EXTERNAL_AUTH_ID"]]["NAME"] : $arUser["EXTERNAL_AUTH_ID"];
				?>
				<li>
					<i class=" bx-ss-icon <?=$icon?>">&nbsp; </i>
					<a href="<?=$arUser["PERSONAL_LINK"]?>" target="_blank" class=" soc-serv-link"><?=$authID?></a>
					
					<div style="display: inline-block">
						<?=$arUser["VIEW_NAME"]?>
					</div>
					<div class="item-actions">
						<?if (in_array($arUser["ID"], $arResult["ALLOW_DELETE_ID"])):?>
							<a class="split-delete-item" href="<?=htmlspecialcharsbx($arUser["DELETE_LINK"])?>" onclick="return confirm('<?=GetMessage("SS_PROFILE_DELETE_CONFIRM")?>')" title=<?=GetMessage("SS_DELETE")?>><?=GetMessage("SS_DELETE_PROFILE")?></a>
						<?endif;?>
					</div>
				</li>
				<?
			}
			?>
		</ul>
	</div>
	</form>
	
	<?
}
?>
<?
if(!empty($arResult["AUTH_SERVICES"]))
{
	?>
	</div>
	<?
}
?>