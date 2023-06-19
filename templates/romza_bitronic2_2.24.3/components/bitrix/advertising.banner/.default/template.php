<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());
if(!empty($arResult["BANNER"]))
{
	echo $arResult["BANNER"];
}
	include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';
if(method_exists($this, 'createFrame')) $frame->end();
?>