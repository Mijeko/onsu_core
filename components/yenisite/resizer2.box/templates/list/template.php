<?CModule::IncludeModule('yenisite.resizer2');?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if (empty($arParams['SET_LIST'])) $arParams['SET_LIST'] = 4?>
<? $path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_LIST']); ?>
<img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />