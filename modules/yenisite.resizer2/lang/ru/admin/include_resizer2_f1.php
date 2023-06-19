<?

$MESS ['TEXT'] = '

<h1>Инструкция по работе с модулем Ресайзер 2</h1>
<br/>

<br/><br/>Пункт меню "Наборы":
<br/>Набор - это сущность которая включает себя характеристики изображение (ширину, высоту, качество и т.д.). Каждую такую сущность вы сможете использовать для генерации изображения.

<br/><br/>Пункт меню "Настройки":
<br/>Содержит настройки для позиционирования водяного знака, фона изображения и т.д. А так же подключения необходимых библиотек для работы модуля.

<br/><br/>Пункт меню "Кэширование":
<br/>Генерация фотографии в Ресайзере 2 происходит на лету, после чего сохраняется на диске у вас на сервере. В данном разделе можно сбросить кеш всех фотографий, сгенерированных Ресайзером 2. Например если вы поменяли водяной знак. В Ресайзере 2 есть так же функционал автоматического сброса кэша при изменении набора, причем кэш будет сброшен только для измененного набора.

<br/><br/>В комплекте с Ресайзером 2 идет компонент resizer2.box (+ шаблоны), который позволяет выводить фотографии элемента используя созданные наборы. В настройках компонента необходимо указать ID элемента. Такой компонент можно вставлять в стандартные шаблоны компонентов news.list, news.detail, catalog.element, catalog.section, catalog.top вместо выводя фотографий анонса и детальной.

<br/><br/>
<div style="clear: right; float: left; width: 90%; border: 1px solid #000000; padding: 10px; margin-bottom: 0px;">Смотрите <a href="http://dev.1c-bitrix.ru/community/webdev/user/51651/blog/resizer2-setup/">инструкцию</a> по установке и настройке модуля</a>.
<br/><br/>
Ссылка на видео настройки дополнительных фото каталога:<br/>
<a href="http://screencast.com/t/dBNf3hFQ">http://screencast.com/t/dBNf3hFQ</a>
<br/><br/>
Ссылка на видео настройки всплывающих фото на статических страницах:<br/>
<a href="http://screencast.com/t/BwgyrQ5MTPK">http://screencast.com/t/BwgyrQ5MTPK</a>



</div>
<br style="clear:both"/>
<br/>
<br/>
<table width="100%">

<tr>
<td width="50%" style="padding:5px;"><b>Код для вставки resizer2.box на детальные страницы элементов</b></td>
<td colspan="2"><b>Код для вставки resizer2.box на страницу списка элементов</b></td>
</tr>

<tr><td width="50%" style="padding:5px; vertical-align: top;">
<code>
компоненты <b>news.detail</b>, <b>catalog.element</b><br/><br/>
&lt;?$APPLICATION->IncludeComponent("yenisite:resizer2.box", ".default", array(<br/>
	"ELEMENT_ID" => $arResult[ID],<br/>
	"PROPERTY_CODE" => "PHOTO",<br/>
	"SET_DETAIL" => "2",<br/>
	"SET_BIG_DETAIL" => "1",<br/>
	"SET_SMALL_DETAIL" => "3",<br/>
	"CACHE_TYPE" => "A",<br/>
	"CACHE_TIME" => "3600",<br/>
	"SHOW_DESCRIPTION" => "N",<br/>
	"SHOW_DELAY_DETAIL" => "300",<br/>
	"HIDE_DELAY_DETAIL" => "600",<br/>
	"ZOOM_SPEED_IN" => "600",<br/>
	"ZOOM_SPEED_OUT" => "600",<br/>
	"OVERLAY" => "true",<br/>
	"OVERLAY_OPACITY" => "0.6"<br/>
	),<br/>
	false<br/>
);?&gt;
</code>
</td>
<td width="25%" style="padding:10px; vertical-align: top;">
<code>
компонент <b>news.list</b><br/><br/>
&lt;?$APPLICATION->IncludeComponent("yenisite:resizer2.box", "list", array(<br/>
	"ELEMENT_ID" => $arItem[ID],<br/>
	"PROPERTY_CODE" => "PHOTO",<br/>
	"SET_LIST" => "4",<br/>
	"CACHE_TYPE" => "A",<br/>
	"CACHE_TIME" => "3600",<br/>
	),<br/>
	false<br/>
);?&gt;
</code>
</td>

<td width="25%" style="padding:10px; vertical-align: top;">
<code>
компоненты <b>catalog.section</b>, <b>catalog.top</b><br/><br/>
&lt;?$APPLICATION->IncludeComponent("yenisite:resizer2.box", "list", array(<br/>
	"ELEMENT_ID" => $arElement[ID],<br/>
	"PROPERTY_CODE" => "PHOTO",<br/>
	"SET_LIST" => "4",<br/>
	"CACHE_TYPE" => "A",<br/>
	"CACHE_TIME" => "3600",<br/>
	),<br/>
	false<br/>
);?&gt;
</code>
</td>
</tr>
<tr>
<td colspan="3">Не забудьте поменять код свойства <b>PHOTO</b> на тот код свойства, в котором у вас хранятся фотографии</td>
</tr>

<tr>
<td colspan="3" ><br/><b style="color: red">Если ругается веб-антивирус</b>: <br> идем на вкладку <b>Исключения</b> в <b>Настройки/Проактивная защита/Веб-антивирус</b> и добавляем 2 исключения <b>jquery</b> и <b>colorpicker</b> (см. скриншот <a href="http://screencast.com/t/Xz2RcBmhfiM">http://screencast.com/t/Xz2RcBmhfiM</a>)</td>
</tr>



</table>


<br/><br/>В визуальном редакторе появилась кнопка для вставки кода всплывающего изображения. Теперь можно вставлять иминиатюрное изображение, при клике на котором будет всплывать увеличенное. Для этого требуется выбрать наборы в окне диалога добавления фотографии.<br/><br/>

<b>Внимание!!!</b> для того чтобы работали jquery библиотеки их необходимо подключить самостоятельно, либо выделить галочками в <a href="/bitrix/admin/yci_resizer2_wm.php?lang=ru">настройках</a> модуля "Ресайзер 2". Учтите, если у вас уже подключены одноименные библиотеки и вы включени их повторно в настройках модуля - может возникнуть конфликт.<br/><br/>

<br/><br/>Если будут вопросы, пожалуйста, пишите в нашу <a href="http://portal.yenisite.ru/support/">техническую поддержку</a> (предварительно необходимо будет зарегистрироваться).

';

?>


