<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(false);
?>
<ul class="sitemap">
	
	
	<?
	$previousLevel = -1;
	foreach($arResult as $arItem):?>
		<?
		if($arItem['DEPTH_LEVEL'] == 1
			&& (strpos($arItem["LINK"], 'catalog_') !== false 
				|| strpos($arItem["LINK"], SITE_ID.'_') !== false)) {

            $word = explode('_', $arItem["LINK"]);
            $word = $word[0];
            $word = explode('/', $word);
            $arItem["LINK"] = str_replace($word[1].'_', "", $arItem["LINK"]);
            $arItem["LINK"] = str_replace("_", "-", $arItem["LINK"]);
        } 
		?>
		<?if($arItem["DEPTH_LEVEL"]<$previousLevel):?>
			<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
			<?if($arItem["IS_PARENT"]!=0):?>
			<li class="folder expanded"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			<ul>
			<?else:?>
			<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>
		<?else:?>
			<?if($arItem["IS_PARENT"]!=0):?>
			<li class="folder expanded"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			<ul>
			<?else:?>
			<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>
		<?endif?>
		
		
		
		
	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
	<?endforeach?>		
	<?if ($previousLevel > 1): //close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
    <?endif?>
</ul>
