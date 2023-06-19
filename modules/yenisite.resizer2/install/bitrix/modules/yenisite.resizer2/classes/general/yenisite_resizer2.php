<?
class CResizer2{
function ShowResizer2Head()
{

	global $APPLICATION;
	//if(substr_count($APPLICATION->GetCurDir(), '/bitrix/') == 0)
	//{
	    CModule::IncludeModule('yenisite.resizer2');	    
	    $arSettings = CResizer2Settings::GetSettings();
	    if($arSettings['jquery'] == 'Y'){
			//$APPLICATION->AddHeadScript('/yenisite.resizer2/js/jquery/jquery-1.6.1.min.js');   
			if(!is_object($GLOBALS["USER"])) //zaplatka na 12 bitrix (19.11.2012)
                $GLOBALS["USER"]=new CUser;			
			CJSCore::Init(array("jquery"));			
	    }
	    
	    if($arSettings['fancy'] == 'Y'){
		    $APPLICATION->AddHeadScript('/yenisite.resizer2/js/fancybox2/jquery.fancybox.pack.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-buttons.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/fancybox2/jquery.fancybox.css');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-buttons.css');
    		$APPLICATION->AddHeadScript('/yenisite.resizer2/js/resizer2/script.js');
			
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-thumbs.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/fancybox2/helpers/jquery.fancybox-thumbs.css');
			
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/fancybox2/helpers/jquery.mousewheel-3.0.6.pack.js');
	    }
	    
	    if($arSettings['lightbox'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/lightbox/lightbox.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/lightbox/lightbox.css');
	    }
		
		if($arSettings['nflightbox'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/nflightbox/nflightbox.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/nflightbox/nf.lightbox.css');
	    }
	    
	    if($arSettings['pretty'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/prettyphoto/js/jquery.prettyPhoto.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/prettyphoto/js/prettyPhoto.css');
	    }
		
	    if($arSettings['nyroModal'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/nyroModal/jquery.nyroModal.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/nyroModal/nyroModal.css');
	    }
		
	    if($arSettings['thickbox'] == 'Y'){        
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/thickbox/thickbox.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/thickbox/thickbox.css');
	    }
		
		if($arSettings['windy'] == 'Y'){        
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/windy/js/modernizr.custom.79639.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/windy/js/jquery.windy.js');
			//$APPLICATION->AddHeadScript('/yenisite.resizer2/js/windy/js/jquery-ui-1.8.23.custom.min.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/windy/css/windy.css');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/windy/css/style1.css');
			//$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/windy/css/demo.css');
	    }
	    
		if($arSettings['easyzoom'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Easyzoom/easyzoom.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Easyzoom/easyzoom.css');
	    }
		
		if($arSettings['highslide'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/highslide/highslide-full.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/highslide/highslide.css');
	    }
	    if($arSettings['carousellite'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/jCarouselLite/jcarousellite_1.0.1.min.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/jCarouselLite/jquery.easing.1.1.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/jCarouselLite/jcarousellite.css');
	    }
		
		if($arSettings['shadowbox'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/shadowbox/shadowbox.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/shadowbox/shadowbox.css');
	    }
		
		if($arSettings['colorbox'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/colorbox/js/jquery.colorbox-min.js');
			//$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/colorbox/skins/skin1/colorbox.css');
	    }
		
		if($arSettings['skitter'] == 'Y'){
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Skitter/js/jquery.easing.1.3.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Skitter/js/jquery.animate-colors-min.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Skitter/js/jquery.skitter.min.js');
			
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Skitter/css/skitter.styles.css');
	    }
		
		if($arSettings['cloudzoom'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/cloudZoom/cloud-zoom.1.0.2.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/cloudZoom/cloud-zoom.css');
	    }
		
		if($arSettings['zoomy'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/zoomy/jquery.zoomy1.2.min.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/zoomy/zoomy1.2.css');
	    }
		
		if($arSettings['ad'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/ADgallery/jquery.ad-gallery.min.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/ADgallery/jquery.ad-gallery.css');
	    }
		
		if($arSettings['hoverpulse'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/hoverpulse/jquery.hoverpulse.js');
			
	    }
		
		if($arSettings['pika'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Pikachoose/js/jquery.pikachoose.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Pikachoose/js/jquery.jcarousel.min.js');
			//$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Pikachoose/styles/bottom.css');
	    }
		
		if($arSettings['coin'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/Coin/coin-slider.min.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/Coin/coin-slider-styles.css');
	    }
		
		if($arSettings['spacegallery'] == 'Y') {
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/spacegallery/eye.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/spacegallery/utils.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/spacegallery/spacegallery.js');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/spacegallery/custom.css');
			$APPLICATION->SetAdditionalCSS('/yenisite.resizer2/js/spacegallery/spacegallery.css');
	    }
		
		if($arSettings['pirobox'] == 'Y') {
			//$APPLICATION->AddHeadScript('/yenisite.resizer2/js/pirobox/jquery-ui-1.8.2.custom.min.js');
			$APPLICATION->AddHeadScript('/yenisite.resizer2/js/pirobox/pirobox_extended_min.js');
			}
	//}
}


function HTMLEditorButton($editorName, $arEditorParams)
{
    CAdminFileDialog::ShowScript(
        Array(
            "event" => "OpenImageResizer2",
                        "arResultDest" => Array("FUNCTION_NAME" => "getImageUrl"),
                        "arPath" => Array("PATH" => '/upload/'),
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
        Array(
            "event" => "OpenImageResizer3",
                        "arResultDest" => Array("FUNCTION_NAME" => "getImageUrlMediaLibrary"),
                        "arPath" => Array("PATH" => '/upload/'),
                        "select" => 'F',
                        "operation" => 'O',
                        "showUploadTab" => true,
                        "showAddToMenuTab" => false,
                        "fileFilter" => 'image',
                        "allowAllFiles" => true,
                        "saveConfig" => true
        )
    );
 

   return Array(
      "JS" => Array('button.php'),
   );
}

function CompressImages($arFields)
    {
		$resize_compress_property = COption::GetOptionString('yenisite.resizer2', 'resize_compress_property', '');
		$obEl = CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
		$arProps = $obEl->GetProperties();
		if($arProps[$resize_compress_property]['VALUE'])
		{
			$resize_compress_set = COption::GetOptionString('yenisite.resizer2', 'resize_compress_set', '');	
			$arSet = CResizer2Set::GetByID($resize_compress_set);			
			$check=0;
			$i=0;
			foreach($arProps[$resize_compress_property]['VALUE'] as $v)
			{
				$picture['SIZE']=CFile::GetFileArray($v);			    
				if($picture['SIZE']['HEIGHT']>$arSet['h']||$picture['SIZE']['WIDTH']>$arSet['w'])
				{
					$picture['PATH'][$i] = CFile::GetPath($v);
					$picture['PATH'][$i] = CResizer2Resize::ResizeGD2($picture['PATH'][$i], $resize_compress_set);
					$picture['PATH'][$i] = split("/",$picture['PATH'][$i],4);
					$pos=strpos($picture['PATH'][$i][3],"?");
					if($pos!=false)
						$picture['PATH'][$i]=substr($picture['PATH'][$i][3],0,$pos);
					else
						$picture['PATH'][$i] = $picture['PATH'][$i][3];
					$arFile[$i] = array(	
						"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/".$picture['PATH'][$i]),
						"DESCRIPTION"=>$arProps[$resize_compress_property]['DESCRIPTION'][$i]
						);
					CFile::Delete($v);
					$arDelete[$i]=$picture['PATH'][$i];
					$check=1;
				}
				else
				{
					$arFile[$i] = array(	
						"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($v)),
						"DESCRIPTION"=>$arProps[$resize_compress_property]['DESCRIPTION'][$i]
						);
					$arDelete[$i] = CFile::GetPath($v);
				}
				$i++;
			}
					
			if($check!=0)
			{
				CIBlockElement::SetPropertyValuesEx($arFields['ID'],$arFields['IBLOCK_ID'],array($resize_compress_property => $arFile));
				foreach($arDelete as $v)
					DeleteDirFilesEx($v);
			}	
		}
	}
    
}

class CResizer2Settings extends CResizer2{
    function GetSettings()
    {
        global $DB;
        $strSql = "SELECT * FROM yen_resizer2_settings";	
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);	
        while($setting = $res->GetNext())
        {	
                $arResult[$setting['NAME']] = $setting['VALUE'];
        }        
        return $arResult;
    }
    
    function GetSettingByName($name)
    {
        global $DB;
        $strSql = "SELECT VALUE FROM yen_resizer2_settings WHERE NAME='{$name}'";	
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);	
        $setting = $res->GetNext();        
        return $setting['VALUE'];
    }
    
    function GetFontList()    
    {
        $file_list = scandir("{$_SERVER[DOCUMENT_ROOT]}/yenisite.resizer2/fonts/");
        $result  = array();
        foreach($file_list as $file)
        {            
            if($file !='.' && $file !='..')
                $result[] = $file;            
        }
        return $result;
    }
    
}


function imageRotateGD($src_img, $angle, $bicubic=false) {
    
    //if($angle == 0 || $angle == 360)
        //return $src_img;
    
    $angle = 180 - $angle;
    $angle = deg2rad($angle);
  
    $src_x = imagesx($src_img);
    $src_y = imagesy($src_img);
  
    if( $src_x > $src_y )
        $w = $src_x; 
    else
        $w = $src_y; 
        
    $w = 2*pow($w, 2);
    $w = pow($w, 0.5);     
    $blank = imagecreate($w, $w);

    $center_x = floor($w/2);
    $center_y = floor($w/2);
  
    $rotate = imagecreatetruecolor($w, $w);
    imagealphablending($rotate, false);
    imagesavealpha($rotate, true);
    
    $cosangle = cos($angle);
    $sinangle = sin($angle);
  
    for ($y = 0; $y < $w; $y++) {
      for ($x = 0; $x < $w; $x++) {
        
    $old_x = (($center_x-$x) * $cosangle + ($center_y-$y) * $sinangle)
      + $center_x/1.5;
    $old_y = (($center_y-$y) * $cosangle - ($center_x-$x) * $sinangle)
      + $center_y/1.5;
      
    if ( $old_x >= 0 && $old_x < $src_x
         && $old_y >= 0 && $old_y < $src_y ) {
      if ($bicubic == false) {
        $sY  = $old_y + 1;
        $siY  = $old_y;
        $siY2 = $old_y - 1;
        $sX  = $old_x + 1;
        $siX  = $old_x;
        $siX2 = $old_x - 1;
      
        $c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
        $c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
        $c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
        $c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));
      
        $r = ($c1['red']  + $c2['red']  + $c3['red']  + $c4['red']  ) << 14;
        $g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) << 6;
        $b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >> 2;
        $a = ($c1['alpha']  + $c2['alpha']  + $c3['alpha']  + $c4['alpha'] ) >> 2;
        $color = imagecolorallocatealpha($src_img, $r,$g,$b,$a);
      } else {
        $color = imagecolorat($src_img, $old_x, $old_y);
      }
    } else {               
        $color = imagecolorallocatealpha($src_img, 255, 255, 255, 127);      
    }
   
        imagesetpixel($rotate, $x, $y, $color);
      }
    }
    return $rotate;
}




class CResizer2Resize extends CResizer2{
    
    function WmGDPng($main_img_obj, $watermark_img_obj, $dest_x, $dest_y, $angle = 0, $factor = 100, $alpha_level = 100)
    {
        
        $watermark_img_obj = imageRotateGD($watermark_img_obj, $angle, true);
        $watermark_width = imagesx($watermark_img_obj);
        $watermark_height = imagesy($watermark_img_obj);
        
        $factor = $factor/100;
        
        $main_y = imagesy($main_img_obj);
        $main_x = imagesx($main_img_obj);
        
        $img_buf = $watermark_img_obj;
        imagecopyresampled($main_img_obj, $img_buf, $dest_x, $dest_y, 0, 0, $watermark_width*$factor, $watermark_height*$factor, $watermark_width, $watermark_height);
        return $main_img_obj;
    }
    
    function Hex2Rgb($color) {
        if ($color[0] == '#')
            $color = substr($color, 1);
    
        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                                     $color[2].$color[3],
                                     $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return false;
    
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
    
        return array($r, $g, $b);
    }
    
    function GetCacheID($image_url, $set_id, $ws = 0, $hs = 0)    
    {
		$image_url = str_replace($_SERVER[DOCUMENT_ROOT], "", $image_url);
        $image_url = str_replace("http", "", $image_url);
		$image_url = str_replace(":", "", $image_url);
        $image_url = str_replace("/", "_", $image_url);        
		if($ws > 0 || $hs > 0)
			$set_id = $set_id."_".$ws."-".$hs;
			
        return "{$set_id}_{$image_url}";
    }
    
    
    function ClearCacheByID($set_id, $ws = 0, $hs = 0)    
    {
	
		if($ws > 0 || $hs > 0)
			$set_id = $set_id."_".$ws."-".$hs;
			
        $file_list = scandir("{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/");
        foreach($file_list as $file)
        {            
            if(substr_count($file, "{$set_id}_") > 0)            
                unlink("{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$file}");
            
        }        
    }
    
    function ClearImgCache($image_url, $set_id, $ws = 0, $hs = 0)   
    {
		if($ws > 0 || $hs > 0)
			$set_id = $set_id."_".$ws."-".$hs;
        $cacheID = self::GetCacheID($image_url, $set_id);
        if(file_exists("{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}"))
        {
            unlink("{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}");
            
        }
    }


    function GetFontSize($text, $w, $h, $font, $angle)
    {
        $size = 1;
        $box  = imagettfbbox($size, 0, $font, $text );

            while(abs($box[4]) < $w && $size < 200)
            {
                $size+=1;
                $box  = imagettfbbox($size, 0, $font, $text );
            }

         return $size;
        
    }
    
	
	function imgTagClassResize($className, $content, $set = 0, $set_big = 0){
		$matches = array();
		$ptrnBefore = "#<img(.*?)class=\"{$className}\"(.*?)src=\"([^\"]+)\"(.*?)>#";	
		preg_match_all($ptrnBefore, $content, $matches);
		$src = $matches[3];
		$ptrnAfter = "#<img(.*?)src=\"([^\"]+)\"(.*?)class=\"{$className}\"(.*?)>#";
		preg_match_all($ptrnAfter, $content, $matches);	
		$src = array_merge($src, $matches[2]);	
		if($set_big){	
			$tmplBefore =    "<a class=\"resizer2fancy\" href=\"BIG:\\3\"><img\\1class=\"{$className}\"\\2src=\"\\3\"\\4></a>";	
			$tmplAfter = "<a class=\"resizer2fancy\" href=\"BIG:\\2\"><img\\1src=\"\\2\"\\3class=\"{$className}\"\\4></a>";				
			$content =  preg_replace($ptrnBefore, $tmplBefore, $content);
			$content =  preg_replace($ptrnAfter, $tmplAfter, $content);	
		}
		$src = array_unique($src);			
		foreach($src as $sr){
			$path = self::ResizeGD2($sr, $set);
			if($set_big){
				$path_big = self::ResizeGD2($sr, $set_big);
				$content = str_replace("BIG:".$sr, $path_big, $content);
			}
			$path = self::ResizeGD2($sr, $set);
			$content = str_replace($sr, $path, $content);
						
		}
		return $content;
	}


	function getImgAttr($str){ $arr = explode(" ", $str); $attr = array(); foreach($arr as $a){ if(substr_count($a, "=") > 0){ $arri = explode("=", $a); if($arri[0] && $arri[1]){ $attr[$arri[0]] = $at = str_replace("\"", "",  $arri[1]);}}} return $attr;}
	function setImgAttr($attr){ $img = "<img"; foreach($attr as $k=>$at){ $at = str_replace("\"", "", $at); $img.=" {$k}=\"{$at}\""; } $img .= "/>"; return $img;}
	function imgTagWH($content, $set){		

		$matches = array();
		$ptrnBefore = "#<img(.*?)>#";	
		preg_match_all($ptrnBefore, $content, $matches);
		foreach($matches[0] as $k=>$im){
			$attr = self::getImgAttr($im);			
			if($attr["width"] > 0 || $attr["height"] > 0)
			{
				list($w, $h) = getimagesize($_SERVER["DOCUMENT_ROOT"].$attr["src"]); 
				if($w > $attr["width"] || $h > $attr["height"]){
					$attr["src"] =  self::ResizeGD2($attr["src"], $set, $attr["width"], $attr["height"]);
					unset($attr["width"]);
					unset($attr["height"]);
					$img = self::setImgAttr($attr);
					$content = str_replace($im, $img, $content);		
				}
			}

		}
		
		return $content;
	}
	
	
	
    
    function ResizeGD2($image_url, $set_id, $ws = 0, $hs = 0)
    {
		if(!empty($image_url))
		{
			//otsekaem iz URL parametri posle znaka '?'
			$pos=strpos($pathb,"?");
			$pathb=substr($pathb,0,$pos);
			
			$strpos = strpos($image_url,"http"); //esli URL absolytni
			if(!is_int($strpos))
			{
				$image_path = $_SERVER[DOCUMENT_ROOT].$image_url;
			}
			else
			{
				$image_path = $image_url;
			}
		}
		
		//if(!file_exists("{$_SERVER[DOCUMENT_ROOT]}{$image_url}") || is_dir("{$_SERVER[DOCUMENT_ROOT]}{$image_url}"))
				
		$fd = fopen($image_path, "r");
		if(!$fd||$dd = opendir($image_path))
		{
				$arSettings = CResizer2Settings::GetSettings();
				$no_path = CFile::GetPath($arSettings['no_image']);
				$strpos = strpos($no_path,"http");
				if(!is_int($strpos))
				{
					$no_path = $_SERVER[DOCUMENT_ROOT].$no_path;
				}
				else
				{
					$no_path = $no_path;
				}	
				
				 //if(!file_exists($no_path) || is_dir($no_path)){
				$fd_no = fopen($no_path, "r");
				if(!$fd_no||$dd_no = opendir($no_path))
				{
					return;
				}
				fclose ($fd_no);
				closedir ($dd_no);				
				$image_path = $no_path;	
		}	
		fclose ($fd);
		closedir ($dd);
		
        $cacheID = self::GetCacheID($image_path, $set_id, $ws, $hs);
		
		if($set_id == 0)
            {
                $arSet['w'] = 400;
                $arSet['h'] = 600;
                $arSet['priority'] = 'FILL';
                $arSet['wm'] = 'Y';
                $arSet['q'] = 100;
            }
            else
                $arSet = CResizer2Set::GetByID($set_id);
				
		if($arSet['conv']) //esli ystanovlen format dlya konvertacii kartinok
			{
				$cacheID_ext = strrpos($cacheID,'.');
				$cacheID_ext = substr($cacheID,$cacheID_ext+1);
				$cacheID = str_replace($cacheID_ext,$arSet['conv'],$cacheID);
			}
		
        if(file_exists("{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}"))
        {
            if($_SERVER["SERVER_PORT"]<1024)
				return "http://{$_SERVER[SERVER_NAME]}/upload/resizer2/{$cacheID}?cache=Y";
			else
				return "http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}/upload/resizer2/{$cacheID}?cache=Y";
        }
        else{          
           
          
            
            $dx = 10;
            $dy = 10;
            
            $arSettings = CResizer2Settings::GetSettings();
			
			//esli vibran nabor
			if($set_id != 0)
			{
				$arSett = CResizer2Set::GetByID($set_id);
				//i y nego est' nastroiki watermark
				if($arSett['watermark_settings']){		
					 $wms = unserialize(base64_decode(trim($arSett['watermark_settings'])));
					 foreach($wms as $k=>$w){	
						 $arSettings[$k] = $w;
						
					}
				}
			}
            
            // if($arSettings['image'])
            //   $wmpath = "http://".$_SERVER[SERVER_NAME].":".$_SERVER["SERVER_PORT"].CFile::GetPath($arSettings['image']);
           
            
            
            if(SITE_CHARSET == 'windows-1251')
                $arSettings['text'] = iconv('windows-1251', 'utf-8', $arSettings['text']);
           
            
            
			if($ws > 0) $arSet["w"] = $ws;
			if($hs > 0) $arSet["h"] = $hs;
			
            $path = $image_path;
            $path = split("/", $path);
            $fn = $path[count($path)-1];
            
            if(substr_count(strtolower($fn), ".png")){
                    $im = imagecreatefrompng($image_path); 
                    //$im = imagecreatefrompng("http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}/{$image_url}");
					}
            elseif(substr_count(strtolower($fn), ".gif")){
                    $im = imagecreatefromgif($image_path); 
                    }
           // elseif(substr_count(strtolower($fn), ".bmp")){
           //       $im = self::imagecreatefrombmp($image_path);                    
                    //$im = imagecreatefromgif("http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}/{$image_url}");
			//		}
            else{
                    $im = imagecreatefromjpeg($image_path); 
					}
                    //$im = imagecreatefromjpeg("http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}/{$image_url}");               

					
            imagealphablending($im, false);
            imagesavealpha($im, true);

                        
            $im_width=imageSX($im);
            $im_height=imageSY($im);
            
            $new_im_width = $arSet['w'];
            $new_im_height = $arSet['h'];
			
			$sX=0;
			$sY=0;
            
            switch($arSet['priority'])
            {
                case "HEIGHT":
                    if($arSet['h'] < $im_height)
                    {
                        $factor = $arSet['h']/$im_height;
                        $new_im_width = intval($im_width*$factor);
                        $arSet['w'] = intval($im_width*$factor);
                    }                
                    break;
                
                case "WIDTH":
                    if($arSet['w'] < $im_width)
                    {
                        $factor = $arSet['w']/$im_width;
                        $new_im_height = intval($im_height*$factor);
                        $arSet['h'] = intval($im_height*$factor);
                    }                
                    break;
                
				case "CROP":
					if($im_width/$im_height>=$arSet['w']/$arSet['h'])
					{
						$factor = $im_height/$arSet['h'];
						$sX = ($im_width - $arSet['w']*$factor)/2;
						$im_width = $arSet['w']*$factor;
					}		
					else
					{
						$factor = $im_width/$arSet['w'];
						$sY = ($im_height - $arSet['h']*$factor)/2;
						$im_height = $arSet['h']*$factor;
					}
					
                    break;
				
				case "FIT_LARGE":
					if($im_width/$im_height>=$arSet['w']/$arSet['h'])
					{
						$factor = $arSet['w']/$im_width;
						$arSet['h'] = intval($im_height*$factor);
					}	 
					else
					{
						$factor = $arSet['h']/$im_height;
						$arSet['w'] = intval($im_width*$factor);
					}
					
                    break;
				
                case "CWIDTH":
                    if($arSet['h'] < $im_height)
                    {                   
                        
                        $factor = $arSet['h']/$im_height;
                        $new_im_width = intval($im_width*$factor);
                        $new_im_height = $arSet['h'];
                        $dx = ($arSet['w'] - $new_im_width)/2;
                        $dy = ($arSet['h'] - $new_im_height)/2;
                        
                        $new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);
						
						list($r, $g, $b) = self::Hex2Rgb('#ffffff');
						$color = imagecolorallocate($new_im_buf, $r, $g, $b);
            			imagefill($new_im_buf, 0, 0, $color);
						imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);
                        $im_width = imageSX($new_im_buf);
                        $im_height = imageSY($new_im_buf);
                        
                    }
                    else
                    {
                        $arSet['h'] = $im_height;
                        $factor = $arSet['h']/$im_height;
                        $new_im_width = intval($im_width*$factor);
                        $new_im_height = $arSet['h'];
                        $dx = ($arSet['w'] - $new_im_width)/2;
                        $dy = ($arSet['h'] - $new_im_height)/2;
                        
                        $new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);
						
						list($r, $g, $b) = self::Hex2Rgb('#ffffff');
						$color = imagecolorallocate($new_im_buf, $r, $g, $b);
            			imagefill($new_im_buf, 0, 0, $color);
                        imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);
                        $im_width = imageSX($new_im_buf);
                        $im_height = imageSY($new_im_buf);                  
                    }
                    break;
                
                 case "CHEIGHT":
                    if($arSet['w'] < $im_width)
                    {   
                        $factor = $arSet['w']/$im_width;
                        $new_im_height = intval($im_height*$factor);
                        $new_im_width = $arSet['w'];
                        $dx = ($arSet['w'] - $new_im_width)/2;
                        $dy = ($arSet['h'] - $new_im_height)/2;
                        
                        $new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);
						
						list($r, $g, $b) = self::Hex2Rgb('#ffffff');
						$color = imagecolorallocate($new_im_buf, $r, $g, $b);
            			imagefill($new_im_buf, 0, 0, $color);
                        imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);
                        $im_width = imageSX($new_im_buf);
                        $im_height = imageSY($new_im_buf);
                    }
                    else
                    {
                        $arSet['w'] = $im_width;
                        $factor = $arSet['w']/$im_width;
                        $new_im_height = intval($im_height*$factor);
                        $new_im_width = $arSet['w'];
                        $dx = ($arSet['w'] - $new_im_width)/2;
                        $dy = ($arSet['h'] - $new_im_height)/2;                    
                        $new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);
						
						list($r, $g, $b) = self::Hex2Rgb('#ffffff');
						$color = imagecolorallocate($new_im_buf, $r, $g, $b);
            			imagefill($new_im_buf, 0, 0, $color);
                        imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);
                        $im_width = imageSX($new_im_buf);
                        $im_height = imageSY($new_im_buf);                  
                    }
                    break;
                case "FILL":
                    {   
                        $factor = $arSet['h']/$im_height;
                        $new_im_width = $im_width*$factor;
                        $new_im_height = intval($arSet['h']);						
						
                        if($new_im_width > $arSet['w'])
                        {
                            $factor = $arSet['w']/$new_im_width;
                            $new_im_height = $new_im_height*$factor;                        
                            $new_im_width = $arSet['w'];
                        }
						//else
							//$new_im_width = $new_im_width;
						
                        $dx = ($arSet['w'] - $new_im_width)/2;
                        $dy = ($arSet['h'] - $new_im_height)/2;
                        $new_im_buf = imagecreatetruecolor($new_im_width, $new_im_height);
						if(!$arSettings['fill'])
							$arSettings['fill'] = '#ffffff';
						list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
						$color = imagecolorallocate($new_im_buf, $r, $g, $b);
            			imagefill($new_im_buf, 0, 0, $color);
						
                        imagecopyresampled($new_im_buf, $im, 0, 0, 0, 0, $new_im_width, $new_im_height, $im_width, $im_height);
                        $im_width = imageSX($new_im_buf);
                        $im_height = imageSY($new_im_buf);
                    }    
                    break;
					
				default:
					if($im_width/$im_height>=$arSet['w']/$arSet['h'])
					{
						$factor = $arSet['w']/$im_width;
						$arSet['h'] = intval($im_height*$factor);
					}	 
					else
					{
						$factor = $arSet['h']/$im_height;
						$arSet['w'] = intval($im_width*$factor);
					}
					
                    break;
            }
            if(($arSet['w'] > $im_width || $arSet['h'] > $im_height) && ($arSet['priority'] == 'HEIGHT' || $arSet['priority'] == 'WIDTH' ))
            {
                $arSet['w'] = $im_width;
                $arSet['h'] = $im_height;           
            }
            $new_im = imagecreatetruecolor($arSet['w'], $arSet['h']);
            if(!$arSettings['fill'])
                $arSettings['fill'] = '#ffffff';
            list($r, $g, $b) = self::Hex2Rgb($arSettings['fill']);
            $color = imagecolorallocate($new_im, $r, $g, $b);
            
            imagefill($new_im, 0, 0, $color);
			
            if(!$new_im_buf)        
                imagecopyresampled($new_im, $im, 0, 0, $sX, $sY, $arSet['w'], $arSet['h'], $im_width, $im_height);
            else
                imagecopyresampled($new_im, $new_im_buf, $dx, $dy, 0, 0, $im_width, $im_height, $im_width, $im_height);
            
            if(!$arSettings['color'])
                $arSettings['color'] = '#ffffff';
                
            list($r, $g, $b) = self::Hex2Rgb($arSettings['color']);
            
            $c = imagecolorallocatealpha($new_im, $r, $g, $b, $arSettings['opacity']?$arSettings['opacity']:0);
            $font_size = self::GetFontSize($arSettings['text'], $arSet['w'], $arSet['h'], "{$_SERVER[DOCUMENT_ROOT]}/yenisite.resizer2/fonts/{$arSettings[font_family]}" ,$arSettings['angle']);
            $font_size = $font_size * $arSettings['fs']/100;
            $box  = imagettfbbox($font_size, 0, "{$_SERVER[DOCUMENT_ROOT]}/yenisite.resizer2/fonts/{$arSettings[font_family]}", $arSettings['text']."" );
            $wt = abs($box[2])+1; $ht = abs($box[7])+1;
            
		            
            //$wmpath = $_SERVER["DOCUMENT_ROOT"].str_replace("http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}", "", $wmpath);
			if($arSettings['image'] && $arSet['wm'] == 'Y')
			{ 
				$wmpath = CHTTP::URN2URI(CFile::GetPath($arSettings['image']),"{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}");
			}
				
            $water = imagecreatefrompng($wmpath);
            
			$watermark_img_obj = imageRotateGD($water, $arSettings['angle'], true);
			
			$wi = imageSX($watermark_img_obj)*$arSettings['fs']/100;
            $hi = imageSY($watermark_img_obj)*$arSettings['fs']/100;            
			//die("<img src='".$wi."'/>");
			//if($wi > $hi) $hi = $wi;
			//else $wi = $hi;
			
            //$wi = 2*pow($wi, 2);
            //$hi = pow($hi, 0.5)+10;     


            $yi = 0;    
            $xi = 0;
                
            switch($arSettings['place_v'])
            {
                case 'top':
                    $yi = $arSettings['top_margin'];
                    if($arSettings['angle'] >= 0)
                        $y = abs(sin(deg2rad($arSettings['angle'])))*$wt + abs(sin(deg2rad($arSettings['angle'] + 90))*$ht) + $arSettings['top_margin'];
                    if($arSettings['angle'] > 90)
                        $y = abs(sin(deg2rad($arSettings['angle'])))*$wt + $arSettings['top_margin'];
                    if($arSettings['angle'] > 180)
                        $y = $arSettings['top_margin'];                    
                    if($arSettings['angle'] > 270)
                       $y = abs(sin(deg2rad($arSettings['angle'] + 90))*$ht) + $arSettings['top_margin'];
									
					
                    break;
                
                case 'center':
                    $yi = imagesY($new_im)/2 - $hi/2;
					$y = imagesY($new_im)/2 + sin(deg2rad($arSettings['angle']))*$wt/2 ;   					
                    break;
                
                case 'bottom':
                    $yi = imagesY($new_im) - $hi - $arSettings['bottom_margin'];
                    $y = imagesY($new_im) - abs($box[5]) - $arSettings['bottom_margin']; 
                    break;
            }

            $x = cos(deg2rad($arSettings['angle']))*$wt;            
            switch($arSettings['place_h'])
            {
                case 'left':
                    $xi = $arSettings['left_margin'] ;
                    $x = $x<0?abs($x):0;                    
                    $x += cos(deg2rad($arSettings['angle'] + 90))*$ht<0?abs(cos(deg2rad($arSettings['angle'] + 90))*$ht):0;
                    $x += - $arSettings['left_margin'];
                    break;
                case 'center':
                    $xi = imagesX($new_im)/2 - $wi/2 ;
                    $x = imagesX($new_im)/2 - $x/2 ;
                    break;                
                case 'right':
                    $xi = imagesX($new_im) - $wi - $arSettings['right_margin'] ;
                    if($arSettings['angle'] < 90 || $arSettings['angle'] > 270)
                        $x = imagesX($new_im) - $x - $arSettings['right_margin'] ;                        
                    else
                        $x = imagesX($new_im) - $arSettings['right_margin'] ;
                        
                    if($arSettings['angle'] > 180)   
                        $x = $x - cos(deg2rad($arSettings['angle'] + 90))*$ht - $arSettings['right_margin'] ;

                    break;
            }
            
            if($arSettings['image'] && $arSet['wm'] == 'Y'){            
                $new_im = CResizer2Resize::WmGDPng($new_im, $water, $xi, $yi, $arSettings['angle'], $arSettings['fs'],  $arSettings['opacity']?$arSettings['opacity']:0);
            }
			
            elseif($arSettings['text'] && $arSet['wm'] == 'Y')
                imagettftext($new_im, $font_size , $arSettings['angle'] , $x, $y, $c, "{$_SERVER[DOCUMENT_ROOT]}/yenisite.resizer2/fonts/{$arSettings[font_family]}", $arSettings['text']);
			
			
			
            if(substr_count(strtolower($cacheID), ".png"))
                     imagePng($new_im, "{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}");
            elseif(substr_count(strtolower($cacheID), ".gif"))
                    imageGif($new_im, "{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}");
            else{
                   //$cacheID = str_replace('.jpg',  $cacheID);
                   imageJpeg($new_im, "{$_SERVER[DOCUMENT_ROOT]}/upload/resizer2/{$cacheID}", $arSet['q']);
				}
                
            if($_SERVER["SERVER_PORT"]<1024)    
				return "http://{$_SERVER[SERVER_NAME]}/upload/resizer2/{$cacheID}";  
			else
				return "http://{$_SERVER[SERVER_NAME]}:{$_SERVER["SERVER_PORT"]}/upload/resizer2/{$cacheID}";         
        }
    }
    
    
    
    
    
    
    function ConvertBMP2GD($src, $dest = false) {
        if(!($src_f = fopen($src, "rb"))) {
        return false;
        }
        if(!($dest_f = fopen($dest, "wb"))) {
        return false;
        }
        $header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,
        14));
        $info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",
        fread($src_f, 40));

        extract($info);
        extract($header);

        if($type != 0x4D42) { // signature "BM"
        return false;
        }

        $palette_size = $offset - 54;
        $ncolor = $palette_size / 4;
        $gd_header = "";
        // true-color vs. palette
        $gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
        $gd_header .= pack("n2", $width, $height);
        $gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
        if($palette_size) {
        $gd_header .= pack("n", $ncolor);
        }
        // no transparency
        $gd_header .= "\xFF\xFF\xFF\xFF";

        fwrite($dest_f, $gd_header);

        if($palette_size) {
        $palette = fread($src_f, $palette_size);
        $gd_palette = "";
        $j = 0;
        while($j < $palette_size) {
        $b = $palette{$j++};
        $g = $palette{$j++};
        $r = $palette{$j++};
        $a = $palette{$j++};
        $gd_palette .= "$r$g$b$a";
        }
        $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
        fwrite($dest_f, $gd_palette);
        }

        $scan_line_size = (($bits * $width) + 7) >> 3;
        $scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size &
        0x03) : 0;

        for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
        // BMP stores scan lines starting from bottom
        fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *
        $l));
        $scan_line = fread($src_f, $scan_line_size);
        if($bits == 24) {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size) {
        $b = $scan_line{$j++};
        $g = $scan_line{$j++};
        $r = $scan_line{$j++};
        $gd_scan_line .= "\x00$r$g$b";
        }
        }
        else if($bits == 8) {
        $gd_scan_line = $scan_line;
        }
        else if($bits == 4) {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size) {
        $byte = ord($scan_line{$j++});
        $p1 = chr($byte >> 4);
        $p2 = chr($byte & 0x0F);
        $gd_scan_line .= "$p1$p2";
        }
        $gd_scan_line = substr($gd_scan_line, 0, $width);
        }
        else if($bits == 1) {
        $gd_scan_line = "";
        $j = 0;
        while($j < $scan_line_size) {
        $byte = ord($scan_line{$j++});
        $p1 = chr((int) (($byte & 0x80) != 0));
        $p2 = chr((int) (($byte & 0x40) != 0));
        $p3 = chr((int) (($byte & 0x20) != 0));
        $p4 = chr((int) (($byte & 0x10) != 0));
        $p5 = chr((int) (($byte & 0x08) != 0));
        $p6 = chr((int) (($byte & 0x04) != 0));
        $p7 = chr((int) (($byte & 0x02) != 0));
        $p8 = chr((int) (($byte & 0x01) != 0));
        $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
        }
        $gd_scan_line = substr($gd_scan_line, 0, $width);
        }

        fwrite($dest_f, $gd_scan_line);
        }
        fclose($src_f);
        fclose($dest_f);
        return true;
    }

    function imagecreatefrombmp($filename) {
        $tmp_name = tempnam("/tmp", "GD");
        if(self::ConvertBMP2GD($filename, $tmp_name)) {
        $img = imagecreatefromgd($tmp_name);
        unlink($tmp_name);
        return $img;
        }
        return false;
    }
    
    
    
    
    
    
    
    
    
    
}


class CResizer2Set extends CResizer2{
    
    static function Update($id, $name, $w = 800, $h = 600, $q = 100, $wm ='N' , $priority = 'WIDTH' , $conv = '') 
    {
        global $DB;
		/********proverka i dobalvenie stolbca CONV***************/
		$strSql = "SHOW COLUMNS FROM yen_resizer2_sets";  
		$check = false;
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		while($arRes = $res->GetNext())
		{ 
			if($arRes['Field']=="conv")
				$check = true;
		}
		if($check!=true)		
		{
			$strSql = "ALTER TABLE yen_resizer2_sets ADD COLUMN conv varchar(30)";  
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		}
		/************************************************************/
        $wm = $wm=='Y'?'Y':'N';                       
        $strSql = "UPDATE yen_resizer2_sets SET name='{$name}', w='{$w}', h='{$h}', q='{$q}', wm='{$wm}', priority='{$priority}', conv='{$conv}' WHERE id={$id}";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }
    
    static function GetByID($id) 
    {
        global $DB;
        $strSql = "SELECT * FROM yen_resizer2_sets WHERE id={$id}";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
        $res = $res->GetNext();
        return $res;
    }
    
    static function GetList() 
    {
        global $DB;
        $strSql = "SELECT * FROM yen_resizer2_sets";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);      
        return $res;
    }
    
    static function Add($name, $w = 800, $h = 600, $q = 100, $wm ='N' , $priority = 'WIDTH', $conv = '')
    {
        global $DB;
		/********proverka i dobalvenie stolbca CONV***************/
		$strSql = "SHOW COLUMNS FROM yen_resizer2_sets";  
		$check = false;
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		while($arRes = $res->GetNext())
		{ 
			if($arRes['Field']=="conv")
				$check = true;
		}
		if($check!=true)		
		{
			$strSql = "ALTER TABLE yen_resizer2_sets ADD COLUMN conv varchar(30)";  
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		}
		/************************************************************/
        $wm = $wm=='Y'?'Y':'N';                       
        $strSql = "INSERT INTO yen_resizer2_sets(name, w, h, q, wm, priority,conv)  VALUES('{$name}', '{$w}', '{$h}', '{$q}', '{$wm}', '{$priority}', '{$conv}')";        
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }
}
?>
