<?
include_once "include_stop_statistic.php";

// for sef of standart bitrix component
$_SERVER["REQUEST_URI"] = !empty($_POST["REQUEST_URI"]) ? $_POST["REQUEST_URI"] : $_SERVER["REQUEST_URI"];
$_SERVER["SCRIPT_NAME"] = !empty($_POST["SCRIPT_NAME"]) ? $_POST["SCRIPT_NAME"] : $_SERVER["SCRIPT_NAME"];

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");

if (check_bitrix_sessid() && isset($_POST["ELEMENT_ID"]) && isset($_POST["IBLOCK_ID"]) && isset($_POST["comment_mode"]))
{
    // @var $moduleId
    // @var $moduleCode
    // @var $settingsClass
    include_once "include_module.php";
    include 'include_options.php';

    $save_param = new CPHPCache();
    $cacheID = SITE_ID."_catalog_review_";
    $cachePath = "/{$moduleCode}/ajax/catalog/review/";

    if(!in_array($_POST["comment_mode"], array('blog', 'forum')))
    {
        die("[ajax died] wrong comment mode");
    }

    if($save_param->InitCache(86400*14, SITE_ID."_catalog_review_".$_POST["comment_mode"].$_POST['ELEMENT_ID'], "/{$moduleCode}/ajax/catalog/review/".$_POST["comment_mode"]))
    {
        $arSaveParam = $save_param->GetVars();
    }
    unset($save_param);
    if(!is_array($arSaveParam)) {
        die("[ajax died] loading params");
    }

    if(!defined('BX_UTF'))
    {
        $_POST = $APPLICATION->ConvertCharsetArray($_POST, 'UTF-8', LANG_CHARSET);
    }
    $arSaveParam["ELEMENT_ID"] = intval($_POST["ELEMENT_ID"]);
    $arSaveParam["IBLOCK_ID"] = intval($_POST["IBLOCK_ID"]);
    // edit params
    switch($_POST["comment_mode"])
    {
        case 'blog':
            $componentName = 'bitrix:blog.post.comment';
            $arSaveParam["AJAX_POST"] = isset($_POST["act"]) && $_POST["act"] == 'add' ? 'Y' : 'N';
            $arSaveParam["ID"] = $_POST["ID"];

            if(intval($arSaveParam['ID']) == 0 && Bitrix\Main\Loader::includeModule('iblock'))
            {
                $dbPropBlogPost = CIBlockElement::GetProperty($arSaveParam['IBLOCK_ID'], $arSaveParam['ELEMENT_ID'], array("sort" => "asc"), Array("CODE" => CIBlockPropertyTools::CODE_BLOG_POST));
                if($obPropBlogPost = $dbPropBlogPost->Fetch())
                {
                    if(intval($obPropBlogPost["VALUE"]) > 0)
                    {
                        $arSaveParam['ID'] = intval($obPropBlogPost["VALUE"]);
                    }
                }
            }

            // create new blog post
            if ($arSaveParam["AJAX_POST"] == 'Y' && intval($arSaveParam['ID']) == 0 && Bitrix\Main\Loader::includeModule('blog') && Bitrix\Main\Loader::includeModule('iblock'))
            {
                $arFields = array(
                    'TITLE' => $arSaveParam["ELEMENT"]["NAME"],
                    'DETAIL_TEXT' =>
                        "[URL=http://".$_SERVER['HTTP_HOST'].$arSaveParam["ELEMENT"]["DETAIL_PAGE_URL"]."]".$arSaveParam["ELEMENT"]["NAME"]."[/URL]\n".
                        ($arSaveParam["ELEMENT"]["PREVIEW_TEXT"] != '' ? $arSaveParam["ELEMENT"]["PREVIEW_TEXT"] : '')."\n",
                    'PUBLISH_STATUS' => BLOG_PUBLISH_STATUS_PUBLISH,
                    "PERMS_POST" => array(),
                    "PERMS_COMMENT" => array(),
                    "=DATE_CREATE" => $DB->GetNowFunction(),
                    "=DATE_PUBLISH" => $DB->GetNowFunction(),
                    "AUTHOR_ID" => $_POST['ownerID'],
                    "BLOG_ID" => $_POST['blogID'],
                    "ENABLE_TRACKBACK" => "N"
                );
                $postID = CBlogPost::Add($arFields);
                if ($postID)
                {
                    $arSaveParam['ID'] = $postID;
                    CIBlockElement::SetPropertyValues($arSaveParam['ELEMENT_ID'], $arSaveParam['IBLOCK_ID'], $postID, CIBlockPropertyTools::CODE_BLOG_POST);
                    /*if(defined("BX_COMP_MANAGED_CACHE")) {
                        $GLOBALS["CACHE_MANAGER"]->ClearByTag("iblock_id_".$arSaveParam['IBLOCK_ID']);
                    }*/
                }
                else
                {
                    if ($ex = $APPLICATION->GetException())
                        echo $ex->GetString();
                    die();
                }
            }
            break;
        case 'forum':
            $componentName = 'bitrix:forum.topic.reviews';
            $arSaveParam['USE_CAPTCHA'] = $rz_b2_options['use_google_captcha'] == 'N' ? $rz_b2_options['feedback-for-item-on-detail'] : 'N';
            $arSaveParam['USE_GOOGLE_CAPTCHA'] = $rz_b2_options['use_google_captcha'] == 'Y' ? $rz_b2_options['feedback-for-item-on-detail'] : 'N';
            break;
    }
    $arSaveParam["CACHE_TYPE"] = 'N';
    $arSaveParam["CACHE_TIME"] = '0';
    /*
        // NEED CLEAR CACHE OF CATALOG FOR CNT OF REVIEWS
        if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
            $arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', ($_REQUEST['CUSTOM_CACHE_KEY'] ?:false), CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
        }

        if(!is_array($arParams) || empty($arParams)) {
            die("[ajax died] loading params");
        }

        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag("iblock_id_".$arParams['IBLOCK_ID']);
    */
    $APPLICATION->IncludeComponent(
        $componentName,
        "reviews",
        $arSaveParam,
        false
    );

}
die();
