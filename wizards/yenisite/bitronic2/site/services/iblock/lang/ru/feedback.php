<?
$MESS ['ELEMENT_NAME'] = "Сообщение";
$MESS ['SECTION_NAME'] = "Раздел";

$MESS ['LINK'] = "Введите ссылку на этот товар в магазине конкурента";
$MESS ['ELEMENT_ID'] = "Элемент";
$MESS ['NAME'] = "Имя";
$MESS ['PHONE'] = "Телефон";
$MESS ['EMAIL'] = "Email";
$MESS ['PRICE'] = "Цена";
$MESS ['PRICE_DESIRED'] = "Желаемая цена";
$MESS ['PRICE_ON_DATE'] = "Цена на момент заявки";
$MESS ['PRICE_OTHER'] = "Цена в другом магазине";
$MESS ['PRICE_TYPE_ID'] = "Тип цены";
$MESS ['PRODUCT'] = "Товар";
$MESS ['IP'] = "IP адрес";
$MESS ['URL'] = "Ссылка";
$MESS ['FIO'] = "ФИО";
$MESS ['QUANTITY'] = "Количество";
$MESS ['COMMENT'] = "Комментарий";

$MESS['YOUR_NAME'] = "Ваше Имя";
$MESS['YOUR_MESSAGE'] = "Ваше сообщение";
$MESS['ORDER_NUMBER'] = "Номер заказа";

//-----------------------------------------
$MESS['FOUND_CHEAP'] = "Нашли дешевле";
$MESS['FOUND_CHEAP_TITLE'] = "Нашли дешевле";
$MESS['FOUND_CHEAP_SUCCESS_TEXT'] = "Спасибо! После обработки запроса наш специалист свяжется с Вами.";
$MESS['FOUND_CHEAP_DESC'] = "
#NAME# - Имя заявителя
#PHONE# - Телефон заявителя
#EMAIL# - E-mail заявителя
#PROPERTIES# - Параметры (содержит название и ссылку на товар)";
$MESS['FOUND_CHEAP_SUBJECT'] = "#SITE_NAME#: Нашли дешевле.";
$MESS['FOUND_CHEAP_TEXT'] = "
<br>Здравствуйте.
<br>
<br>Найден товар по более низкой цене.
<br>#NAME# - Имя нашедшего
<br>#EMAIL# - Адрес электронной почты
<br>#PHONE# - Телефон
<br>
<br>Подробности:
<br>#PROPERTIES#";

//----------------------------------------
$MESS['CALLME'] = "Заказать звонок";
$MESS['CALLME_TITLE'] = "Заказать звонок";
$MESS['CALLME_SUCCESS_TEXT'] = "Спасибо! Ваше обращение принято. После обработки наш специалист свяжется с Вами.";
$MESS['CALLME_DESC'] = "
#NAME# - Имя
#PHONE# - Телефон
#PROPERTIES# - Параметры";
$MESS['CALLME_SUBJECT'] = "#SITE_NAME#: Заказан обратный звонок.";
$MESS['CALLME_TEXT'] = "
Здравствуйте.

#NAME# заказал звонок.
Телефон: #PHONE#";

//----------------------------------------
//----------------------------------------
$MESS['FEEDBACK'] = "Обратная связь";
$MESS['FEEDBACK_TITLE'] = "Заказать звонок";
$MESS['FEEDBACK_SUCCESS_TEXT'] = "Спасибо! Ваше обращение принято. После обработки наш специалист свяжется с Вами.";
$MESS['FEEDBACK_DESC'] = "
#NAME# - Имя
#PHONE# - Телефон
#PROPERTIES# - Параметры";
$MESS['FEEDBACK_SUBJECT'] = "#SITE_NAME#: Создано новое сообщение через форму обратной связи.";
$MESS['FEEDBACK_TEXT'] = "
Здравствуйте.

#NAME# написал сообщение.
Телефон: #PHONE#
E-mail: #EMAIL#

Сообщение: #MESSAGE#

#PROPERTIES#
";

//----------------------------------------
$MESS['ELEMENT_EXIST'] = "Товар в наличии";
$MESS['ELEMENT_EXIST_TITLE'] = "Сообщить о поступлении товара";
$MESS['ELEMENT_EXIST_SUCCESS_TEXT'] = "Спасибо! В случае поступления товара на склад мы сообщим Вам.";
$MESS['ELEMENT_EXIST_DESC'] = "
#PRODUCT_NAME# - название товара
#PRODUCT_URL_FULL# - абсолютная ссылка на страницу товара в публичной части сайта
#EMAIL# - E-mail подписавшегося

--- Устаревшие (с версии 2.17.0 рекомендуется использовать #PRODUCT_URL_FULL#) ---
#PRODUCT_URL# - ссылка на товар относительно корня сайта
#SERVER_NAME# - Доменное имя сервера (нужно для ссылки)
#HTTP# - http или https протокол (нужен для ссылки)

--- Системные поля ---";
$MESS['ELEMENT_EXIST_SUBJECT'] = "#SITE_NAME#: Товар появился в наличии!";
$MESS['ELEMENT_EXIST_TEXT'] = "Здравствуйте!<br>
Товар <a href=\"#PRODUCT_URL_FULL#\">#PRODUCT_NAME#</a> появился в наличии.";

//----------------------------------------
$MESS['ELEMENT_EXIST_ADMIN'] = "Подписка на наличие товара";
$MESS['ELEMENT_EXIST_ADMIN_SUCCESS_TEXT'] = "Спасибо! В случае поступления товара на склад мы сообщим Вам.";
$MESS['ELEMENT_EXIST_ADMIN_DESC'] = "
#EMAIL# - E-mail подписавшегося
#PROPERTIES# - Параметры (содержит название и ссылку на товар)";
$MESS['ELEMENT_EXIST_ADMIN_SUBJECT'] = "#SITE_NAME#: Оформлена подписка на товар!";
$MESS['ELEMENT_EXIST_ADMIN_TEXT'] = "Здравствуйте!<br>
С почтового адреса #EMAIL# совершена подписка на отсутствующий товар.<br>
<br>
#PROPERTIES#";

//----------------------------------------
$MESS['ELEMENT_CONTACT'] = "Заявка на товар";
$MESS['ELEMENT_CONTACT_TITLE'] = "Оставить заявку на товар";
$MESS['ELEMENT_CONTACT_SUCCESS_TEXT'] = "Спасибо! Наши менеджеры свяжутся с вами в ближайшее время.";
$MESS['ELEMENT_CONTACT_DESC'] = "
#PRODUCT_ID# - числовой идентификатор товара
#PRODUCT_NAME# - название товара
#PRODUCT_URL_FULL# - абсолютная ссылка на страницу товара в публичной части сайта
#PRODUCT_URL_ADMIN# - абсолютная ссылка на страницу редактирования товара в административной части сайта
#EMAIL# - E-mail подписавшегося

--- Устаревшие (с версии 2.17.0 рекомендуется использовать #PRODUCT_URL_FULL#) ---
#PRODUCT_URL# - ссылка на товар относительно корня сайта
#SERVER_NAME# - Доменное имя сервера (нужно для ссылки)
#HTTP# - http или https протокол (нужен для ссылки)

--- Системные поля ---";
$MESS['ELEMENT_CONTACT_SUBJECT'] = "#SITE_NAME#: Оставлена заявка на товар по запросу.";
$MESS['ELEMENT_CONTACT_TEXT'] = 'На товар <a href="#PRODUCT_URL_FULL#">#PRODUCT_NAME#</a> [<a href="#PRODUCT_URL_ADMIN#">#PRODUCT_ID#</a>] появилась новая заявка с почтового адреса #EMAIL#.';

//----------------------------------------
$MESS['PRICE_LOWER'] = "Снижение цены";
$MESS['PRICE_LOWER_I_WANT_TEXT'] = "Я хочу";
$MESS['PRICE_LOWER_DIFF_TEXT'] = "Разница";
$MESS['PRICE_LOWER_RUB'] = "р.";
$MESS['PRICE_LOWER_TITLE'] = "Следить за ценой";
$MESS['PRICE_LOWER_SUCCESS_TEXT'] = "Спасибо! В случае снижения цены до заданной мы сообщим Вам.";
$MESS['PRICE_LOWER_DESC'] = "
#NEW_PRICE# - новая цена
#PRODUCT_NAME# - название товара
#PRODUCT_URL_FULL# - абсолютная ссылка на страницу товара в публичной части сайта
#EMAIL# - E-mail подписавшегося

--- Устаревшие (с версии 2.17.0 рекомендуется использовать #PRODUCT_URL_FULL#) ---
#PRODUCT_URL# - ссылка на товар относительно корня сайта
#SERVER_NAME# - Доменное имя сервера (нужно для ссылки)
#HTTP# - http или https протокол (нужен для ссылки)

--- Системные поля ---";
$MESS['PRICE_LOWER_SUBJECT'] = "#SITE_NAME#: Снизилась цена на товар!";
$MESS['PRICE_LOWER_TEXT'] = "Здравствуйте!<br>
Оповещаем вас о том что цена на товар <a href=\"#PRODUCT_URL_FULL#\">#PRODUCT_NAME#</a> снизилась до #NEW_PRICE#.";

//----------------------------------------
$MESS['PRICE_LOWER_ADMIN'] = "Подписка на снижение цены";
$MESS['PRICE_LOWER_ADMIN_SUCCESS_TEXT'] = "Спасибо! В случае снижения цены до заданной мы сообщим Вам.";
$MESS['PRICE_LOWER_ADMIN_DESC'] = "
#EMAIL# - E-mail подписавшегося
#PROPERTIES# - Параметры (содержит желаемую цену, название и ссылку на товар)";
$MESS['PRICE_LOWER_ADMIN_SUBJECT'] = "#SITE_NAME#: Оформлена подписка на товар!";
$MESS['PRICE_LOWER_ADMIN_TEXT'] = "Здравствуйте!<br>
С почтового адреса #EMAIL# совершена подписка на снижение цены товара.<br>
<br>
#PROPERTIES#";