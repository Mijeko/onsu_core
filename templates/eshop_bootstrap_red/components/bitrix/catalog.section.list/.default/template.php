<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss('style.css');

$strTitle = "";
?>

<div class="catalog-section-list">
    <!-- tree\template.php -->
    <?
    $TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
	$CURRENT_DEPTH = $TOP_DEPTH;
	$CURRENT_ID = "";
    $unCollapse = 0;
    $allParent = [];
    $curUrl = explode("/", $APPLICATION->GetCurDir(), 4);
    
	foreach($arResult["SECTIONS"] as $arSection) {
        //var_dump($arSection);
	    if ($arSection['DEPTH_LEVEL'] != 1) {
	        $allParent[$arSection['IBLOCK_SECTION_ID']] = 1;
        }
        
        $selUrl = explode("/", $arSection["SECTION_PAGE_URL"], 4);
        //echo $curUrl[2] . '=' .$selUrl[2] .' '. ($curUrl[2] == $selUrl[2]) ."<br>";
	    if ($curUrl[2] == $selUrl[2])
            if ($arSection['IBLOCK_SECTION_ID'])
	            $unCollapse =  $arSection['IBLOCK_SECTION_ID'];
	        else
                $unCollapse =  $arSection['ID'];
    }
    
	foreach($arResult["SECTIONS"] as $arSection) {
        //var_dump($unCollapse, $arSection);
	    $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
		if($CURRENT_DEPTH < $arSection["DEPTH_LEVEL"]) {
            $strClass = 'class="collapse in"';
		    if ($arSection["DEPTH_LEVEL"] != 1) {
                if ($unCollapse != $arSection['IBLOCK_SECTION_ID']) {
                    $strClass = 'class="collapse"';
                }
            } else {
                $strClass = 'class="main-menu"';
            }
		    echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH), "<ul $CURRENT_ID $strClass>";
		} elseif($CURRENT_DEPTH == $arSection["DEPTH_LEVEL"]) {
			echo "</li>";
		} else {
			while($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"]) {
				echo "</li>";
				echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
				$CURRENT_DEPTH--;
			}
			echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</li>";
		}

		$count = $arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] ? "&nbsp;(".$arSection["ELEMENT_CNT"].")" : "";

		if ($_REQUEST['SECTION_ID']==$arSection['ID']) {
			$link = '<b>'.$arSection["NAME"].$count.'</b>';
			$strTitle = $arSection["NAME"];
		} else {
			$link = '<a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection["NAME"].$count.'</a>';
			if (key_exists($arSection['ID'], $allParent)) {
                $link .= ' <a class="chevron" data-toggle="collapse" data-target="#i' . $arSection['ID'] .'" onclick="up_down()">';
                if ($unCollapse == $arSection['ID'])
                    $link .= '<span class="glyphicon glyphicon-chevron-up"></span></a>';
                else
                    $link .= '<span class="glyphicon glyphicon-chevron-down"></span></a>';
            }
		}

		echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH);
		
		$selUrl = explode("/", $arSection["SECTION_PAGE_URL"], 4);
		?>
        <li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?= $curUrl[2] == $selUrl[2] ? '<b>' . $link . '</b>' : $link ?>
        <?
        $CURRENT_ID = 'id="i'.$arSection['ID'].'"';
		$CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
	}

	while($CURRENT_DEPTH > $TOP_DEPTH)
	{
		echo "</li>";
		echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
		$CURRENT_DEPTH--;
	}
	?>
</div>
<?=($strTitle?'<br/><h2>'.$strTitle.'</h2>':'')?>

<script>
    function up_down() {
        $(event.target).toggleClass('glyphicon-chevron-up').toggleClass('glyphicon-chevron-down');
    }
</script>    
