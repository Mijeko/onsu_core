<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
if($this->__folder)
    $pathToTemplateFolder = $this->__folder ;
else
    $pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));

$arColorSchemes = array ('red', 'green', 'ice', 'metal', 'pink', 'yellow') ;

if($arParams['THEME'] && in_array($arParams['THEME'], $arColorSchemes, true))
    $color_scheme = $arParams['THEME'] ;
elseif($arParams['THEME'] === "blue")
    $color_scheme = 'ice' ;
elseif(($bitronic_color_scheme = COption::GetOptionString('yenisite.market', 'color_scheme')) && in_array($bitronic_color_scheme, $arColorSchemes))
    $color_scheme = $bitronic_color_scheme ;
else
    $color_scheme = 'red' ;

$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

?>

<?if (!empty($arResult)):?>

<?if ($arParams["MASK"] != "N"):?>
<div class="ye_vert_mask"></div>
<?endif?>

<div id="ye_mainmenu" <?if ($arParams['SHOW_BY_CLICK'] == 'Y'):?>class="ye-by-click"<?else:?>class="ye-by-hover"<?endif;?>>

<ul>

<?
$previousLevel = 0;
foreach($arResult as $key => $arItem):?>
<?if($key !== 'HITS'):?>
	<?
        if($arItem['DEPTH_LEVEL'] == 1) {
            if(strpos($arItem["LINK"], 'catalog_') !== false  || strpos($arItem["LINK"], SITE_ID.'_') !== false)
            {
            	$word = explode('_', $arItem["LINK"]);
	            $word = $word[0];
	            $word = explode('/', $word);
	            $arItem["LINK"] = str_replace($word[1].'_', "", $arItem["LINK"]);
	            $arItem["LINK"] = str_replace("_", "-", $arItem["LINK"]);
            }
            if ($arParams['SHOW_BY_CLICK'] == 'Y' && !empty($arItem['IS_PARENT'])) $arItem["LINK"] = '#';
        } 
	?>

	<?if ($previousLevel)
	for ($i=$previousLevel; $i > $arItem["DEPTH_LEVEL"] ; $i--):?> 
		</ul>
		<?if($i > 2)://<div class="ye_submenu">?></div><?endif?>
		</li>
	<?endfor?>

	<?
	$ys_v_link_open = ''; $ys_v_link_close = ''; $bFirst = false;

	if ($arItem["DEPTH_LEVEL"] > 2 && $arItem["DEPTH_LEVEL"] > $previousLevel) {
		$bFirst = true;
	}
	if ($arItem["SELECTED"]
	&& ( $arItem["LINK"] == $_SERVER["REQUEST_URI"]
		|| $arItem["LINK"] == $_SERVER["REQUEST_URI"].'index.php'
		|| $arItem["LINK"].'index.php' == $_SERVER["REQUEST_URI"]))
	{
		$ys_v_link_open .= '<span class="span-active' . (($bFirst) ? ' ye_first">' : '">');
		$ys_v_link_close .= '</span>';
	} else {
		$ys_v_link_open .= '<a href="'.$arItem["LINK"]. (($bFirst) ? '" class="ye_first">' : '">');
		if ($arItem["IS_PARENT"] || $arItem["DEPTH_LEVEL"] == 1 || $arItem["SELECTED"]) {
			$ys_v_link_open .= '<span>';
			$ys_v_link_close = '</span>' . $ys_v_link_close;
		}
		$ys_v_link_close .= '</a>';
	}
	?>

	<?if ($arItem["IS_PARENT"]):?>

		<li class="has_submenu <?if ($arItem["SELECTED"]):?>active-link<?endif?>"><?=$ys_v_link_open?><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?><?=$ys_v_link_close?>
		<?if($arItem["DEPTH_LEVEL"] > 1):?><div class="ye_submenu"><?endif?>
			<?if ($arItem["SELECTED"]):?>
				<ul style="display: block;">
			<?else:?>
				<ul>
			<?endif;?>
	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1): ?>
				<li class="<?if ($arItem["SELECTED"]):?>active-link<?endif?>"><?=$ys_v_link_open?><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?><?=$ys_v_link_close?></li>
			<?else:?>
				<li class="<?if ($arItem["SELECTED"]):?>active-link<?endif?>" data-ys-li-key="<?=$key?>"><?=$ys_v_link_open?><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?><?=$ys_v_link_close?></li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li class="<?if ($arItem["SELECTED"]):?>active-link<?endif?>"><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></a></li>
			<?else:?>
				<li class="<?if ($arItem["SELECTED"]):?>active-link<?endif?>" data-ys-li-key="<?=$key?>"><a href=""><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></a></li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
<?endif;?>
<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?for ($i=$previousLevel; $i > 1 ; $i--):?> 
		</ul>
		<?if($i > 2)://<div class="ye_submenu">?></div><?endif?>
		</li>
	<?endfor?>
<?endif?>

</ul>

<?if (!empty($arResult['HITS'])):?>
<?foreach($arResult['HITS'] as $key => $hit):?>
	<div class="best" data-ys-hit-key="<?=$key?>">
		<a href="<?=$hit["DETAIL_PAGE_URL"]?>">
	        <img src='<?=$hit["PHOTO"];?>' alt='<?=$hit["NAME"]?>' />
	    </a>
        
        <a href="<?=$hit["SECTION_PAGE_URL"]?>" class="tag"><?=$hit["SECTION"]?></a>
        <h3>
        	<a href="<?=$hit["DETAIL_PAGE_URL"]?>"><?=$hit["NAME"]?></a>
		</h3>
		<?
            if($arParams["CURRENCY"] == "RUB" && $arParams["RUB_SIGN"] == "Y")
            {
            	$hit["PRINT_VALUE"] = str_replace(GetMessage('RUB_REPLACE'), '<i class="rubl">'.
            	GetMessage("RUB").'</i>', $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_VALUE"]);

	            $hit["PRINT_DISCOUNT_VALUE"] = str_replace(GetMessage('RUB_REPLACE'), '<i class="rubl">'.
	            	GetMessage("RUB").'</i>', $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_DISCOUNT_VALUE"]);
            }
            else
            {
            	$hit["PRINT_VALUE"] = $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_VALUE"];
            	$hit["PRINT_DISCOUNT_VALUE"] = $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_DISCOUNT_VALUE"];
            }
        ?>
		<i class="price">
			<?=$hit["PRINT_DISCOUNT_VALUE"]?>
			<?if($hit["PRICE"][$arParams["PRICE_CODE"]]["DISCOUNT_VALUE"] != $hit["PRICE"][$arParams["PRICE_CODE"]]["VALUE"]):?>
				<i class="oldprice">
					<?=$hit["PRINT_VALUE"]?>
				</i>
			<?endif;?>
		</i>
	</div>
<?endforeach;?>
<?endif;?>

</div>

<?endif?>