<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<? if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true); ?>
<?
$glueTemplate = ' / ';
foreach ($arResult['GROUPS'] as $groupID => $arGroup): ?>
	<div class="tech-info-block expandable expanded allow-multiple-expanded">
		<?if($groupID > 0):?>
			<header>
				<?// TODO icons?>
				<i class="flaticon-multimedia general"></i>
				<span class="text"><?=$arGroup['NAME']?></span>
			</header>
		<?elseif(!empty($arParams['SKU_PROP_ID'])):?>

		<dl class="expand-content clearfix" id="<?=$arParams['SKU_PROP_ID']?>">
		</dl>
		<?endif?>

		<dl class="expand-content clearfix">
			<?foreach ($arGroup['PROPS'] as $pid):
				$arProperty = $arResult['DISPLAY_PROPERTIES'][$pid];
				?><dt><?
					echo $arProperty["NAME"];
					if(strlen($arProperty['HINT']) > 0):
						echo '<sup data-tooltip title="'.$arProperty['HINT'].'" data-container="body" data-placement="right">?</sup>';
					endif
				?></dt>
				<dd><?
					if ($arProperty['PROPERTY_TYPE'] == 'L'):
						if (is_array($arProperty['DISPLAY_VALUE'])):
							echo implode($glueTemplate, $arProperty['DISPLAY_VALUE']);
						else:
							echo $arProperty['DISPLAY_VALUE'];
						endif;
					else:
						if (is_array($arProperty["DISPLAY_VALUE"]) && $arProperty['PROPERTY_TYPE'] != 'F'):
							if($arProperty['PROPERTY_TYPE'] == 'E' || $arProperty['PROPERTY_TYPE'] == 'G') {
								foreach ($arProperty["DISPLAY_VALUE"] as &$p) {
									if (substr_count($p, "a href") > 0) {
										$p = strip_tags($p);
									}
								}
							}
							echo implode($glueTemplate, $arProperty["DISPLAY_VALUE"]);
						elseif ($arProperty['PROPERTY_TYPE'] == 'F'):
							if ($arProperty['MULTIPLE'] == 'Y'):
								if (is_array($arProperty['DISPLAY_VALUE'])):
									foreach ($arProperty['DISPLAY_VALUE'] as $n => $value):
										echo $n > 0 ? ', ' : '';
										echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'][$n] . '</a>', $value);
									endforeach;
								else:
									echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'][0] . '</a>', $arProperty['DISPLAY_VALUE']);
								endif;
							else:
								echo str_replace('</a>', ' ' . $arProperty['DESCRIPTION'] . '</a>', $arProperty['DISPLAY_VALUE']);
							endif;
						else:
							//if (substr_count($arProperty["DISPLAY_VALUE"], "a href") > 0) {
							if ($arProperty['PROPERTY_TYPE'] == 'E' || $arProperty['PROPERTY_TYPE'] == 'G') {
								$arProperty["DISPLAY_VALUE"] = strip_tags($arProperty["DISPLAY_VALUE"]);
							}
							
							echo $arProperty["DISPLAY_VALUE"];
							
							// TODO DESCRIPTION
							if (false && $arParams['SHOW_PROPERTY_VALUE_DESCRIPTION'] != 'N') {
								echo ' ', $arProperty['DESCRIPTION'];
							}
						endif;
					endif;
				?></dd>
			<? endforeach ?>
		</dl>
	</div><!-- .tech-info-block -->
<? endforeach ?>
		
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
