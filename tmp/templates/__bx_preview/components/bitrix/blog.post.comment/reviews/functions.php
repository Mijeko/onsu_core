<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

function makeInputsFromParams($arParams, $name="PARAMS")
{
	$result = "";

	if(is_array($arParams))
	{
		foreach ($arParams as $key => $value)
		{
			if(substr($key, 0, 1) != "~")
			{
				$inputName = $name.'['.$key.']';

				if(is_array($value))
					$result .= makeInputsFromParams($value, $inputName);
				else
					$result .= '<input type="hidden" name="'.$inputName.'" value="'.$value.'">'.PHP_EOL;
			}
		}
	}

	return $result;
}

function RecursiveComments($sArray, $key, $level=0, $first=false, $canModerate=false, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams)
{
	if(!empty($sArray[$key]))
	{
		foreach($sArray[$key] as $comment)
		{
			if(!empty($arSumComments[$comment["ID"]]))
			{
				$comment["CAN_EDIT"] = $arSumComments[$comment["ID"]]["CAN_EDIT"];
				$comment["SHOW_AS_HIDDEN"] = $arSumComments[$comment["ID"]]["SHOW_AS_HIDDEN"];
				$comment["SHOW_SCREENNED"] = $arSumComments[$comment["ID"]]["SHOW_SCREENNED"];
				$comment["NEW"] = $arSumComments[$comment["ID"]]["NEW"];
			}
			ShowComment($comment, $level, 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);
			if(!empty($sArray[$comment["ID"]]))
			{
				foreach($sArray[$comment["ID"]] as $key1)
				{
					if(!empty($arSumComments[$key1["ID"]]))
					{
						$key1["CAN_EDIT"] = $arSumComments[$key1["ID"]]["CAN_EDIT"];
						$key1["SHOW_AS_HIDDEN"] = $arSumComments[$key1["ID"]]["SHOW_AS_HIDDEN"];
						$key1["SHOW_SCREENNED"] = $arSumComments[$key1["ID"]]["SHOW_SCREENNED"];
						$key1["NEW"] = $arSumComments[$key1["ID"]]["NEW"];
					}
					ShowComment($key1, ($level+1), 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);

					if(!empty($sArray[$key1["ID"]]))
					{
						RecursiveComments($sArray, $key1["ID"], ($level+2), false, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams);
					}
				}
			}
			if($first)
				$level=0;
		}
	} elseif ($arParams['GAMIFICATION']) {
		echo '<div class="mar-b-15">', GetMessage('BITRONIC2_BE_FIRST'), '</div>';
	}
}

function ShowComment($comment, $tabCount=0, $tabSize=2.5, $canModerate=false, $User=Array(), $use_captcha=false, $bCanUserComment=false, $errorComment=false, $arParams = array())
{
	$comment["urlToAuthor"] = "";
	$comment["urlToBlog"] = "";
// echo "<pre style='text-align:left;'>";print_r($comment);echo "</pre>";
	if($comment["SHOW_AS_HIDDEN"] == "Y" || $comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview")
	{	?>
				<div class="comment-wrap" itemprop="review" itemscope itemtype="http://schema.org/Review" id="blg-comment-<?=$comment["ID"]?>">
			<header>
				<div class="date">
					<meta itemprop="datePublished" content="<?=$comment["DATE_CREATE"]?>">
					<?=$comment["DateFormated"]?>
				</div>
				<div class="user-info" itemprop="author" itemscope itemtype="http://schema.org/Person">
					<div class="avatar">
						<?if(!empty($comment['AVATAR_file']['src'])):?>
							<img src="<?=CResizer2Resize::ResizeGD2($comment['AVATAR_file']['src'],$arParams['RESIZER_COMMENT_AVATAR'])?>" alt="<?=$comment['AuthorName']?>">
						<?else:?>
							<i class="flaticon-user12"></i>
						<?endif?>
					</div>
					<div class="name" itemprop="name">
						<?=$comment['AuthorName']?>
					</div>
				</div>
			</header>
			<div class="content">
				<?
				/* TODO
				<div class="rating">
					..............
				</div>
				*/
				?>
				<?
				/* TODO
				<div class="pros">
					..............
				</div>
				<div class="cons">
					..............
				</div>
				*/
				?>
				<?// COMMENT TEXT
				$explode_pos = 400;
				if(strlen($comment["TextFormated"]) > $explode_pos)
				{
					$explode_pos = strpos($comment["TextFormated"], " ", $explode_pos);
					$comment['TEXT_SHORT'] = substr($comment["TextFormated"], 0, $explode_pos);
					$comment['TEXT_FULL'] = substr($comment["TextFormated"], $explode_pos);
				}
				else
				{
					$comment['TEXT_SHORT'] = $comment["TextFormated"];
				}
				?>
				<div class="comment-text" itemprop="reviewBody">
					<?=$comment['TEXT_SHORT']?>
					<div class="hidden-block">
						<?=$comment['TEXT_FULL']?>
					</div>
				</div>
			</div><!-- /.content -->
			<footer>
				<?if(strlen($comment['TEXT_FULL']) > 0):?>
					<span class="link">
						<span class="text when-closed"><?=GetMessage('BITRONIC2_REVIEW_TEXT_SHOW')?></span>
						<span class="text when-opened"><?=GetMessage('BITRONIC2_REVIEW_TEXT_HIDE')?></span>
					</span>
				<?endif?>
				<?
				/* TODO
				<span class="usefulness">
					..................
				</span>
				*/
				?>
			</footer>
		</div><!-- /.comment-wrap -->
		<?			
	}
}