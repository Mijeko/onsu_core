<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("title", "Ошибка");
?>
<div class="container">
<p>Произошла ошибка в JS в Вашем браузере отлючено использование JavaScript.</p>

<p>Приносим извинения за доставленные неудобства.</p>

<p>Для возврата на предыдущую страницу нажмите <a href='#' onClick="history.back();return false">здесь</a> либо кнопку "Назад" в Вашем браузере.</p>

<p>Если данная проблема повторяется, пожалуйста, сообщите об этом администрации сайта. Спасибо, за понимание.</p>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>