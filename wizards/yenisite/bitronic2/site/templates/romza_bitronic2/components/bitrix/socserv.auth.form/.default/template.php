<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$arAuthServices = $arPost = array();
if (is_array($arResult['SERVICES'])) {
	$arAuthServices = $arResult['SERVICES'];
}
if (is_array($arParams["~POST"])) {
	$arPost = $arParams["~POST"];
}

?>

<?if($arParams["~CURRENT_SERVICE"] <> ''):?>
<script type="text/javascript">
BX.ready(function(){BxShowAuthService('<?=CUtil::JSEscape($arParams["~CURRENT_SERVICE"])?>', '<?=$arParams["~SUFFIX"]?>')});
</script>
<?endif?>


				<?/*
				<ul class="social-list">
					<li><a href="#" class="social-modal vk"><span class="icon-container sprite"></span></a></li>
					<li><a href="#" class="social-modal ok"><span class="icon-container sprite"></span></a></li>
					<li><a href="#" class="social-modal mail"><span class="icon-container sprite"></span></a></li>
					<li><a href="#" class="social-modal fb"><span class="icon-container sprite"></span></a></li>
					<li><a href="#" class="social-modal black"><span class="icon-container sprite"></span></a></li>	
				</ul>
			*/?>

	<form method="post" name="bx_auth_services<?=$arParams["SUFFIX"]?>" target="_top" action="<?=$arParams["AUTH_URL"]?>" class="form_socials">
		<?if($arParams["~SHOW_TITLES"] != 'N'):?>
			<p class="social-text"><?=GetMessage("socserv_as_user")?></p>
		<?endif;?>
		<div class="bx-auth-services">
		
			<?foreach($arAuthServices as $service):?>
				<span>
					<a 
						href="javascript:void(0)" 
						<?if (empty($service['ONCLICK'])):?>onclick="BxShowAuthService('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')"<?else:?> onclick="<?=$service['ONCLICK']?>" <?endif?>
						id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"
						<?//class="social-modal"?>
						title="<?=htmlspecialcharsbx($service["NAME"])?>"
						>
						<i class="bx-ss-icon <?=htmlspecialcharsbx($service["ICON"])?>"></i>
					</a>
				</span>
			<?endforeach?>

		</div>
		<div class="bx-auth-service-form" id="bx_auth_serv<?=$arParams["SUFFIX"]?>" style="display:none">
			<?foreach($arAuthServices as $service):?>
					<div id="bx_auth_serv_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>" style="display:none"><?=$service["FORM_HTML"]?></div>
			<?endforeach?>
		</div>
		<?foreach($arPost as $key => $value):?>
			<?if(!preg_match("|OPENID_IDENTITY|", $key)):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endif;?>
		<?endforeach?>
		<input type="hidden" name="auth_service_id" value="" />
	</form>