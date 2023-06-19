<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $rz_b2_options;?>
<?if(!defined('HIDE_LEFT_MENU') || !HIDE_LEFT_MENU):?>
	<div id="catalog-at-side" class="catalog-at-side minified">
		<?if($rz_b2_options['catalog-placement'] == 'side'):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php", "PATH" => SITE_DIR."include_areas/header/menu_catalog.php"), false, array("HIDE_ICONS"=>"Y"));?>
		<?endif?>
	</div>
<?endif?>