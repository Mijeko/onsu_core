<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH."/js/back-end/handlers/search_handler.js");

$this->setFrameMode(true);
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
    $INPUT_ID = "search-field";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

$bSelect = ($arParams['SHOW_CATEGORY_SWITCH'] !== 'N');
$example = false;
?>
<form action="<?echo $arResult["FORM_ACTION"]?>" method="get" id="<?echo $CONTAINER_ID?>" class="search-wrap silver-normal <?if($bSelect):?>category_0<?else:?>category_all<?endif?>" >
    <?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';?>
    <?if($arParams["EXAMPLE_ENABLE"] == "Y"):
        $list = $arParams["EXAMPLES"];
        if(!end($list))
            array_pop($list);
        $example = $list[array_rand($list)];
        if($example):
            $id = 'bxdinamic_bitronic2_search_example';
            ?>
            <span class="search-example-wrap<?=(isset($_REQUEST['q'])?' hidden':'')?>"><span class="search-example-text"><?=GetMessage('BITRONIC2_EXAMPLE');?></span>
                <span id="<?=$id?>">
			<?$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
                    <span class="search-example"><?echo $example;?></span>
                    <?$frame->end();?>
		</span>
		</span>
        <?endif;endif;?>
    <input type="text" class="textinput" id="<?echo $INPUT_ID?>" name="q" value="<?=htmlspecialcharsbx($_REQUEST["q"])?>" autocomplete="off"<?if(!$example):?> placeholder="<?=GetMessage('BITRONIC2_SEARCH_PLACEHOLDER')?>"<?endif?>>
    <span class="search-controls">
		<i class="search-clear flaticon-close47" data-tooltip title="<?=GetMessage('BITRONIC2_CLEAR_PLACEHOLDER')?>"></i>
        <?if ($bSelect):?>
            <select name="where" id="search-area" class="search-area select-main">
			<?for($i=0; $i<$arParams['NUM_CATEGORIES']; $i++):?>
                <option value="<?=$arParams['CATEGORY_'.$i][0]?>" data-category="<?=$i?>"><?=$arParams['CATEGORY_'.$i.'_TITLE']?></option>
            <?endfor?>
                <option value="ALL" data-category="all"><?=GetMessage('BITRONIC2_EVERYWHERE')?></option>
		</select>
        <?endif?>
        <button class="btn-main btn-search" name="s" type="submit" value="">
			<i class="flaticon-search50"></i>
		</button>
	</span>
    <div class="popup_ajax-search" id="popup_ajax-search">
    </div>
</form><!-- search-wrap -->

<script>
    searchAreaHandler ($);
	jQuery(window).load(function(){
		require(['back-end/ajax/search'], function(){
			new JCRZB2TitleSearch({
				'AJAX_PAGE' : '<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>',
				'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
				'RESULT_ID': 'popup_ajax-search',
				'INPUT_ID': '<?echo $INPUT_ID?>',
				'MIN_QUERY_LEN': 2,
                'SEARCH_PAGE': '<?echo $arParams['PAGE'] == $APPLICATION->GetCurPage() ? 'Y' : 'N'?>'
			});
		});
	});
    BASKET_URL = '<?=$arParams['BASKET_URL']?>';
</script>