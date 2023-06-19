<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if(CModule::IncludeModule('statistic')):?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));?>
<?if (CModule::IncludeModule('yenisite.geoipstore') && $arParams['UNITE_WITH_GEOIPSTORE'] != 'Y'):?>
	<span class="sym ys-arr"><?=GetMessage("LOC_TO")?></span>
<?endif?>

<input type="hidden" name="city_inline" id="city_inline" value="<?=$arResult['CITY_INLINE'];?>"/>

<a href="" class="ys-loc-city">
	<?
	if (!empty($arResult['CITY_INLINE'])) {
		echo $arResult['CITY_INLINE'];
	} else {
		echo GetMessage('CHOOSE_CITY');
	}
	?>
</a>

<div class="ys-popup popup" id="ys-locator" style="display:none;">

	<a class="close sym" href="javascript:void(0);" title="<?=GetMessage('EXIT')?>"><?=($arParams['NEW_FONTS']=='Y') ? GetMessage("CLOSE_NEW") : GetMessage("CLOSE");?></a>
	<h2>
		<?if(!empty($arResult['CITY_IP'])):?>
			<?=GetMessage('YOUR_CITY').' <span class="ys-city-header">'.$arResult['CITY_IP'].'</span>?';?>
		<?else:?>
			<?=GetMessage('YOUR_CITY').'?';?>
		<?endif;?>
	
	</h2>
	<div>
		<input class="txt ys-city-query" style="width: 400px;" type="text" placeholder="<?=GetMessage('ENTER_YOUR_CITY');?>" />
	</div>
	
	<div class="ys-loc-autocomplete">
	</div>
	
	<div class="ys-loc-cities">
		<ul class="ys-loc-first">
		
			<?if(!empty($arResult['CITY_IP'])):?>
				<li class="ys-your-city">
					<span class="sym"><?=($arParams['NEW_FONTS']=='Y') ? GetMessage("YOUR_NEW") : GetMessage("YOUR");?> </span>
					<span data-location="<?=$arResult['CITY_ID']?>"><?=$arResult['CITY_IP'];?></span>
			<?else:?>
				<li>
					<a href="#"><span data-location="<?=$arResult['LOCATION'][0]?>"><?=$arResult['CITY'][0];?></span></a>
			<?endif;?>
				</li>
<?for ($i=1; $i<9; $i++):?>
<?if ($i % 3 == 0):?>
		<ul>
<?endif?>
			<li><a href="#"><span data-location="<?=$arResult['LOCATION'][$i]?>"><?=$arResult['CITY'][$i]?></span></a></li>
<?if ($i % 3 == 2):?>
		</ul>
<?endif?>
<?endfor?>
	</div>
	
	<div class="clear"></div>
	
	<div class="ys-my-city">
		<input class='button' type="submit" name="submit" value="<?=GetMessage("THIS_IS_MY_CITY")?>">
	</div>
	<input type="hidden" id="ys-COMPONENT_DIRECTORY" value="<?=$componentPath.'/'; //$arResult["COMPONENT_DIRECTORY"]?>" />
	<input type="hidden" id="ys-SITE_ID" value="<?=SITE_ID?>" />
</div>

<div id="ys-geoip-mask" class="ys-geoip-mask"></div>
<?if(method_exists($this, 'createFrame')) $frame->end();?>
<?endif;?>