<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));
$APPLICATION->AddHeadScript($templateFolder."/jquery.animate-colors-min.js");
$APPLICATION->AddHeadScript($templateFolder."/jquery.animate-shadow-min.js");

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

$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");?>
<?
$name = "";
?>
<nav>
    <div class="navmenu">
<?if ($arParams["MASK"] != "N"):?>
<div class="ye_horiz_mask"></div>
<?endif?>
        <div class="nav_cor_l"></div>
        <div class="nav_cor_r"></div>
<?if (!empty($arResult)):
    $count = 0;
    foreach($arResult as $arItem) {
        if($arItem["DEPTH_LEVEL"] == 1)
            $count++;
    }
    $previousLevel = 0;
    $i = 0;
    $hits = false;
?>
    <ul id="navigator">
    <?foreach($arResult as $index => $arItem):?>
    
    <?if($index === 'HITS') continue?>

        <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]-1));?></ul>
                
                <?if(is_array($hits)) include 'megamenu.php'?>

                </div></li>
            <?else:?>
                <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
            <?endif?>
        <?endif?>
        
        <?

        if($arItem['DEPTH_LEVEL'] == 1 && (strpos($arItem["LINK"], 'catalog_') !== false  ||
            strpos($arItem["LINK"], SITE_ID.'_') !== false))
        {
            $word = explode('_', $arItem["LINK"]);
            $word = $word[0];
            $word = explode('/', $word);
            $arItem["LINK"] = str_replace($word[1].'_', "", $arItem["LINK"]);
            $arItem["LINK"] = str_replace("_", "-", $arItem["LINK"]);
        }
        $liClass = ($count < 5) ? 'littlemenucount ' : '';
        
        $bCurPage = ($arItem["LINK"] == $_SERVER["REQUEST_URI"]
                  || $arItem["LINK"] == $_SERVER["REQUEST_URI"].'index.php'
                  || $arItem["LINK"].'index.php' == $_SERVER["REQUEST_URI"]);
        ?>
        <?if ($arItem["IS_PARENT"]):?>
            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
            
                <?$hits = $arResult['HITS'][$index];?>
                <li <?if($liClass):?>class="<?=$liClass?>" <?endif?>style="width: <?=100/$count;?>%"><a <?if($bCurPage):?>name="activeLink"<?else:?>href="<?=$arItem["LINK"]?>"<?endif?>><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></a>
                    <div class="subnav"><ul><?$i=0;?>
            <?else:?>
                
                <li class="<?if ($arItem["SELECTED"]):?>item-selected<?endif;?>"><?if(!$bCurPage):?><a href="<?=$arItem["LINK"]?>"><?endif?><?=$arItem["TEXT"]?><?if(!$bCurPage):?></a><?endif?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?>
                
                <ul>
            <?endif?>
        <?else:
            $liClass .= 'no-subnav'?>
            <?if ($arItem["PERMISSION"] > "D"):?>
                <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <li class="<?=$liClass?>" style="width: <?=100/$count;?>%"><a href="<?=$arItem["LINK"];?>"><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></a>
                <?else: $i++;?>
                    
                    <?if($bCurPage):?>
                        <li class="<?if ($arItem["SELECTED"]):?>item-selected<?endif;?>"><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?> </li>
                    <?else:?>
                        <li class="<?if ($arItem["SELECTED"]):?>item-selected<?endif;?>"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></li>
                    <?endif?>
                <?endif?>
            <?else:?>
                <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                    <?if ($arItem["SELECTED"]):?>
                        <li class="<?=$liClass?>" style="width: <?=100/$count;?>%"><?=$arItem["TEXT"]?></li>
                    <?else:?>
                        <li class="<?=$liClass?>" style="width: <?=100/$count;?>%">><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></li>
                    <?endif?>
                <?else:?>
                    <li><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a><?if($arItem["PARAMS"]["ELEMENT_CNT"] > 0):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></li>
                <?endif?>
            <?endif?>
        <?endif?>
        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
        
    <?endforeach?>
    
    <?if ($previousLevel > 1): //close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel-2) );?> </ul>
        <?if(is_array($hits)) include 'megamenu.php'?>
        </div></li>
    <?endif?>
    
    </ul>
<?endif?>
    </div>
</nav>


<script>
var YSMenu = {};
YSMenu.Speed = {
    <?
    switch ($arParams['SPEED']) {
        case 'NONE': echo 'animate: 0, fadeOut: 0'; break;
        case 'FAST': echo 'animate: 333, fadeOut: 500'; break;
        case 'SLOW': echo 'animate: 1000, fadeOut: 1200'; break;
        case 'NORMAL':
        default:     echo 'animate: 666, fadeOut: 900'; break;
    }
    ?>
};
</script>
