<?
$MESS['RZ_MODULE_NOT_INSTALLED'] = 'Не установлен обязательный модуль #MODULE_NAME#, для продолжения установки необходимо <a target="_blank" href="/bitrix/admin/update_system_partner.php?addmodule=#MODULE_ID#">установить этот модуль</a>';
$MESS['RZ_MODULE_VERSION_ERROR'] = 'Ошибка проверки версии модуля #MODULE_ID#, обратитесь в техподдержку - <a target="_blank" href="http://portal.yenisite.ru/support/">http://portal.yenisite.ru/support/</a>';
$MESS['RZ_MODULE_VERSION_MINIMAL'] = 'Минимально необходимая версия модуля #MODULE_ID# &mdash; #VERSION_NEED#, текущая версия &mdash; #VERSION_HAS#, для продолжения установки необходимо <a target="_blank" href="/bitrix/admin/update_system_partner.php?addmodule=#MODULE_ID#">обновить модуль</a>';
$MESS['RZ_WIZARD_ERR_NEXT_CAPTION'] = 'Повторить';

// ++++++++++++ EVENT TYPES ++++++++++++//
$MESS["MAIN_NEW_USER_TYPE_NAME"] = "Зарегистрировался новый пользователь";
$MESS["MAIN_NEW_USER_TYPE_DESC"] = "

#USER_ID# - ID пользователя
#LOGIN# - Логин
#EMAIL# - EMail
#NAME# - Имя
#LAST_NAME# - Фамилия
#USER_IP# - IP пользователя
#USER_HOST# - Хост пользователя
";
$MESS["MAIN_USER_INFO_TYPE_NAME"] = "Информация о пользователе";
$MESS["MAIN_USER_INFO_TYPE_DESC"] = "

#USER_ID# - ID пользователя
#STATUS# - Статус логина
#MESSAGE# - Сообщение пользователю
#LOGIN# - Логин
#URL_LOGIN# - Логин, закодированный для использования в URL
#CHECKWORD# - Контрольная строка для смены пароля
#NAME# - Имя
#LAST_NAME# - Фамилия
#EMAIL# - E-Mail пользователя
";
$MESS["MAIN_NEW_USER_CONFIRM_TYPE_NAME"] = "Подтверждение регистрации нового пользователя";
$MESS["MAIN_NEW_USER_CONFIRM_TYPE_DESC"] = "


#USER_ID# - ID пользователя
#LOGIN# - Логин
#EMAIL# - EMail
#NAME# - Имя
#LAST_NAME# - Фамилия
#USER_IP# - IP пользователя
#USER_HOST# - Хост пользователя
#CONFIRM_CODE# - Код подтверждения
";
$MESS["MAIN_USER_INVITE_TYPE_NAME"] = "Приглашение на сайт нового пользователя";
$MESS["MAIN_USER_INVITE_TYPE_DESC"] = "#ID# - ID пользователя
#LOGIN# - Логин
#URL_LOGIN# - Логин, закодированный для использования в URL
#EMAIL# - EMail
#NAME# - Имя
#LAST_NAME# - Фамилия
#PASSWORD# - пароль пользователя
#CHECKWORD# - Контрольная строка для смены пароля
#XML_ID# - ID пользователя для связи с внешними источниками
";
$MESS["MAIN_FEEDBACK_FORM_TYPE_NAME"] = "Отправка сообщения через форму обратной связи";
$MESS["MAIN_FEEDBACK_FORM_TYPE_DESC"] = "#AUTHOR# - Автор сообщения
#AUTHOR_EMAIL# - Email автора сообщения
#TEXT# - Текст сообщения
#EMAIL_FROM# - Email отправителя письма
#EMAIL_TO# - Email получателя письма";
$MESS["MAIN_USER_PASS_REQUEST_TYPE_NAME"] = "Запрос на смену пароля";
$MESS["MAIN_USER_PASS_CHANGED_TYPE_NAME"] = "Подтверждение смены пароля";
// ^^^^^^^^^^^^ EVENT TYPES ^^^^^^^^^^^^//
// ++++++++++++    EVENTS   ++++++++++++//
$MESS["MAIN_NEW_USER_EVENT_NAME"] = "#SITE_NAME#: Зарегистрировался новый пользователь";
$MESS["MAIN_NEW_USER_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------

На сайте #SERVER_NAME# успешно зарегистрирован новый пользователь.

Данные пользователя:
ID пользователя: #USER_ID#

Имя: #NAME#
Фамилия: #LAST_NAME#
E-Mail: #EMAIL#

Login: #LOGIN#

Письмо сгенерировано автоматически.";
$MESS["MAIN_USER_INFO_EVENT_NAME"] = "#SITE_NAME#: Регистрационная информация";
$MESS["MAIN_USER_INFO_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Ваша регистрационная информация:

ID пользователя: #USER_ID#
Статус профиля: #STATUS#
Login: #LOGIN#

Вы можете изменить пароль, перейдя по следующей ссылке:
http://#SERVER_NAME#/auth/index.php?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Сообщение сгенерировано автоматически.";
$MESS["MAIN_USER_PASS_REQUEST_EVENT_NAME"] = "#SITE_NAME#: Запрос на смену пароля";
$MESS["MAIN_USER_PASS_REQUEST_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Для смены пароля перейдите по следующей ссылке:
http://#SERVER_NAME#/auth/index.php?change_password=yes&lang=ru&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Ваша регистрационная информация:

ID пользователя: #USER_ID#
Статус профиля: #STATUS#
Login: #LOGIN#

Сообщение сгенерировано автоматически.";
$MESS["MAIN_USER_PASS_CHANGED_EVENT_NAME"] = "#SITE_NAME#: Подтверждение смены пароля";
$MESS["MAIN_USER_PASS_CHANGED_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
#NAME# #LAST_NAME#,

#MESSAGE#

Ваша регистрационная информация:

ID пользователя: #USER_ID#
Статус профиля: #STATUS#
Login: #LOGIN#

Сообщение сгенерировано автоматически.";
$MESS["MAIN_NEW_USER_CONFIRM_EVENT_NAME"] = "#SITE_NAME#: Подтверждение регистрации нового пользователя";
$MESS["MAIN_NEW_USER_CONFIRM_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------

Здравствуйте,

Вы получили это сообщение, так как ваш адрес был использован при регистрации нового пользователя на сервере #SERVER_NAME#.

Ваш код для подтверждения регистрации: #CONFIRM_CODE#

Для подтверждения регистрации перейдите по следующей ссылке:
http://#SERVER_NAME#/auth/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#

Вы также можете ввести код для подтверждения регистрации на странице:
http://#SERVER_NAME#/auth/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#

Внимание! Ваш профиль не будет активным, пока вы не подтвердите свою регистрацию.

---------------------------------------------------------------------

Сообщение сгенерировано автоматически.";
$MESS["MAIN_USER_INVITE_EVENT_NAME"] = "#SITE_NAME#: Приглашение на сайт";
$MESS["MAIN_USER_INVITE_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------
Здравствуйте, #NAME# #LAST_NAME#!

Администратором сайта вы добавлены в число зарегистрированных пользователей.

Приглашаем Вас на наш сайт.

Ваша регистрационная информация:

ID пользователя: #ID#
Login: #LOGIN#

Рекомендуем вам сменить установленный автоматически пароль.

Для смены пароля перейдите по следующей ссылке:
http://#SERVER_NAME#/auth.php?change_password=yes&USER_LOGIN=#URL_LOGIN#&USER_CHECKWORD=#CHECKWORD#
";
$MESS["MAIN_FEEDBACK_FORM_EVENT_NAME"] = "#SITE_NAME#: Сообщение из формы обратной связи";
$MESS["MAIN_FEEDBACK_FORM_EVENT_DESC"] = "Информационное сообщение сайта #SITE_NAME#
------------------------------------------

Вам было отправлено сообщение через форму обратной связи

Автор: #AUTHOR#
E-mail автора: #AUTHOR_EMAIL#

Текст сообщения:
#TEXT#

Сообщение сгенерировано автоматически.";
// ^^^^^^^^^^^^    EVENTS   ^^^^^^^^^^^^//