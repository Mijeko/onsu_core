<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Yenisite\Core\Ajax;
// reverse comments
if(isset($arResult["PagesComment"]) && is_array($arResult["PagesComment"])) 
{
	$arResult["PagesComment"] = array_reverse($arResult["PagesComment"], false);
}

//change avatar
foreach($arResult["CommentsResult"] as &$arCommentPage)
{
	foreach($arCommentPage as &$arComment)
	{
		$path = false;
		if(IntVal($arComment["BlogUser"]["AVATAR"]) > 0)
		{
			$path =	$arComment["BlogUser"]["AVATAR"];
		}
		elseif(IntVal($arComment["arUser"]["PERSONAL_PHOTO"]) > 0)
		{
			$path = $arComment["arUser"]["PERSONAL_PHOTO"];
		}
		$path = CFile::GetPath($path);
		$path = ($path) ? $path : 'no_photo';
		
		$arComment['AVATAR_file']['src'] = CResizer2Resize::ResizeGD2($path, $arParams["RESIZER_COMMENT_AVATAR"]);
	
	}
	$arCommentPage = array_reverse($arCommentPage, true);
}
if(is_array($arResult["PagesComment"]))
{
	foreach($arResult["PagesComment"] as &$arCommentPage)
	{
		foreach($arCommentPage as &$arComment)
		{
			$path = false;
			if(IntVal($arComment["BlogUser"]["AVATAR"]) > 0)
			{
				$path =	$arComment["BlogUser"]["AVATAR"];
			}
			elseif(IntVal($arComment["arUser"]["PERSONAL_PHOTO"]) > 0)
			{
				$path = $arComment["arUser"]["PERSONAL_PHOTO"];
			}
			
			$arComment['AVATAR_file']['src'] = CResizer2Resize::ResizeGD2(CFile::GetPath($path), $arParams["RESIZER_COMMENT_AVATAR"]);
		
		}
		$arCommentPage = array_reverse($arCommentPage, true);
	}
	unset($arCommentPage,$arComment);
}

if($arResult["is_ajax_post"] != "Y" && $_REQUEST['rz_ajax'] != 'Y')
{
	foreach($arParams as $key => $param)
	{
		if(strpos($key, '~') === 0) unset($arParams[$key]);
	}
	// ##### FOR AJAX
	// @var $moduleCode
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';
	
	$save_param = new CPHPCache();
	if($save_param->InitCache(86400*14, SITE_ID."_catalog_review_blog".$arParams['ELEMENT_ID'], "/{$moduleCode}/ajax/catalog/review/blog"))
		if($arParams != $save_param->GetVars())
			CPHPCache::Clean(SITE_ID."_catalog_review_blog", "/{$moduleCode}/ajax/catalog/review/blog");
	if($save_param->StartDataCache()):
		$save_param->EndDataCache($arParams);
	endif;
	unset($save_param);
}
?>