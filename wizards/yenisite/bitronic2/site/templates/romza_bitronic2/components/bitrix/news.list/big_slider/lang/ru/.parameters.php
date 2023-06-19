<?
$MESS["NEWS_COUNT"] = "Кол-во показываемых баннеров";
$MESS["SLIDER_WIDTH"] = "Ширина слайдера";
$MESS["MENU_CATALOG"] = "Меню каталога";
$MESS["YOUTUBE_PARAMETERS"] = "Дополнительные параметры для плеера YouTube";
$MESS["YOUTUBE_PARAMETERS_TIP"] = 'Строка с параметрами проигрывателя из <a href="https://developers.google.com/youtube/player_parameters?hl=ru#Parameters" class="text-primary">YouTube IFrame Player API</a>. Параметры нужно писать слитно без пробелов, разделяя знаком амперсанда.
<br>
<br>Например, не показывать в конце похожие видео:
<br><span class="text-info">rel=0</span>
<br>Зациклить воспроизведение видеоролика:
<br><span class="text-info">version=3&amp;loop=1&amp;playlist=%VIDEO_ID%</span>
<br>
<br>Вместо макроса <span class="text-primary">%VIDEO_ID%</span> будет подставлен идентификатор видеоролика из свойства инфоблока.
<br><span class="text-muted">
<br>Со списком всех доступных параметров можно ознакомиться в <a href="https://developers.google.com/youtube/player_parameters?hl=ru#Parameters">справочнике YouTube</a>.
<br>Параметр autoplay уже имеется в свойствах инфоблока с баннерами и включается через API Javascript, поэтому не нужно указывать его в списке общих параметров.</span>';

// Resizer
$MESS["RESIZER_SETS"] = "Настройка наборов Ресайзера";
$MESS["RESIZER_SET_1200"] = "Баннер до 1200 пикс.";
$MESS["RESIZER_SET_991"] = "Баннер до 991 пикс.";
$MESS["RESIZER_SET_FROM_1200"] = "Баннер от 1200 пикс.";
$MESS["USE_RESIZER_SET_FROM_1200"] = "Использовать ресайзер для баннера при ширине страницы свыше 1200пикс.";

$MESS["USE_RESIZER_SET_FROM_1200_TIP"] = "Если включена данная опция, то для масштабирования картинки баннера при ширине страницы свыше 1200пикс. будет использоваться модуль Ресайзер. Иначе будет выведена загруженная картинка оригинал";

$MESS["RESIZER_SET_FROM_1200"] = 
$MESS["RESIZER_SET_1200_TIP"] = 
$MESS["RESIZER_SET_991_TIP"] = 
"Выбранный набор используется при ширине страницы до указанного значения";
?>