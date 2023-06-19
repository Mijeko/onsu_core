<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнеры");
?>
<main class="container">
	<div class="row">
		<article class="col-xs-12">
			<h1><? $APPLICATION->ShowTitle('h1') ?></h1>

<? $APPLICATION->IncludeComponent(
	"bitrix:catalog.sections.top", 
	"partners", 
	array(
		"IBLOCK_TYPE" => "content",	// Тип инфоблока
		"IBLOCK_ID" => "#PARTNERS_IBLOCK_ID#",	// Инфоблок
		"RESIZER_SET" => "#PARTNER_LIST_RESIZER_SET#",
		"SECTION_SORT_FIELD" => "sort",	// По какому полю сортируем разделы
		"SECTION_SORT_ORDER" => "asc",	// Порядок сортировки разделов
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
		"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
		"FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
		"SECTION_COUNT" => "20",	// Максимальное количество выводимых разделов
		"ELEMENT_COUNT" => "20",	// Максимальное количество элементов, выводимых в каждом разделе
		"PROPERTY_CODE" => array(	// Свойства
			0 => "PHONE",
			1 => "EMAIL",
		),
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"COMPONENT_TEMPLATE" => "partners",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

		</article>
	</div>
</main>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>