<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

include 'functions.php';

if($arResult["is_ajax_post"] != "Y" && $_REQUEST['rz_ajax'] != 'Y'):?>
	<div id="blog_comments" data-element-id="<?=(int)$arParams["ELEMENT_ID"]; ?>">
<?endif?>

<?
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');

if(strlen($arResult["MESSAGE"])>0)
{
	?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text text-success">
			<?=$arResult["MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text text-danger" id="blg-com-err">
			<?=$arResult["ERROR_MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["COMMENT_ERROR"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["COMMENT_ERROR"]?>
		</div>
	</div>
	<?
}
	
if(strlen($arResult["FATAL_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text text-danger">
			<?=$arResult["FATAL_MESSAGE"]?>
		</div>
	</div>
	<?
}
else
{
	if($arResult["imageUploadFrame"] != "Y")
	{
		if($arResult["is_ajax_post"] != "Y" && $arResult["CanUserComment"])
		{
			?>
			<div class="form-wrap">
				<header><span class="text"><?=GetMessage('BITRONIC2_WRITE_REVIEW')?></span></header>
				<form method="POST" name="form_comment" action="#" class="form_comment" id="form_comment_blog">
					<input type="hidden" name="SITE_DIR" id="SITE_DIR" value="<?=SITE_DIR?>">
					<input type="hidden" name="parentId" id="parentId" value="0">
					<input type="hidden" name="edit_id" id="edit_id" value="">
					<input type="hidden" name="act" id="act" value="add">
					<input type="hidden" name="post" value="Y">
                    <input type="hidden" name="privacy_policy" value="N"/>
					<input type="hidden" name="ownerID" value="<?=(!empty($arResult['Blog']['OWNER_ID'])) ? $arResult['Blog']['OWNER_ID'] : 1?>">
					<input type="hidden" name="blogID" value="<?=$arResult['Blog']['ID']?>">
					
					<input type="hidden" name="ID" value="<?=(int)$arParams["ID"]; ?>">
					<input type="hidden" name="IBLOCK_ID" value="<?=(int)$arParams["IBLOCK_ID"]; ?>">
					<input type="hidden" name="ELEMENT_ID" value="<?=(int)$arParams["ELEMENT_ID"]; ?>">
					<input type="hidden" name="SITE_ID" value="<?=SITE_ID?>">
					
					<?

					echo makeInputsFromParams($arParams["PARENT_PARAMS"]);
					echo bitrix_sessid_post();
					// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
					
					if(!empty($arResult["User"]))
					{	
						$user_ava = CFile::GetPath($arResult['arUser']['PERSONAL_PHOTO']);
						$user_ava = ($user_ava) ? $user_ava : 'no_photo';
						?>
							<div class="when-authorized user-info">
								<div itemscope itemtype="http://schema.org/ImageObject" class="avatar">
									<img itemprop="contentUrl" title="<?=$arResult["User"]['NAME']?>" src="<?=CResizer2Resize::ResizeGD2($user_ava,$arParams['RESIZER_COMMENT_AVATAR'])?>" alt="<?=$arResult["User"]['NAME']?>">
								</div>
								<div class="name"><?=$arResult["User"]['NAME']?></div>
							</div>
						<?	
					}
					else
					{
						?>
							<div class="when-not-authorized">
								<label>
									<span class="text"><?=GetMessage("B_B_MS_NAME")?><span class="required-asterisk">*</span>:</span>
									<input type="text" name="user_name" id="user_name" value="<?=htmlspecialcharsEx($_SESSION["blog_user_name"])?>" class="textinput commentator-name" placeholder="<?=GetMessage("B_B_MS_NAME")?>" required>
								</label>
								<label>
									<span class="text">E-mail<span class="required-asterisk">*</span>:</span>
									<input type="text" name="user_email" id="user_email" value="<?=htmlspecialcharsEx($_SESSION["blog_user_email"])?>" class="textinput commentator-name" placeholder="E-mail">
								</label>
							</div>
							<?
					}
					?>
					<?
					/* TODO
					<div class="rating">
						............
					</div>
					*/
					?>
					<div class="textarea-wrap">
						<label for="textarea-comment"><?=GetMessage('BITRONIC2_REVIEW_TEXT')?></label>
						<textarea name="comment" id="textarea-comment" cols="30" rows="5" placeholder="<?=GetMessage('BITRONIC2_ENTER_MESSAGE')?>" class="textinput" required></textarea>
					</div>
                    <div class="aggre-privicy-politic">
                        <label class="checkbox-styled">
                            <input value="Y" type="checkbox" name="privacy_policy">
                            <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                                <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
                        </label>
                    </div>
					<div class="form-footer">
						<?
						if($arResult["use_captcha"]===true)
						{
							?>
							<input type="hidden" name="captcha_code" id="captcha_code" value="<?=$arResult["CaptchaCode"]?>">
							<label for="captcha_word"><?=GetMessage("B_B_MS_CAPTCHA_SYM")?><span class="required-asterisk">*</span>:</label>
							<div class="captcha" data-code="<?=$arResult["CaptchaCode"]?>">
								<?/*<img src="/bitrix/tools/captcha.php?captcha_code=" alt="<?=GetMessage("B_B_MS_CAPTCHA_SYM")?>">*/?>
							</div>
							
							<input type="text" size="30" name="captcha_word" id="captcha_word" value=""  tabindex="7" required class="textinput comment-captcha-input" placeholder="<?=GetMessage("B_B_MS_CAPTCHA_SYM_SHORT")?>">
							<?
						}
						?>
						<button class="btn-main disabled" value="<?=GetMessage("B_B_MS_SEND")?>" type="submit" name="sub-post" id="post-button"><span class="btn-text"><?=GetMessage("B_B_MS_SEND")?></span></button>
					</div>

					<span class="required-info">
						<span class="required-asterisk">*</span> &mdash; <?=GetMessage('BITRONIC2_REQ_FIELDS')?>
					</span>
					<input type="hidden" name="blog_upload_cid" id="upload-cid" value="">
				</form>
			</div><!-- /.form-wrap -->
			<?
		}
		
		$arParams["RATING"] = $arResult["RATING"];
		$arParams["component"] = $component;
		$arParams["arImages"] = $arResult["arImages"];
		if($arResult["is_ajax_post"] == "Y")
			$arParams["is_ajax_post"] = "Y";

        //$templateData['CNT_ELEMENTS'] = count($arResult['Comments']);
		if($arResult["is_ajax_post"] != "Y" && $arResult["NEED_NAV"] == "Y")
		{
			$i = 1;
			foreach($arResult["PagesComment"] as $arPage)
			{
				$tmp = $arResult["CommentsResult"];
				$tmp[0] = $arPage;
                $cnt += count($arResult["CommentsResult"]);
				?>
					<div id="blog-comment-page-<?=$i?>"<?if($arResult["PAGE"] != $i) echo "style=\"display:none;\""?>><?RecursiveComments($tmp, $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);?></div>
				<?
				$i++;
			}
		}
		else
		{
			RecursiveComments($arResult["CommentsResult"], $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);
		}
		
		if($arResult["is_ajax_post"] != "Y")
		{
			if($arResult["NEED_NAV"] == "Y")
			{
				?>
				<div class="pagination">
					<?=GetMessage("BPC_PAGE")?>&nbsp;<?
					for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++):
						$add_class = ($i == $arResult["PAGE"]) ?  'active' : '';
						?>
						<a href="<?=$arResult["NEW_PAGES"][$i]?>" class="pagination-item <?=$add_class?>"	data-page="<?=$i?>">
							<span class="btn-text"><?=$i?></span>
						</a>
					<?endfor?>
				</div>
				<?
			}
		}
	}
}
?>
<?if($arResult["is_ajax_post"] != "Y" && $_REQUEST['rz_ajax'] != 'Y'):?>
	</div><!-- #blog_comments -->
<?else:?>
	<input type="hidden" value="<?=count($arResult['Comments'])?>" id="reviews_count" name="reviews_count" />
<?endif;?>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";