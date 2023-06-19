<?php
CModule::IncludeModule('yenisite.resizer2');
$bColorbox = (CResizer2Settings::GetSettingByName('colorbox') == 'Y');

global $APPLICATION;

if ($bColorbox) $APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin1/colorbox.css');
?>