<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) ;
$pathToTemplateFolder = str_replace($document_root, '', __FILE__);
$pathToTemplateFolder = str_replace('template.php', '', $pathToTemplateFolder);
?>

<?$APPLICATION->SetAdditionalCSS($pathToTemplateFolder.$arParams["THEME"].".css");?>
<?if (!empty($arResult)):?>
<div class="ye_roobriqa">
<? $prev = $arResult[0]["DEPTH_LEVEL"]; $now = $arResult[0]["DEPTH_LEVEL"];?>
<?foreach($arResult as $i=>$arItem): $now = $arItem["DEPTH_LEVEL"]; ?>
<?if($arItem["DEPTH_LEVEL"] > $arParams["MAX_LEVEL"]) continue;?>

	<?if($arItem["DEPTH_LEVEL"] == $arResult[0]["DEPTH_LEVEL"] ):?>	

	<? if($now == $arResult[0]["DEPTH_LEVEL"] && $i > 0):?>
				</ul>
		</div><!-- /item -->
	<?endif?>	

	<?
		$path = 0;
		$path  = $arItem["PARAMS"]["PICTURE"];		
		if(!$path) $path = $pathToTemplateFolder."img/no_photo.jpg";		
	
		if ( CModule::IncludeModule('yenisite.resizer2') ) {
			if( !isset($arParams['RESIZER2_SET']) ) {
				$dbSets = CResizer2Set::GetList();
				while($arSet = $dbSets->Fetch()) {
					$arSets[$arSet["id"]] = "[{$arSet["id"]}] {$arSet["NAME"]} ({$arSet['w']}x{$arSet['h']})";
					
					if($arSet['h'] == 170 && $arSet['w'] == 170) {
						$defualtSetId = $arSet['id'];
						break;
					}
				}
				
				$arParams['RESIZER2_SET'] = isset($defualtSetId) ? $defualtSetId : 4;
			}
			
			$path = CResizer2Resize::ResizeGD2( $arItem["PARAMS"]["PICTURE"], $arParams["RESIZER2_SET"] );
		} else {
			$arFile = CFile::MakeFileArray($path);
			$arNewFile = CIBlock::ResizePicture($arFile, array(
					"WIDTH" => 170,
					"HEIGHT" => 170,
					"METHOD" => "resample",
			) );
			$path = str_replace($_SERVER["DOCUMENT_ROOT"], "", $arNewFile["tmp_name"]);
		}
	?>
		<div class="item">
			<div class="tit"><div class="image"><a href="<?=$arItem["LINK"]?>"><img src="<?=$path?>" alt="<?=$arItem["TEXT"]?>"></a></div><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></div>
			<ul>
	<?endif?>
	<?if($arItem["DEPTH_LEVEL"] > $arResult[0]["DEPTH_LEVEL"]):?>				
				<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a> <?if($arItem["PARAMS"]["ELEMENT_CNT"]):?><sup><?=$arItem["PARAMS"]["ELEMENT_CNT"]?></sup><?endif?></li>
	<?endif?>
<?$prev = $arItem["DEPTH_LEVEL"]; endforeach?>
				</ul>
		</div><!-- /item -->
</div>
<?endif?>
