<?global $bRzSingleOne;
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']);
if (!is_bool($bRzSingleOne)) {
	$bRzSingleOne = false;
}?>
<? if (!$bRzSingleOne && !$isAjax): ?>
	<?
	CJSCore::RegisterExt("bs_modal", Array(
		'js' => $templateFolder . '/modal.js',
		'css' => $templateFolder . '/modal.css',
		'rel' => array('jquery'),
		'skip_core' => 'true'
	));
	CJSCore::Init(array("bs_modal"));
	?>
	<script type="text/javascript">
		if(typeof rzSingleOne == 'undefined') {
			rzSingleOne = {
				'AJAX_URL': "<?= $this->__path , '/component.php'?>",
				'URL' : "<?=$APPLICATION->GetCurPage(true)?>"
			};
		}
	</script>
	<? $bRzSingleOne = true; ?>
<? endif ?>