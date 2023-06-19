<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Yenisite\Resizer2\SetFileTable;

/**
 * Base Resizer class
 */
class CResizer2
{
	const CACHE_TIME = 2764800;

	/**
	 * Include all JS and CSS files defined by module settings
	 */
	public static function ShowResizer2Head()
	{
		if (defined("ADMIN_SECTION") && ADMIN_SECTION === true) return;

		Loader::includeModule('yenisite.resizer2');
		$arSettings = CResizer2Settings::GetSettings();

		if ($arSettings['jquery'] == 'Y') {
			if (!is_object($GLOBALS["USER"])) {
				//zaplatka na 12 bitrix (19.11.2012)
				$GLOBALS["USER"] = new CUser;
			}
			CJSCore::Init(array("jquery"));
		}

		$asset = Asset::getInstance();
		if ('Y' == $arSettings['galleria']) {
			$asset->addJs('/yenisite.resizer2/js/galleria/galleria-1.2.9.min.js');
		}
		if ('Y' == $arSettings['camera']) {
			$asset->addJs('/yenisite.resizer2/js/camera/scripts/jquery.easing.1.3.js');
			//$asset->addJs('/yenisite.resizer2/js/camera/scripts/jquery.mobile.customized.min.js');
			$asset->addJs('/yenisite.resizer2/js/camera/scripts/camera.min.js');
			$asset->addCss('/yenisite.resizer2/js/camera/css/camera.css');
		}
		if ('Y' == $arSettings['powerzoomer']) {
			$asset->addJs('/yenisite.resizer2/js/powerzoomer/ddpowerzoomer.js');
		}
		if ('Y' == $arSettings['blogslideshow']) {
			$asset->addJs('/yenisite.resizer2/js/blogslideshow/jquery.blogslideshow.min.js');
			$asset->addCss('/yenisite.resizer2/js/blogslideshow/blogslideshow.css');
		}
		if ('Y' == $arSettings['imageZoomer']) {
			$asset->addJs('/yenisite.resizer2/js/featuredImageZoomer/multizoom.js');
			$asset->addCss('/yenisite.resizer2/js/featuredImageZoomer/multizoom.css');
		}
		if ('Y' == $arSettings['fancy']) {
			$asset->addJs('/yenisite.resizer2/js/fancybox2/jquery.fancybox.pack.js');
			$asset->addJs('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-buttons.js');
			$asset->addCss('/yenisite.resizer2/js/fancybox2/jquery.fancybox.css');
			$asset->addCss('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-buttons.css');
			$asset->addJs('/yenisite.resizer2/js/resizer2/script.js');

			$asset->addJs('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-thumbs.js');
			$asset->addCss('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-thumbs.css');

			$asset->addJs('/yenisite.resizer2/js/fancybox2/helpers/jquery.mousewheel-3.0.6.pack.js');
		}
		if ('Y' == $arSettings['lightbox']) {
			$asset->addJs('/yenisite.resizer2/js/lightbox/lightbox.js');
			$asset->addCss('/yenisite.resizer2/js/lightbox/lightbox.css');
		}
		if ('Y' == $arSettings['nflightbox']) {
			$asset->addJs('/yenisite.resizer2/js/nflightbox/nflightbox.js');
			$asset->addCss('/yenisite.resizer2/js/nflightbox/nf.lightbox.css');
		}
		if ('Y' == $arSettings['pretty']) {
			$asset->addJs('/yenisite.resizer2/js/prettyphoto/js/jquery.prettyPhoto.js');
			$asset->addCss('/yenisite.resizer2/js/prettyphoto/js/prettyPhoto.css');
		}
		if ('Y' == $arSettings['nyroModal']) {
			$asset->addJs('/yenisite.resizer2/js/nyroModal/jquery.nyroModal.js');
			$asset->addCss('/yenisite.resizer2/js/nyroModal/nyroModal.css');
		}
		if ('Y' == $arSettings['thickbox']) {
			$asset->addJs('/yenisite.resizer2/js/thickbox/thickbox.js');
			$asset->addCss('/yenisite.resizer2/js/thickbox/thickbox.css');
		}
		if ('Y' == $arSettings['windy']) {
			$asset->addJs('/yenisite.resizer2/js/windy/js/modernizr.custom.79639.js');
			$asset->addJs('/yenisite.resizer2/js/windy/js/jquery.windy.js');
			//$asset->addJs('/yenisite.resizer2/js/windy/js/jquery-ui-1.8.23.custom.min.js');
			$asset->addCss('/yenisite.resizer2/js/windy/css/windy.css');
			$asset->addCss('/yenisite.resizer2/js/windy/css/style1.css');
		}
		if ('Y' == $arSettings['easyzoom']) {
			$asset->addJs('/yenisite.resizer2/js/Easyzoom/easyzoom.js');
			$asset->addCss('/yenisite.resizer2/js/Easyzoom/easyzoom.css');
		}
		if ('Y' == $arSettings['highslide']) {
			$asset->addJs('/yenisite.resizer2/js/highslide/highslide-full.js');
			$asset->addCss('/yenisite.resizer2/js/highslide/highslide.css');
		}
		if ('Y' == $arSettings['carousellite']) {
			$asset->addJs('/yenisite.resizer2/js/jCarouselLite/jcarousellite_1.0.1.min.js');
			$asset->addJs('/yenisite.resizer2/js/Skitter/js/jquery.easing.1.3.js');
			$asset->addCss('/yenisite.resizer2/js/jCarouselLite/jcarousellite.css');
		}
		if ('Y' == $arSettings['shadowbox']) {
			$asset->addJs('/yenisite.resizer2/js/shadowbox/shadowbox.js');
			$asset->addCss('/yenisite.resizer2/js/shadowbox/shadowbox.css');
		}
		if ('Y' == $arSettings['colorbox']) {
			$asset->addJs('/yenisite.resizer2/js/colorbox/js/jquery.colorbox-min.js');
			//$asset->addCss('/yenisite.resizer2/js/colorbox/skins/skin1/colorbox.css');
		}
		if ('Y' == $arSettings['skitter']) {
			$asset->addJs('/yenisite.resizer2/js/Skitter/js/jquery.easing.1.3.js');
			$asset->addJs('/yenisite.resizer2/js/Skitter/js/jquery.animate-colors-min.js');
			//$asset->addJs('/yenisite.resizer2/js/Skitter/js/jquery.skitter.min.js');

			$asset->addCss('/yenisite.resizer2/js/Skitter/css/skitter.styles.css');
		}
		if ('Y' == $arSettings['cloudzoom']) {
			$asset->addJs('/yenisite.resizer2/js/cloudZoom/cloud-zoom.1.0.2.js');
			$asset->addCss('/yenisite.resizer2/js/cloudZoom/cloud-zoom.css');
		}
		if ('Y' == $arSettings['zoomy']) {
			$asset->addJs('/yenisite.resizer2/js/zoomy/jquery.zoomy1.2.min.js');
			$asset->addCss('/yenisite.resizer2/js/zoomy/zoomy1.2.css');
		}
		if ('Y' == $arSettings['ad']) {
			$asset->addJs('/yenisite.resizer2/js/ADgallery/jquery.ad-gallery.min.js');
			$asset->addCss('/yenisite.resizer2/js/ADgallery/jquery.ad-gallery.css');
		}
		if ('Y' == $arSettings['hoverpulse']) {
			$asset->addJs('/yenisite.resizer2/js/hoverpulse/jquery.hoverpulse.js');
		}
		if ('Y' == $arSettings['pika']) {
			$asset->addJs('/yenisite.resizer2/js/Pikachoose/js/jquery.pikachoose.js');
			$asset->addJs('/yenisite.resizer2/js/Pikachoose/js/jquery.jcarousel.min.js');
		}
		if ('Y' == $arSettings['coin']) {
			$asset->addJs('/yenisite.resizer2/js/Coin/coin-slider.min.js');
			$asset->addCss('/yenisite.resizer2/js/Coin/coin-slider-styles.css');
		}
		if ('Y' == $arSettings['spacegallery']) {
			$asset->addJs('/yenisite.resizer2/js/spacegallery/eye.js');
			$asset->addJs('/yenisite.resizer2/js/spacegallery/utils.js');
			$asset->addJs('/yenisite.resizer2/js/spacegallery/spacegallery.js');
			$asset->addCss('/yenisite.resizer2/js/spacegallery/custom.css');
			$asset->addCss('/yenisite.resizer2/js/spacegallery/spacegallery.css');
		}
		if ('Y' == $arSettings['pirobox']) {
			//$asset->addJs('/yenisite.resizer2/js/pirobox/jquery-ui-1.8.2.custom.min.js');
			$asset->addJs('/yenisite.resizer2/js/pirobox/pirobox_extended_min.js');
		}
		if ('Y' == $arSettings['elevateZoom']) {
			$asset->addJs('/yenisite.resizer2/js/elevateZoom/jquery.elevatezoom.js');
		}
		if ('Y' == $arSettings['jQZoom']) {
			$asset->addCss('/yenisite.resizer2/js/jQZoom/jquery.jqzoom.css');
			$asset->addJs('/yenisite.resizer2/js/jQZoom/jquery.jqzoom-core.js');
		}
		if ('Y' == $arSettings['fancyZoom']) {
			$asset->addJs('/yenisite.resizer2/js/fancyZoom/jquery.fancyzoom.min.js');
			$asset->addJs('/yenisite.resizer2/js/fancyZoom/jquery.ifixpng.js');
			$asset->addJs('/yenisite.resizer2/js/fancyZoom/jquery.shadow.js');
		}
		if ('Y' == $arSettings['zoom']) {
			$asset->addJs('/yenisite.resizer2/js/zoom/jquery.zoom-min.js');
			$asset->addCss('/yenisite.resizer2/js/zoom/zoom.css');
		}
		if ('Y' == $arSettings['superbox']) {
			$asset->addJs('/yenisite.resizer2/js/superbox/jquery.superbox.js');
			$asset->addCss('/yenisite.resizer2/js/superbox/jquery.superbox.css');
		}
	}

	/**
	 * ???
	 * @param string $content ???
	 */
	public static function replaceResizer2Content(&$content)
	{
		if (CSite::InDir("/bitrix/")) return;

		$resize_class           = COption::GetOptionString("yenisite.resizer2", "resize_class", "");
		$resize_class_classname = COption::GetOptionString("yenisite.resizer2", "resize_class_classname", "");
		$resize_class_set_small = COption::GetOptionString("yenisite.resizer2", "resize_class_set_small", "");
		$resize_class_set_big   = COption::GetOptionString("yenisite.resizer2", "resize_class_set_big",   "");
		$resize_wm     = COption::GetOptionString("yenisite.resizer2", "resize_wm",     "");
		$resize_wm_set = COption::GetOptionString("yenisite.resizer2", "resize_wm_set", "");

		if ($resize_class == "Y" && $resize_class_classname && $resize_class_set_small) {
			$content = CResizer2Resize::imgTagClassResize($resize_class_classname, $content, $resize_class_set_small, $resize_class_set_big);
		}

		if ($resize_wm == "Y" && $resize_wm_set ) {
			$content = CResizer2Resize::imgTagWH($content, $resize_wm_set);
		}
	}

	/**
	 * Add button to bitrix visual editor
	 * @return array|void Path list to additional files for old editor or void for new editor
	 */
	public static function HTMLEditorButton()
	{
		$bNewEditor = COption::GetOptionString("fileman", "use_editor_3", "");
		$uploadFolder = '/'.COption::GetOptionString('main','upload_dir', 'upload').'/';
		CAdminFileDialog::ShowScript(
			array(
				"event" => "OpenImageResizer2",
				"arResultDest" => array("FUNCTION_NAME" => "getImageUrl"),
				"arPath" => array("PATH" => $uploadFolder),
				"select" => 'F',
				"operation" => 'O',
				"showUploadTab" => true,
				"showAddToMenuTab" => false,
				"fileFilter" => 'image',
				"allowAllFiles" => true,
				"saveConfig" => true
			)
		);

		CMedialib::ShowDialogScript(
			array(
				"event" => "OpenImageResizer3",
				"arResultDest" => array("FUNCTION_NAME" => "getImageUrlMediaLibrary"),
				"arPath" => array("PATH" => $uploadFolder),
				"select" => 'F',
				"operation" => 'O',
				"showUploadTab" => true,
				"showAddToMenuTab" => false,
				"fileFilter" => 'image',
				"allowAllFiles" => true,
				"saveConfig" => true
			)
		);

		if (!$bNewEditor) {
			return array(
				"JS" => array('button.php'),
			);
		}

		if (defined('ADMIN_SECTION')) {
			IncludeModuleLangFile(__FILE__);
			include 'include/htmleditorbutton.php';
		}
	}

	/**
	 * Compress images of given iblock element
	 * @param array $arFields - Must contain keys 'ID' and 'IBLOCK_ID'
	 */
	public static function CompressImages($arFields)
	{
		$resize_compress_property = COption::GetOptionString('yenisite.resizer2', 'resize_compress_property', '');
		$obEl = CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
		$arProps = $obEl->GetProperties();
		if (!$arProps[$resize_compress_property]['VALUE']) return;

		$resize_compress_set = COption::GetOptionString('yenisite.resizer2', 'resize_compress_set', '');
		if (intval($resize_compress_set) <= 0) return;

		$arSet = CResizer2Set::GetByID($resize_compress_set);
		if (empty($arSet) || !is_array($arSet)) return;

		$check = 0;
		$i = 0;
		foreach ($arProps[$resize_compress_property]['VALUE'] as $v) {
			$picture['SIZE'] = CFile::GetFilearray($v);

			if ($picture['SIZE']['HEIGHT'] > $arSet['h'] || $picture['SIZE']['WIDTH'] > $arSet['w']) {
				$picture['PATH'][$i] = CFile::GetPath($v);
				$picture['PATH'][$i] = CResizer2Resize::ResizeGD2($picture['PATH'][$i], $resize_compress_set);

				$arFile[$i] = array(
					"VALUE" => CFile::MakeFilearray($_SERVER["DOCUMENT_ROOT"] . $picture['PATH'][$i]),
					"DESCRIPTION" => $arProps[$resize_compress_property]['DESCRIPTION'][$i]
				);
				CFile::Delete($v);
				$arDelete[$i] = $picture['PATH'][$i];
				$check = 1;
			} else {
				$arFile[$i] = array(
					"VALUE" => CFile::MakeFilearray($_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($v)),
					"DESCRIPTION" => $arProps[$resize_compress_property]['DESCRIPTION'][$i]
				);
				$arDelete[$i] = CFile::GetPath($v);
			}
			$i++;
		}

		if ($check != 0) {
			CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], array($resize_compress_property => $arFile));
			foreach ($arDelete as $v) {
				DeleteDirFilesEx($v);
			}
		}
	}
}

/**
 * Work with module settings table
 */
class CResizer2Settings extends CResizer2
{
	const CACHE_ID = 'resizer2_setings';
	const CACHE_DIR = '/resizer2/setings';

	/**
	 * Get array of all module settings
	 * @return array List of settings (name => value)
	 */
	public static function GetSettings()
	{
		$obCache = new CPHPCache();
		if ($obCache->InitCache(self::CACHE_TIME, self::CACHE_ID, self::CACHE_DIR)) {
			$arResult = $obCache->GetVars();
		} else {
			global $DB;
			$arResult = array();
			$strSql = 'SELECT * FROM yen_resizer2_settings';
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);

			while ($setting = $res->GetNext()) {
				$arResult[$setting['NAME']] = $setting['VALUE'];
			}
			if ($obCache->StartDataCache()) {
				if (defined("BX_COMP_MANAGED_CACHE")) {
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache(self::CACHE_DIR);
					$CACHE_MANAGER->RegisterTag(self::CACHE_ID);
					$CACHE_MANAGER->EndTagCache();
				}
				$obCache->EndDataCache($arResult);
			}
		}
		return $arResult;
	}

	/**
	 * Get value of module setting by its name
	 * @param string $name - Name of setting
	 * @return string - Value of setting
	 */
	public static function GetSettingByName($name)
	{
		$obCache = new CPHPCache();
		if ($obCache->InitCache(self::CACHE_TIME, self::CACHE_ID, self::CACHE_DIR)) {
			$arSettings = $obCache->GetVars();

			if (isset($arSettings[$name])) {
				return $arSettings[$name];
			}
		}

		global $DB;
		$name = $DB->ForSql($name);
		$strSql = "SELECT `VALUE` FROM `yen_resizer2_settings` WHERE `NAME`='{$name}'";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);

		if ($setting = $res->GetNext()) {
			return $setting['VALUE'];
		}
		return false;
	}

	/**
	 * Check existing of setting by its name
	 * @param string $name - name of setting
	 * @return bool
	 */
	public static function checkSettingByName($name)
	{
		$obCache = new CPHPCache();
		if ($obCache->InitCache(self::CACHE_TIME, self::CACHE_ID, self::CACHE_DIR)) {
			$arSettings = $obCache->GetVars();
			if (isset($arSettings[$name])) return true;
		}

		global $DB;
		$name = $DB->ForSql($name);
		$strSql = "SELECT NAME FROM yen_resizer2_settings WHERE NAME='{$name}'";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);

		return (0 < $res->SelectedRowsCount());
	}

	/**
	 * Get list of available fonts
	 * @return array
	 */
	public static function GetFontList()
	{
		$fileList = scandir("{$_SERVER['DOCUMENT_ROOT']}/yenisite.resizer2/fonts/");
		$result = array();
		foreach ($fileList as $file) {
			if ($file != '.' && $file != '..') {
				$result[] = $file;
			}
		}
		return $result;
	}

	/**
	 * Set value for one setting
	 *
	 * @param string $name
	 * @param string $value
	 */
	public static function SetSettingByName($name, $value)
	{
		if (empty($name) || !isset($value)) return;

		global $DB;
		$_name = $DB->ForSql($name);
		$value = $DB->ForSql($value);
		if (self::checkSettingByName($name)) {
			$strSql = "UPDATE yen_resizer2_settings SET VALUE='{$value}' WHERE NAME='{$_name}'";
		} else {
			$strSql = "INSERT INTO yen_resizer2_settings (NAME, VALUE) VALUES('{$_name}', '{$value}')";
		}
		$DB->Query($strSql, false, $err_mess.__LINE__);

		if (defined("BX_COMP_MANAGED_CACHE")) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag(self::CACHE_ID);
		}
	}

	/**
	 * Delete setting by given name
	 * @param string $name
	 * @return bool
	 */
	public static function DeleteSettingByName($name)
	{
		if (empty($name)) return;

		global $DB;
		$name = $DB->ForSql($name);
		$strSql = "DELETE FROM yen_resizer2_settings WHERE NAME='{$name}'";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$bDelete = ($res->AffectedRowsCount() > 0);

		if ($bDelete && defined("BX_COMP_MANAGED_CACHE")) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag(self::CACHE_ID);
		}
		return $bDelete;
	}
}

/**
 * Resize methods
 */
class CResizer2Resize extends CResizer2
{
	const CACHE_DIR = '/resizer2/images/';

	/**
	 * Place watermark on resized image
	 * @param resource $main_img_obj
	 * @param resource $watermark_img_obj
	 * @param int $dest_x
	 * @param int $dest_y
	 * @param array $arSettings
	 * @return resource
	 */
	public static function WmGDPng($main_img_obj, $watermark_img_obj, $dest_x, $dest_y, $arSettings)
	{
		$angle  = $arSettings['angle'];
		$factor = $arSettings['fs'];
		$alpha_level = $arSettings['opacity'] ?: 0;

		$main_x = imagesx($main_img_obj);
		$main_y = imagesy($main_img_obj);
		$factor = $factor / 100;

		$watermark_img_obj_tmp = imagerotate($watermark_img_obj, $angle, imageColorAllocateAlpha($watermark_img_obj, 0, 0, 0, 127));
		if ($watermark_img_obj_tmp !== false) {
			// clear memory
			imagedestroy($watermark_img_obj);
			// replace with rotated image
			$watermark_img_obj = $watermark_img_obj_tmp;
		}
		$watermark_width = imagesx($watermark_img_obj);
		$watermark_height = imagesy($watermark_img_obj);

		$new_watermark_width = $watermark_width * $factor > $main_x ? $main_x : $watermark_width * $factor;
		$new_watermark_height = $watermark_height * $factor > $main_y ? $main_y : $watermark_height * $factor;

		$dest_x = $dest_x - $new_watermark_width / 2;
		$dest_y = $dest_y - $new_watermark_height / 2;

		// if watermark beyond the left & top borders
		$dest_x = ($dest_x < 0) ? 0 : $dest_x;
		$dest_y = ($dest_y < 0) ? 0 : $dest_y;

		// if watermark beyond the right & bottom borders
		$dest_x = (($dest_x + $new_watermark_width) > $main_x) ? ($main_x - $new_watermark_width) : $dest_x;
		$dest_y = (($dest_y + $new_watermark_height) > $main_y) ? ($main_y - $new_watermark_height) : $dest_y;

		//CHECK MARGINS
		$mLeft = $arSettings['left_margin'];
		$mRight = $arSettings['right_margin'];
		$mTop = $arSettings['top_margin'];
		$mBottom = $arSettings['bottom_margin'];

		//left & right
		if ($mLeft + $mRight + $new_watermark_width > $main_x) {
			$mLeft = ($main_x - $new_watermark_width) * $mLeft / ($mLeft + $mRight);
			$mRight = ($main_x - $new_watermark_width) * $mRight / ($mLeft + $mRight);
		}
		$dest_x = $dest_x < $mLeft ? $mLeft : $dest_x;
		$dest_x = ($main_x - $dest_x - $new_watermark_width) < $mRight
		        ? $dest_x - $mRight - ($main_x - ($dest_x + $new_watermark_width))
		        : $dest_x;
		//top & bottom
		if ($mTop + $mBottom + $new_watermark_height > $main_y) {
			$tmp = ($main_y - $new_watermark_height) / ($mTop + $mBottom);
			$mTop *= $tmp;
			$mBottom *= $tmp;
		}
		$dest_y = $dest_y < $mTop ? $mTop : $dest_y;
		$dest_y = ($main_y - ($dest_y + $new_watermark_height)) < $mBottom
		        ? $dest_y - $mBottom - ($main_y - ($dest_y + $new_watermark_height))
		        : $dest_y;

		// TODO: use $alpha_level for watermark
		$img_buf = $watermark_img_obj;
		imagecopyresampled($main_img_obj, $img_buf, $dest_x, $dest_y, 0, 0, $new_watermark_width, $new_watermark_height, $watermark_width, $watermark_height);

		return $main_img_obj;
	}

	/**
	 * Convert hex color to rgb model
	 * @param string $color - Hex color
	 * @return array|false Array of decimal values of RGB model(R, G, B)
	 */
	public static function Hex2Rgb($color)
	{
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}
		$length = strlen($color);
		if ($length == 6) {
			$arReturn = array(
				$color[0].$color[1],
				$color[2].$color[3],
				$color[4].$color[5]
			);
		}
		elseif ($length == 3) {
			$arReturn = array(
				$color[0].$color[0],
				$color[1].$color[1],
				$color[2].$color[2]
			);
		}
		else {
			return false;
		}

		return array_map('hexdec', $arReturn);
	}

	/**
	 * @deprecated No longer used by internal code and not recommended.
	 * @see CResizer2Resize::GetCacheArray() Use this method instead.
	 */
	public static function GetCacheID($image_url, $set_id, $ws = 0, $hs = 0)
	{
		$image_url = str_replace($_SERVER['DOCUMENT_ROOT'], "", $image_url);
		$image_url = md5($image_url);

		if (!is_numeric($set_id))
			$set_id = "";
		if ($ws > 0 || $hs > 0)
			$set_id = intval($set_id)."_".$ws."-".$hs;

		return "{$set_id}_{$image_url}";
	}

	/**
	 * Get cache directory for given set and size parameters
	 *
	 * @param int|string $setId - Resizer set identifier
	 * @param int $ws Image width (optional)
	 * @param int $hs Image height (optional)
	 *
	 * @return string Relative cache directory
	 */
	public static function GetSetDir($setId, $ws = 0, $hs = 0)
	{
		$setId = (int)$setId;

		if ($ws > 0 || $hs > 0) {
			$setId .= '_' . (int)$ws . '-' . (int)$hs;
		}
		return $setId;
	}

	/**
	 * Get array with cache info for resized image
	 *
	 * @param string $imageUrl - original image path
	 * @param int $setId - resizer set identifier
	 * @param int $ws (optional)
	 * @param int $hs (optional)
	 * @param bool $bStatic (optional) true - add /static/ subdirectory
	 *
	 * @return array Associative array with next keys
	 * 'KEY' => file cache key
	 * 'EXT' => file extension
	 * 'NAME' => file full name
	 * 'DIR' => directory with file relative to DOCUMENT_ROOT
	 * 'PATH' => file path relative to DOCUMENT_ROOT
	 * 'ABSOLUTE_DIR' => absolute path to directory with file
	 * 'ABSOLUTE_PATH' => absolute path to file
	 */
	public static function GetCacheArray($imageUrl, $setId, $ws = 0, $hs = 0, $bStatic = false)
	{
		$key = md5(str_replace($_SERVER['DOCUMENT_ROOT'], "", $imageUrl));
		$arReturn = array('KEY' => $key);

		$setId = (int)$setId;
		if ($setId > 0) {
			$arSet = CResizer2Set::GetByID($setId);
			if ($arSet['conv']) {
				$ext = $arSet['conv'];
			}
		}
		if (empty($ext)) {
			$ext = substr(strrchr($imageUrl, '.'), 1);
		}

		$arReturn['SET_DIR'] = ($bStatic ? 'static/' : '') . self::GetSetDir($setId, $ws, $hs);

		$arReturn['EXT']  = strtolower($ext);
		$arReturn['NAME'] = $key . '.' . $arReturn['EXT'];

		$arReturn['DIR']  = '/upload/resizer2/' . $arReturn['SET_DIR'] . '/' . substr($key, 0, 3);
		$arReturn['PATH'] = $arReturn['DIR'] . '/' . $arReturn['NAME'];

		$arReturn['ABSOLUTE_DIR']  = $_SERVER['DOCUMENT_ROOT'] . $arReturn['DIR'];
		$arReturn['ABSOLUTE_PATH'] = $_SERVER['DOCUMENT_ROOT'] . $arReturn['PATH'];

		return $arReturn;
	}

	/**
	 * Delete all resized images
	 *
	 * @param bool $showMessage - Print deleted set names
	 * @param bool $bStatic - Delete cache for static pages
	 */
	public static function ClearCacheAll($showMessage = true, $bStatic = false)
	{
		self::ClearCacheByID(0);
		self::ClearCacheByID(''); // backward compatibility to delete old cache

		$arSets = CResizer2Set::GetList();
		while ($arr = $arSets->Fetch()) {
			self::ClearCacheByID($arr["id"], 0, 0, $bStatic);
			if ($showMessage) echo GetMessage('DROP')." ".$arr['NAME']."<br/>";
		}
	}

	/**
	 * Delete all images of given set
	 *
	 * @param int $setId - Resizer set identifier
	 * @param int $ws
	 * @param int $hs
	 */
	public static function ClearCacheByID($setId, $ws = 0, $hs = 0, $bStatic = false)
	{
		static $uploadDir;
		$uploadDir = '/'.COption::GetOptionString('main','upload_dir', 'upload').'/resizer2/';
		$absDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

		$setDir = self::GetSetDir($setId, $ws, $hs);
		SetFileTable::deleteBySet($setDir);

		// remove new cache directory (since 3.0.0)
		DeleteDirFilesEx($uploadDir . $setDir);

		if ($bStatic) {
			// remove cache from visual editor for static pages
			$setDir = 'static/' . $setDir;
			SetFileTable::deleteBySet($setDir);
			DeleteDirFilesEx($uploadDir . $setDir);
		}

		// remove old cache files (before 3.0.0)
		$prefix = $setId . '_';
		$length = strlen($prefix);

		$fileList = scandir($absDir);
		foreach ($fileList as $file) {
			if (substr($file, 0, $length) !== $prefix) continue;

			$path = $absDir . $file;
			if (is_dir($path)) {
				DeleteDirFilesEx($uploadDir . $file);
			} else {
				unlink($path);
			}
		}

		// clear managed cache
		if (defined("BX_COMP_MANAGED_CACHE")) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag(CResizer2Set::CACHE_ID . $setId);
		}
		else {
			BXClearCache(true, CResizer2Set::CACHE_DIR);
		}
	}

	/**
	 * Delete resized image for given original image
	 *
	 * @param string $image_url - Path to original image
	 * @param int $set_id - Resizer set identifier
	 * @param int $ws (optional)
	 * @param int $hs (optional)
	 */
	public static function ClearImgCache($image_url, $set_id, $ws = 0, $hs = 0)
	{
		$arCache = self::GetCacheArray($image_url, $set_id, $ws, $hs);
		if (file_exists($arCache['ABSOLUTE_PATH'])) {
			unlink($arCache['ABSOLUTE_PATH']);
		}
	}

	/**
	 * Get font size
	 *
	 * @param string $text
	 * @param int $w
	 * @param int $h
	 * @param string $font
	 * @param float $angle
	 *
	 * @return int
	 */
	public static function GetFontSize($text, $w, $h, $font, $angle)
	{
		$size = 1;
		$box  = imagettfbbox($size, 0, $font, $text );

		while (abs($box[4]) < $w && $size < 200) {
			$size += 1;
			$box = imagettfbbox($size, 0, $font, $text );
		}

		return $size;
	}

	/**
	 * ???
	 *
	 * @param string $className
	 * @param string $content
	 * @param int $set
	 * @param int $set_big
	 *
	 * @return string
	 */
	public static function imgTagClassResize($className, $content, $set = 0, $set_big = 0)
	{
		$matches = array();
		$ptrnBefore = '#<img([^<>]+)class="' . $className . '"([^<>]+)src="([^"]+)"([^<>]+)>#';
		preg_match_all($ptrnBefore, $content, $matches);

		$src = $matches[3];
		$ptrnAfter = '#<img([^<>]+)src="([^"]+)"([^<>]+)class="' . $className . '"([^<>]+)>#';
		preg_match_all($ptrnAfter, $content, $matches);

		$src = array_merge($src, $matches[2]);
		if ($set_big) {
			$tmplBefore = '<a class="resizer2fancy" href="BIG:\3"><img\1class="' . $className . '"\2src="\3"\4></a>';
			$tmplAfter = '<a class="resizer2fancy" href="BIG:\2"><img\1src="\2"\3class="' . $className . '"\4></a>';
			$content = preg_replace($ptrnBefore, $tmplBefore, $content);
			$content = preg_replace($ptrnAfter, $tmplAfter, $content);
		}
		$src = array_unique($src);
		foreach ($src as $sr) {
			$path = self::ResizeGD2($sr, $set);
			if ($set_big) {
				$path_big = self::ResizeGD2($sr, $set_big);
				$content = str_replace("BIG:".$sr, $path_big, $content);
			}
			$path = self::ResizeGD2($sr, $set);
			$content = str_replace($sr, $path, $content);
		}
		return $content;
	}

	/**
	 * ???
	 * @param string $str
	 * @return string
	 */
	public static function getImgAttr($str)
	{
		$arr = explode(" ", $str);
		$attr = array();
		foreach ($arr as $a) {
			if (substr_count($a, "=") > 0) {
				$arri = explode("=", $a);
				if ($arri[0] && $arri[1]) {
					$attr[$arri[0]] = $at = str_replace("\"", "",  $arri[1]);
				}
			}
		}
		return $attr;
	}

	/**
	 * Create image tag with given attributes
	 * @param string[] $attr
	 * @return string
	 */
	public static function setImgAttr($attr)
	{
		$img = "<img";
		foreach ($attr as $k=>$at) {
			$at = str_replace("\"", "", $at);
			$img .= " {$k}=\"{$at}\"";
		}
		$img .= "/>";
		return $img;
	}

	/**
	 * ???
	 *
	 * @param string $content
	 * @param int $set
	 *
	 * @return string
	 */
	public static function imgTagWH($content, $set)
	{
		$matches = array();
		$ptrnBefore = "#<img(.*?)>#";
		preg_match_all($ptrnBefore, $content, $matches);
		foreach ($matches[0] as $k=>$im) {
			$attr = self::getImgAttr($im);
			if ($attr["width"] <= 0 && $attr["height"] <= 0) continue;

			list($w, $h) = getimagesize($_SERVER["DOCUMENT_ROOT"].$attr["src"]);
			if ($w <= $attr["width"] && $h <= $attr["height"]) continue;

			$attr["src"] =  self::ResizeGD2($attr["src"], $set, $attr["width"], $attr["height"]);
			unset($attr["width"]);
			unset($attr["height"]);
			$img = self::setImgAttr($attr);
			$content = str_replace($im, $img, $content);
		}
		return $content;
	}

	/**
	 * Create resized image
	 *
	 * @param string $image_url Path to original image
	 * @param int $set_id Resizer set identifier
	 * @param int $ws (optional)
	 * @param int $hs (optional)
	 * @param bool $bStatic - (optional) true will separate resized image to different folder and prevent from cache auto deletion
	 *
	 * @return string Path to resized image
	 */
	public static function ResizeGD2($image_url, $set_id, $ws = 0, $hs = 0, $bStatic = false,$q = 100)
	{
		global $APPLICATION, $CACHE_MANAGER;

		// normalize image url
		if (!defined('BX_UTF')) {
			$image_url = $APPLICATION->ConvertCharset($image_url, LANG_CHARSET, 'utf-8');
		}
		if (!empty($image_url)) {
			if (substr($image_url, 0, 4) === 'http') {
				$image_path = $image_url;
			} else {
				$image_path = $_SERVER['DOCUMENT_ROOT'] . $image_url;
			}
			$image_info = @getimagesize($image_path);
		}

		// check original image existance
		if (is_dir($image_path) || !is_array($image_info) || strstr($image_info['mime'],'image') == false) {
			// switch to placeholder image
			$arSettings = CResizer2Settings::GetSettings();
			$no_path = CFile::GetPath($arSettings['no_image']);

			if (substr($no_path, 0, 4) !== 'http') {
				$no_path = $_SERVER['DOCUMENT_ROOT'].$no_path;
			}

			$fd_no = fopen($no_path, "r");
			$dd_no = is_dir($no_path);
			if (!$fd_no || $dd_no) return;

			fclose($fd_no);
			$image_path = $no_path;
			$image_info = getimagesize($image_path);
			$use_no_photo_image = true;
		}

		// work with cache
		$obCache = new CPHPCache;
		$arCache = self::GetCacheArray($image_path, $set_id, $ws, $hs, $bStatic);

		$cacheTime = 31536000; // 1 year
		$cacheDir = self::CACHE_DIR . $arCache['SET_DIR'];

		if ($obCache->InitCache($cacheTime, $arCache['KEY'], $cacheDir)) {
			return $obCache->GetVars();
		}
		if ($bCacheStarted = $obCache->StartDataCache($cacheTime, $arCache['KEY'], $cacheDir)) {
			if (defined('BX_COMP_MANAGED_CACHE')) {
				$CACHE_MANAGER->StartTagCache($cacheDir);
			}
		}

		$arSettings = CResizer2Settings::GetSettings(); // marks cache with tag

		$arDefaultSet = array(
			'id' => 0,
			'w' => 400,
			'h' => 600,
			'priority' => 'FILL',
			'wm' => 'Y',
			'q' => 100,
		);
		if ((int)$set_id > 0) {
			$arSet = CResizer2Set::GetByID($set_id); // marks cache with tag
		} else {
			$CACHE_MANAGER->RegisterTag(CResizer2Set::CACHE_ID . '0');
		}
		if (!is_array($arSet)) {
			$arSet = array();
		}
		$arSet = array_merge($arDefaultSet, $arSet);

		if ($arSet['conv']) {
			//esli ystanovlen format dlya konvertacii kartinok
			$image_ext = $arSet['conv'];
		} else {
			$acceptedFormats =  array(
				'image/gif'      => 'gif',
				'image/pjpeg'    => 'jpg',
				'image/jpg'      => 'jpg',
				'image/jpeg'     => 'jpg',
				'image/x-png'    => 'png',
				'image/png'      => 'png',
				'image/x-ms-bmp' => 'bmp',
				'image/bmp'      => 'bmp'
			);
			foreach ($acceptedFormats as $k => $v) {
				if (strpos(strtolower($image_info['mime']), $k) !== false) {
					$image_ext = $v;
					break;
				}
			}
		}
		if ($arCache['EXT'] != $image_ext) {
			$arCache = str_replace('.' . $arCache['EXT'], '.' . $image_ext, $arCache);
			$arCache['EXT'] = $image_ext;
		}

		if (defined('BX_COMP_MANAGED_CACHE') && $bCacheStarted) {
			$CACHE_MANAGER->EndTagCache();
		}

		// check resized image existance
		$path = false;

		do {
			if (
				file_exists($arCache['ABSOLUTE_PATH']) &&
				0 < filesize($arCache['ABSOLUTE_PATH'])
			) {
				$path = $arCache['PATH'];
				break;
			}

			$fileId = SetFileTable::getFile($arCache['SET_DIR'], $arCache['KEY']);

			if (0 < (int)$fileId) {
				$path = CFile::GetPath($fileId);
			}
		} while (0);
		$path = preg_replace('#^https?:#', '', $path);
		if ($path) {
			if ($bCacheStarted) {
				$obCache->EndDataCache($path);
			}
			return $path;
		}

		// create resized image
		$dx = 10;
		$dy = 10;

		if (!$arSettings['fill']) {
			$arSettings['fill'] = '#ffffff';
		}
		//esli vibran nabor
		if ($set_id > 0) {
			//i y nego est' nastroiki watermark
			if ($arSet['watermark_settings']) {
				$wms = unserialize(base64_decode(trim($arSet['watermark_settings'])));
				foreach ($wms as $k => $w) {
					$arSettings[$k] = $w;
				}
			}
		}

		if (SITE_CHARSET == 'windows-1251') {
			$arSettings['text'] = iconv('windows-1251', 'utf-8', $arSettings['text']);
		}

		if ($ws > 0) $arSet["w"] = $ws;
		if ($hs > 0) $arSet["h"] = $hs;
        if ($q > 0) $arSet["q"] = $q;

		$fn = strtolower($image_info['mime']);

		switch (true) {
			case (strrpos($fn, 'png') !== false): $im =        imagecreatefrompng($image_path); break;
			case (strrpos($fn, 'gif') !== false): $im =        imagecreatefromgif($image_path); break;
			case (strrpos($fn, 'bmp') !== false): $im = CFile::ImageCreateFromBMP($image_path); break;
			default:                              $im =       imagecreatefromjpeg($image_path); break;
		}

		imagealphablending($im, false);
		imagesavealpha($im, true);

		$im_width  = imagesx($im);
		$im_height = imagesy($im);

		$new_im_width  = $arSet['w'];
		$new_im_height = $arSet['h'];

		$sX = 0;
		$sY = 0;

		switch ($arSet['priority']) {
			case "HEIGHT":
				if ($arSet['h'] < $im_height) {
					$factor = $arSet['h'] / $im_height;
					$new_im_width = intval($im_width * $factor);
					$arSet['w'] = intval($im_width * $factor);
				}
				break;

			case "WIDTH":
				if ($arSet['w'] < $im_width) {
					$factor = $arSet['w'] / $im_width;
					$new_im_height = intval($im_height * $factor);
					$arSet['h'] = intval($im_height * $factor);
				}
				break;

			case "CROP":
				if (
					$arSet['w'] >= $im_width &&
					$arSet['h'] >= $im_height &&
					$arSettings['not_increase'] == 'Y'
				) break;

				if ($im_width / $im_height >= $arSet['w'] / $arSet['h']) {
					$factor = $im_height / $arSet['h'];
					$sX = ($im_width - $arSet['w'] * $factor) / 2;
					$im_width = $arSet['w'] * $factor;
				} else {
					$factor = $im_width / $arSet['w'];
					$sY = ($im_height - $arSet['h'] * $factor) / 2;
					$im_height = $arSet['h'] * $factor;
				}
				break;

			case "FIT_LARGE":
				if ($im_width / $im_height >= $arSet['w'] / $arSet['h']) {
					$factor = $arSet['w'] / $im_width;
					$arSet['h'] = intval($im_height * $factor);
				} else {
					$factor = $arSet['h'] / $im_height;
					$arSet['w'] = intval($im_width * $factor);
				}
				break;

			case "CWIDTH":
				if ($arSet['h'] > $im_height) {
					$arSet['h'] = $im_height;
				}
				$factor = $arSet['h'] / $im_height;
				$new_im_width = intval($im_width * $factor);
				$new_im_height = $arSet['h'];

				$dx = ($arSet['w'] - $new_im_width)  / 2;
				$dy = ($arSet['h'] - $new_im_height) / 2;

				$new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);

				list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
				$color = imagecolorallocate($new_im_buf, $r, $g, $b);
				imagefill($new_im_buf, 0, 0, $color);
				imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);

				$im_width  = imagesx($new_im_buf);
				$im_height = imagesy($new_im_buf);
				break;

			case "CHEIGHT":
			 	if ($arSet['w'] > $im_width) {
					$arSet['w'] = $im_width;
			 	}
				$factor = $arSet['w'] / $im_width;
				$new_im_height = intval($im_height * $factor);
				$new_im_width = $arSet['w'];

				$dx = ($arSet['w'] - $new_im_width)  / 2;
				$dy = ($arSet['h'] - $new_im_height) / 2;

				$new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);

				list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
				$color = imagecolorallocate($new_im_buf, $r, $g, $b);
				imagefill($new_im_buf, 0, 0, $color);
				imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);

				$im_width  = imagesx($new_im_buf);
				$im_height = imagesy($new_im_buf);
				break;

			case "FILL":
				$factor = $arSet['h'] / $im_height;
				$new_im_width = $im_width * $factor;
				$new_im_height = intval($arSet['h']);

				if ($new_im_width > $arSet['w']) {
					$factor = $arSet['w'] / $new_im_width;
					$new_im_height = $new_im_height * $factor;
					$new_im_width = $arSet['w'];
				}

				$dx = ($arSet['w'] - $new_im_width)  / 2;
				$dy = ($arSet['h'] - $new_im_height) / 2;
				$new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);

				list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
				$color = imagecolorallocate($new_im_buf, $r, $g, $b);
				imagefill($new_im_buf, 0, 0, $color);
				imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);

				$im_width  = imagesx($new_im_buf);
				$im_height = imagesy($new_im_buf);
				break;

			default:
				if ($im_width / $im_height >= $arSet['w'] / $arSet['h']) {
					$factor = $arSet['w'] / $im_width;
					$arSet['h'] = intval($im_height * $factor);
				} else {
					$factor = $arSet['h'] / $im_height;
					$arSet['w'] = intval($im_width * $factor);
				}
				break;
		}

		// ----	 NOT increase img if width & height smaller than in SET
		$arPriorityNotIncreasImg = array('HEIGHT', 'WIDTH', 'FIT_LARGE', 'CROP');
		if (
			$arSet['w'] > $im_width &&
			$arSet['h'] > $im_height &&
			in_array($arSet['priority'], $arPriorityNotIncreasImg) &&
			$arSettings['not_increase'] == 'Y'
		) {
			$arSet['w'] = $im_width;
			$arSet['h'] = $im_height;
		}

		$new_im = imagecreatetruecolor($arSet['w'], $arSet['h']);

		// ----  FOR TRANSPERENT BACKGROUND OF IMAGE
		$transparent = imagecolorallocatealpha($new_im, 0, 0, 0, 127);
		imagefill($new_im, 0, 0, $transparent);
		imagesavealpha($new_im, true);

		if ($arSet['priority'] == 'FILL') {
			if (!$arSettings['fill']) {
				$arSettings['fill'] = '#ffffff';
			}
			list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
			$color = imagecolorallocate($new_im, $r, $g, $b);
			imagefill($new_im, 0, 0, $color);
		}

		if ($new_im_buf) {
			imagecopyresampled($new_im, $new_im_buf, $dx, $dy, 0, 0, $im_width, $im_height, $im_width, $im_height);
			imagedestroy($new_im_buf);
		} else {
			imagecopyresampled($new_im, $im, 0, 0, $sX, $sY, $arSet['w'], $arSet['h'], $im_width, $im_height);
		}
		// clear memory from original big image
		imagedestroy($im);

		// place watermark on resized image
		if (!$use_no_photo_image && $arSet['wm'] == 'Y') {
			if (!$arSettings['color']) {
				$arSettings['color'] = '#ffffff';
			}

			list($r, $g, $b) = self::Hex2Rgb($arSettings['color']);

			$c = imagecolorallocatealpha($new_im, $r, $g, $b, $arSettings['opacity'] ?: 0);
			$font_size = self::GetFontSize($arSettings['text'], $arSet['w'], $arSet['h'], "{$_SERVER['DOCUMENT_ROOT']}/yenisite.resizer2/fonts/{$arSettings['font_family']}", $arSettings['angle']);
			$font_size = $font_size * $arSettings['fs'] / 100;
			$box = imagettfbbox($font_size, 0, "{$_SERVER['DOCUMENT_ROOT']}/yenisite.resizer2/fonts/{$arSettings['font_family']}", $arSettings['text']."");
			$wt = abs($box[2]) + 1;
			$ht = abs($box[7]) + 1;

			if ($arSettings['image'] && $arSet['wm'] == 'Y')
			{
				$wmpath = $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arSettings['image']);

				$water = imagecreatefrompng($wmpath);
				$watermark_img_obj = imagerotate($water, $arSettings['angle'], -1);

				$wi = imagesx($watermark_img_obj) * $arSettings['fs'] / 100;
				$hi = imagesy($watermark_img_obj) * $arSettings['fs'] / 100;
			}

			$yi = 0;
			$xi = 0;

			switch($arSettings['place_v']) {
				case 'top':
					$y = $yi = $arSettings['top_margin'];
					if ($arSettings['angle'] > 270) {
						$y += abs(sin(deg2rad($arSettings['angle'] + 90)) * $ht);
						break;
					}
					if ($arSettings['angle'] > 180) {
						break;
					}
					if ($arSettings['angle'] > 90) {
						$y += abs(sin(deg2rad($arSettings['angle']))) * $wt;
						break;
					}
					$y += abs(sin(deg2rad($arSettings['angle']))) * $wt
					   +  abs(sin(deg2rad($arSettings['angle'] + 90)) * $ht);
					break;

				case 'center':
					$y  = imagesy($new_im) / 2;
					$yi = $y - $hi / 2;
					$y += sin(deg2rad($arSettings['angle'])) * $wt / 2;
					break;

				case 'bottom':
					$y  = imagesy($new_im) - $arSettings['bottom_margin'];
					$yi = $y - $hi;
					$y -= abs($box[5]);
					break;
			}

			$x = cos(deg2rad($arSettings['angle'])) * $wt;
			switch ($arSettings['place_h']) {
				case 'left':
					$xi = $arSettings['left_margin'];
					$x  = $x < 0 ? -$x : 0;
					$x += ($tmp = cos(deg2rad($arSettings['angle'] + 90)) * $ht) < 0 ? -$tmp : 0;
					$x -= $arSettings['left_margin'];
					break;

				case 'center':
					$tmp = imagesx($new_im) / 2;
					$xi = $tmp - $wi / 2;
					$x  = $tmp - $x  / 2;
					break;

				case 'right':
					$xi = imagesx($new_im) - $arSettings['right_margin'];
					if ($arSettings['angle'] < 90 || $arSettings['angle'] > 270) {
						$x = $xi - $x;
					} else {
						$x = $xi;
					}
					if ($arSettings['angle'] > 180) {
						$x = $x - cos(deg2rad($arSettings['angle'] + 90)) * $ht - $arSettings['right_margin'];
					}
					$xi -= $wi;
					break;
			}

			if ($arSettings['image']) {
				$new_im = CResizer2Resize::WmGDPng(
					$new_im,
					$water,
					$xi,
					$yi,
					$arSettings
				);
				// clear memory
				imagedestroy($watermark_img_obj);
				imagedestroy($water);
			} elseif ($arSettings['text']) {
				imagettftext(
					$new_im,
					$font_size,
					$arSettings['angle'],
					$x,
					$y,
					$c,
					"{$_SERVER['DOCUMENT_ROOT']}/yenisite.resizer2/fonts/{$arSettings['font_family']}",
					$arSettings['text']
				);
			}
		}

		// save resized image
		if (!is_dir($arCache['ABSOLUTE_DIR'])) {
			mkdir($arCache['ABSOLUTE_DIR'], 0777, true);
		}

		switch (strtolower($arCache['EXT'])) {
			case 'png': $imgResult = imagePng($new_im, $arCache['ABSOLUTE_PATH']); break;
			case 'gif': $imgResult = imageGif($new_im, $arCache['ABSOLUTE_PATH']); break;
			default:
				if ($arSet['w'] > 150 && $arSet['h'] > 150) {
					// Create progressive JPEG
					imageinterlace($new_im, 1);
				}
				$imgResult = imageJpeg($new_im, $arCache['ABSOLUTE_PATH'], $arSet['q']);
				break;
		}
		// clear memory
		imagedestroy($new_im);

		if (!$imgResult || !file_exists($arCache['ABSOLUTE_PATH'])) {
			// File create error
			if ($bCacheStarted) $obCache->AbortDataCache();
			return str_replace($_SERVER['DOCUMENT_ROOT'], '', $image_path);
		}

		$fileId = self::SaveFile($arCache, $arSet);

		$path = CFile::GetPath($fileId) ?: $arCache['PATH'];
		$path = preg_replace('#^https?:#', '', $path);
		
		if ($bCacheStarted) {
			$obCache->EndDataCache($path);
		}
		return $path;
	}

	/**
	 * Register resized image in Bitrix
	 *
	 * Imitates methods CFile::SaveFile() and CCloudStorage::OnFileSave()
	 *
	 * @param string[] $arFileCache Array with file resized cache info
	 * @param string[] $arSet Array with resizer set info
	 *
	 * @return int File identifier
	 */
	public static function SaveFile($arFileCache, $arSet)
	{
		static $uploadDir;
		$uploadDir = '/'.COption::GetOptionString('main','upload_dir', 'upload').'/';
		$arFile = CFile::MakeFilearray($arFileCache['ABSOLUTE_PATH']);

		$arFile['MODULE_ID'] = 'yenisite.resizer2';
		$arFile['ORIGINAL_NAME'] = GetFileName($arFile['name']);

		if ($arFile['type'] == 'image/pjpeg' || $arFile['type'] == 'image/jpg') {
			$arFile['type'] = 'image/jpeg';
		}

		$arFile['SUBDIR'] = str_replace($uploadDir, '', $arFileCache['DIR']);
		$arFile['FILE_NAME'] = $arFileCache['NAME'];

		do {
			// Try to save file into cloud storage
			// CCloudStorage::OnFileSave() emulation
			if (!Loader::includeModule('clouds')) break;

			$bucket = CCloudStorage::FindBucketForFile($arFile, $arFile['FILE_NAME']);

			if (!is_object($bucket)) break;
			if (!$bucket->Init()) break;

			$filePath = '/' . $arFile['SUBDIR'] . '/' . $arFile['FILE_NAME'];
			if (!$bucket->SaveFile($filePath, $arFile)) break;

			$arFile['HANDLER_ID'] = $bucket->ID;
			$bucket->IncFileCounter(filesize($arFile["tmp_name"]));

			// File has been succesfully moved into cloud, remove from local file system
			unlink($arFileCache['ABSOLUTE_PATH']);
			@rmdir($arFileCache['ABSOLUTE_DIR']);
		} while (0);

		$NEW_IMAGE_ID = CFile::DoInsert(array(
			"HEIGHT" => $arSet["h"],
			"WIDTH" => $arSet["w"],
			"FILE_SIZE" => $arFile["size"],
			"CONTENT_TYPE" => $arFile["type"],
			"SUBDIR" => $arFile["SUBDIR"],
			"FILE_NAME" => $arFile["FILE_NAME"],
			"MODULE_ID" => $arFile["MODULE_ID"],
			"ORIGINAL_NAME" => $arFile["ORIGINAL_NAME"],
			"DESCRIPTION" => '',
			"HANDLER_ID" => isset($arFile["HANDLER_ID"]) ? $arFile["HANDLER_ID"] : '',
			"EXTERNAL_ID" => isset($arFile["external_id"]) ? $arFile["external_id"] : md5(mt_rand()),
		));

		if (0 < (int)$NEW_IMAGE_ID) {
			SetFileTable::add(array(
				'KEY' => $arFileCache['KEY'],
				'SET_DIR' => $arFileCache['SET_DIR'],
				'FILE_ID' => $NEW_IMAGE_ID
			));
		}

		CFile::CleanCache($NEW_IMAGE_ID);
		return $NEW_IMAGE_ID;
	}
}


/**
 * Work with resizer sets table
 */
class CResizer2Set extends CResizer2
{
	const CACHE_ID = 'resizer2_set_';
	const CACHE_DIR = '/resizer2/sets';

	/**
	 * Update resizer set
	 * @param int $id Set identifier
	 * @param string $name Set name
	 * @param int $w Width
	 * @param int $h Height
	 * @param int $q JPEG quality (1 - 100)
	 * @param string $wm Use watermark ('Y', 'N')
	 * @param string $priority Resize mode
	 * @param string $conv Format to convert images to
	 */
	public static function Update($id, $name, $w = 800, $h = 600, $q = 100, $wm ='N' , $priority = 'WIDTH' , $conv = '')
	{
		global $DB;
		$wm   = $wm == 'Y' ? 'Y' : 'N';
		$id   = $DB->ForSql($id);
		$name = $DB->ForSql($name);
		$w    = $DB->ForSql($w);
		$h    = $DB->ForSql($h);
		$q    = $DB->ForSql($q);
		$wm   = $DB->ForSql($wm);
		$priority = $DB->ForSql($priority);
		$conv     = $DB->ForSql($conv);
		$strSql = "UPDATE yen_resizer2_sets SET name='{$name}', w='{$w}', h='{$h}', q='{$q}', wm='{$wm}', priority='{$priority}', conv='{$conv}' WHERE id={$id}";

		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
	}

	/**
	 * Get resizer set by id
	 *
	 * @param int $id Set identifier
	 *
	 * @return array
	 */
	public static function GetByID($id)
	{
		global $DB, $CACHE_MANAGER;

		$id = $DB->ForSql($id);
		if (strlen($id) == 0) return false;

		$bCacheStarted = false;
		$cache_ID = self::CACHE_ID . $id;

		$obCache = new CPHPCache();
		if ($obCache->InitCache(self::CACHE_TIME, $cache_ID, self::CACHE_DIR)) {
			$res = $obCache->GetVars();
		} else {
			$strSql = "SELECT * FROM yen_resizer2_sets WHERE id={$id}";
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
			$res = $res->GetNext();

			if ($bCacheStarted = $obCache->StartDataCache()) {
				if (defined("BX_COMP_MANAGED_CACHE")) {
					$CACHE_MANAGER->StartTagCache(self::CACHE_DIR);
				}
				$obCache->EndDataCache($res);
			}
		}
		$CACHE_MANAGER->RegisterTag($cache_ID);

		if ($bCacheStarted && defined('BX_COMP_MANAGED_CACHE')) {
			$CACHE_MANAGER->EndTagCache();
		}

		return $res;
	}

	/**
	 * Get resizer set by size and resize mode
	 *
	 * @todo update method to use $accuracy
	 *
	 * @param int $w Width
	 * @param int $h Height
	 * @param string $priority Resize mode
	 * @param int $accuracy 
	 *
	 * @return int|false
	 */
	public static function GetBySizeMode($w = 0, $h = 0, $priority = '', $accuracy = 0)
	{
		global $DB;

		$w = $DB->ForSql($w);
		$h = $DB->ForSql($h);
		$priority = $DB->ForSql($priority);
		if (intval($w) <= 0 && intval($h) <= 0) return false;

		$arWhere = array();
		if (0 < intval($w))        $arWhere[] = "w='{$w}'";
		if (0 < intval($h))        $arWhere[] = "h='{$h}'";
		if (0 < strlen($priority)) $arWhere[] = "priority='{$priority}'";
		$whereStr = implode(' AND ', $arWhere);

		$strSql = "SELECT * FROM yen_resizer2_sets WHERE ".$whereStr;
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$res = $res->GetNext();
		if (is_array($res) && isset($res['id'])) {
			return (int)$res['id'];
		}

		return $res;
	}

	/**
	 * Get all resizer sets
	 *
	 * @return array
	 */
	public static function GetList()
	{
		global $DB;
		$strSql = "SELECT * FROM yen_resizer2_sets";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		return $res;
	}

	/**
	 * Add resizer set
	 *
	 * @param string $name Set name
	 * @param int $w Width
	 * @param int $h Height
	 * @param int $q JPEG quality (1 - 100)
	 * @param string $wm Use watermark ('Y', 'N')
	 * @param string $priority Resize mode
	 * @param string $conv Format to convert images to
	 *
	 * @return int New set identifier
	 */
	public static function Add($name, $w = 800, $h = 600, $q = 100, $wm ='N' , $priority = 'WIDTH', $conv = '')
	{
		global $DB;
		$wm   = $wm == 'Y' ? 'Y' : 'N';
		$name = $DB->ForSql($name);
		$w    = $DB->ForSql($w);
		$h    = $DB->ForSql($h);
		$q    = $DB->ForSql($q);
		$wm   = $DB->ForSql($wm);
		$priority = $DB->ForSql($priority);
		$conv     = $DB->ForSql($conv);
		$strSql = "INSERT INTO yen_resizer2_sets(name, w, h, q, wm, priority,conv)  VALUES('{$name}', '{$w}', '{$h}', '{$q}', '{$wm}', '{$priority}', '{$conv}')";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);

		return $DB->LastID();
	}
}
?>
