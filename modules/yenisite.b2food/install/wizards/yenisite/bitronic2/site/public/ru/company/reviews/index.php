<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы");
?>
<main class="container">
	<div class="row">
		<div class="col-xs-12">
			<h1><?$APPLICATION->ShowTitle()?></h1>
		</div>
	</div>
	<? \Yenisite\Core\Tools::IncludeArea('about', 'reviews', false, true) ?>
</main>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>