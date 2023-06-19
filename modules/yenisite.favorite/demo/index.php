<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('yenisite.favorite');
?>
<?
if (isset($_REQUEST['ADD']) || isset($_REQUEST['REMOVE']) || isset($_REQUEST['REMOVE_ALL'])) {
	if (!empty($_REQUEST['ADD']) && !empty($_REQUEST['PRODUCT_ID'])) {
		if (!\Yenisite\Favorite\Favorite::add($_REQUEST['PRODUCT_ID'])) {
			echo implode('<br>', \Yenisite\Favorite\Favorite::getErrors());
		} else {
			echo '<h2>ADD SUCCESS</h2>';
		}
	}
	if (!empty($_REQUEST['REMOVE']) && !empty($_REQUEST['PRODUCT_ID'])) {
		if (!\Yenisite\Favorite\Favorite::delete($_REQUEST['PRODUCT_ID'])) {
			echo implode('<br>', \Yenisite\Favorite\Favorite::getErrors());
		} else {
			echo '<h2>DELETE SUCCESS</h2>';
		}
	}
	if (!empty($_REQUEST['REMOVE_ALL'])) {
		if (!\Yenisite\Favorite\Favorite::flush()) {
			echo implode('<br>', \Yenisite\Favorite\Favorite::getErrors());
		} else {
			echo '<h2>FLUSH SUCCESS</h2>';
		}
	}
}
?>
	<pre>
	Current User Favorite:
		<?
		$arResult = \Yenisite\Favorite\Favorite::getProducts();
		foreach ($arResult as $productID) {
			echo "\n", $productID;
		} ?>
	</pre>
	<hr/>
	<br/>
	<form action="" method="get" class="col-xs-10 col-xs-offset-1">
		<div class="form-group">
			<input type="text" name="PRODUCT_ID" id="PRODUCT_ID" title="product" placeholder="product ID"
				   class="form-control"/>
		</div>
		<div class="form-group">
			<input type="submit" name="ADD" value="ADD" class="btn btn-primary"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="REMOVE" value="REMOVE" class="btn btn-danger"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="REMOVE_ALL" value="REMOVE ALL (FLUSH)" class="btn btn-danger"/>
		</div>
	</form>
	<br/><br/>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>