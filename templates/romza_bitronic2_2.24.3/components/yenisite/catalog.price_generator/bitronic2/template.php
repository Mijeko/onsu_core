<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>

 <?if($arElement["HIERARCHY"]["TYPE_N"]):?>
		<TR>			
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE  ALIGN=LEFT ><B STYLE="FONT-SIZE: 10px;"><?=$arElement["HIERARCHY"]["TYPE_N"]?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
            <?endforeach?>
            <?foreach($arResult[PRICE_TITLE] as $price):?>
       		<TD STYLE="border: 3px solid #3a3935;"></TD>
       		<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
 <?endif?>


 <?if($arElement["HIERARCHY"]["IBLOCK_N"]):?>
		<TR >
		    <TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE ALIGN=LEFT >
			<B STYLE="FONT-SIZE: 10px;">&nbsp;&nbsp;<?=$arElement["HIERARCHY"]["IBLOCK_N"]?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
            <?endforeach?>
            <?foreach($arResult[PRICE_TITLE] as $price):?>
       		<TD STYLE="border: 3px solid #3a3935;"></TD>
       		<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
<?endif?>
<?$i=0;foreach($arElement["HIERARCHY"]["SECTION_N"] as $sec): $i++;?>
	<?if(!in_array($sec, $_SESSION["YEN_PG"]["SECTION_N"])):?>
		<TR>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE ALIGN=LEFT>
			<B STYLE="FONT-SIZE: 10px;">
			<?$j = 0;while($j <= $i) {echo "&nbsp;&nbsp;"; $j++;}?><?=$sec?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
			<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endforeach?>
			<?foreach($arResult[PRICE_TITLE] as $price):?>
			<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
		<?$_SESSION["YEN_PG"]["SECTION_N"][] = $sec;?>
	<?endif?>
<?endforeach;?>
        <TR>
			<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><?=$arElement["ID"]?></TD>
			<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><?=$arElement["NAME"]?></TD>
			<?$bOnRequest = true?>
            <?foreach($arResult["PRICE_TITLES"] as $price):
            	$priceValue = ($arParams["DISCOUNT_CHECK"]==="N"? $arElement["PRICES"][$price]["VALUE"]: $arElement["PRICES"][$price]["DISCOUNT_VALUE"]);
            	if ($priceValue > 0) $bOnRequest = false;?>
       		<TD STYLE="border: 3px solid #3a3935;" ALIGN=CENTER><?=number_format($priceValue, 2, ',', '');?></TD>
       		<?endforeach?>			
			
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
			<TD STYLE="border: 3px solid #3a3935;" ALIGN=CENTER>
				<? // THIS PART MUST DIFFER FROM DEFAULT TEMPLATE
				if ($bOnRequest) {
					echo GetMessage("ON_REQUEST");
				} elseif ($arElement["CATALOG_AVAILABLE"] === "Y") {
					if ($arElement['CATALOG_QUANTITY'] > 0) {
						echo GetMessage("AVAILABLE");
					} else {
						echo GetMessage("FOR_ORDER");
					}
				} else {
					echo GetMessage("NOT_AVAILABLE");
				}
				// THIS PART MUST DIFFER FROM DEFAULT TEMPLATE ?>
			</TD>
			<?endif?>
			
<?foreach($arResult[PROPS] as $pid=>$name):?>
    		<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE>
    		<?if(is_array($arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"])):?>
        		<?=implode(", ",$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]);?>
    		<?else:?>
        		<?=$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]?$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]:0;?>
    		<?endif?>
    		</TD>
<?endforeach?>	

		
		</TR>


<?endforeach?>
