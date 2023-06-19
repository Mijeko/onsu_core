<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<?IncludeModuleLangFile(__FILE__);?>
<HTML>
<HEAD>
	
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=<? echo LANG_CHARSET ?>">
	<!--  <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=windows-1251"> -->
	<TITLE></TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 3.2  (Linux)">
	<META NAME="CREATED" CONTENT="0;0">
	<META NAME="CHANGED" CONTENT="0;0">
	
	<STYLE>
		<!-- 
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:"Arial"; font-size:xx-small }
		 -->
	</STYLE>
	
</HEAD>


<BODY TEXT="#000000">
<TABLE FRAME=VOID CELLSPACING=0 COLS=8 RULES=NONE BORDER=0>
	
	<TBODY>
		<TR>
			<TD WIDTH=42 HEIGHT=30 ALIGN=LEFT><FONT FACE="Comic Sans MS" SIZE=5 COLOR="#FF0000"><BR></FONT></TD>
			<TD WIDTH=387 ALIGN=CENTER VALIGN=MIDDLE><FONT FACE="Tahoma" SIZE=4 COLOR="#000080"><?=$arResult["FIELDS"]["TITLE"]?> <?=date('d.m.Y');?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>			
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935; border-right: 3px solid #3a3935" HEIGHT=47 ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2><?=$arResult["FIELDS"]["ID"]?></FONT></B></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935; border-right: 3px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2><?=$arResult["FIELDS"]["NAME"]?></FONT></B></TD>

			<?foreach($arResult["PRICE_TITLE"] as $price):?>
            <TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935; border-right: 3px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2><?=$price . " (" . $arParams["CURRENCY_LIST"] . ")"?></FONT></B></TD>
			<?endforeach?>	
			
		<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
			<TD STYLE="border: 3px solid #3a3935;"><B><FONT SIZE=2><?=GetMessage("EXISTENCE_CHECK")?></FONT></B></TD>
		<?endif?>
			
        <?foreach($arResult[PROPS] as $pid=>$name):?>
            <TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935; border-right: 3px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2><?=$name;?></FONT></B></TD>    		
		<?endforeach?>			



