<?
// ++++++++++++ EVENT TYPES ++++++++++++//
$MESS["MAIN_NEW_USER_TYPE_NAME"] = "New user was registered";
$MESS["MAIN_NEW_USER_TYPE_DESC"] = "

#USER_ID# - User ID
#LOGIN# - Login
#EMAIL# - EMail
#NAME# - Name
#LAST_NAME# - Last Name
#USER_IP# - User IP
#USER_HOST# - User Host
";
$MESS["MAIN_USER_INFO_TYPE_NAME"] = "Account Information";
$MESS["MAIN_USER_INFO_TYPE_DESC"] = "

#USER_ID# - User ID
#STATUS# - Account status
#MESSAGE# - Message for user
#LOGIN# - Login
#URL_LOGIN# - Encoded login for use in URL
#CHECKWORD# - Check string for password change
#NAME# - Name
#LAST_NAME# - Last Name
#EMAIL# - User E-Mail
";
$MESS["MAIN_NEW_USER_CONFIRM_TYPE_NAME"] = "New user registration confirmation";
$MESS["MAIN_NEW_USER_CONFIRM_TYPE_DESC"] = "

#USER_ID# - User ID
#LOGIN# - Login
#EMAIL# - E-mail
#NAME# - First name
#LAST_NAME# - Last name
#USER_IP# - User IP
#USER_HOST# - User host
#CONFIRM_CODE# - Confirmation code
";
$MESS["MAIN_USER_INVITE_TYPE_NAME"] = "Invitation of a new site user";
$MESS["MAIN_USER_INVITE_TYPE_DESC"] = "#ID# - User ID
#LOGIN# - Login
#URL_LOGIN# - Encoded login for use in URL
#EMAIL# - EMail
#NAME# - Name
#LAST_NAME# - Last Name
#PASSWORD# - User password
#CHECKWORD# - Password check string
#XML_ID# - User ID to link with external data sources

";
$MESS["MAIN_FEEDBACK_FORM_TYPE_NAME"] = "Sending a message using a feedback form";
$MESS["MAIN_FEEDBACK_FORM_TYPE_DESC"] = "#AUTHOR# - Message author
#AUTHOR_EMAIL# - Author's e-mail address
#TEXT# - Message text
#EMAIL_FROM# - Sender's e-mail address
#EMAIL_TO# - Recipient's e-mail address";
$MESS["MAIN_USER_PASS_REQUEST_TYPE_NAME"] = "Password Change Request";
$MESS["MAIN_USER_PASS_CHANGED_TYPE_NAME"] = "Password Change Confirmation";
// ^^^^^^^^^^^^ EVENT TYPES ^^^^^^^^^^^^//
// ++++++++++++    EVENTS   ++++++++++++//
$MESS["MAIN_NEW_USER_EVENT_NAME"] = "#SITE_NAME#: New user has been registered on the site";
$MESS["MAIN_NEW_USER_EVENT_DESC"] = "Informational message from #SITE_NAME#
---------------------------------------

New user has been successfully registered on the site #SERVER_NAME#.

User details:
User ID: #USER_ID#

Name: #NAME#
Last Name: #LAST_NAME#
User's E-Mail: #EMAIL#

Login: #LOGIN#

Automatically generated message.";
$MESS["MAIN_USER_INFO_EVENT_NAME"] = "#SITE_NAME#: Registration info";
$MESS["MAIN_USER_INFO_EVENT_DESC"] = "Informational message from #SITE_NAME#
---------------------------------------

#NAME# #LAST_NAME#,

#MESSAGE#

Your registration info:

User ID: #USER_ID#
Account status: #STATUS#
Login: #LOGIN#

To change your password please visit the link below:
http://#SERVER_NAME#/auth/index.php?change_password=yes&lang=en&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Automatically generated message.";
$MESS["MAIN_USER_PASS_REQUEST_EVENT_NAME"] = "#SITE_NAME#: Password Change Request";
$MESS["MAIN_USER_PASS_REQUEST_EVENT_DESC"] = "Informational message from #SITE_NAME#
---------------------------------------

#NAME# #LAST_NAME#,

#MESSAGE#

To change your password please visit the link below:
http://#SERVER_NAME#/auth/index.php?change_password=yes&lang=en&USER_CHECKWORD=#CHECKWORD#&USER_LOGIN=#URL_LOGIN#

Your registration info:

User ID: #USER_ID#
Account status: #STATUS#
Login: #LOGIN#

Automatically generated message.";
$MESS["MAIN_USER_PASS_CHANGED_EVENT_NAME"] = "#SITE_NAME#: Password Change Confirmation";
$MESS["MAIN_USER_PASS_CHANGED_EVENT_DESC"] = "Informational message from #SITE_NAME#
---------------------------------------

#NAME# #LAST_NAME#,

#MESSAGE#

Your registration info:

User ID: #USER_ID#
Account status: #STATUS#
Login: #LOGIN#

Automatically generated message.";
$MESS["MAIN_NEW_USER_CONFIRM_EVENT_NAME"] = "#SITE_NAME#: New user registration confirmation";
$MESS["MAIN_NEW_USER_CONFIRM_EVENT_DESC"] = "Greetings from #SITE_NAME#!
------------------------------------------

Hello,

you have received this message because you (or someone else) used your e-mail to register at #SERVER_NAME#.

Your registration confirmation code: #CONFIRM_CODE#

Please use the link below to verify and activate your registration:
http://#SERVER_NAME#/auth/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#&confirm_code=#CONFIRM_CODE#

Alternatively, open this link in your browser and enter the code manually:
http://#SERVER_NAME#/auth/index.php?confirm_registration=yes&confirm_user_id=#USER_ID#

Attention! Your account will not be activated until you confirm registration.

---------------------------------------------------------------------

Automatically generated message.";
$MESS["MAIN_USER_INVITE_EVENT_NAME"] = "#SITE_NAME#: Invitation to site";
$MESS["MAIN_USER_INVITE_EVENT_DESC"] = "Informational message from site #SITE_NAME#
------------------------------------------
Hello #NAME# #LAST_NAME#!

Administrator has added you to registered site users.

We invite you to visit our site.

Your registration info:

User ID: #ID#
Login: #LOGIN#

We recommend you to change automatically generated password.

To change password please follow the link:
http://#SERVER_NAME#/auth.php?change_password=yes&USER_LOGIN=#URL_LOGIN#&USER_CHECKWORD=#CHECKWORD#";
$MESS["MAIN_FEEDBACK_FORM_EVENT_NAME"] = "#SITE_NAME#: A feedback form message";
$MESS["MAIN_FEEDBACK_FORM_EVENT_DESC"] = "Notification from #SITE_NAME#
------------------------------------------

A message has been sent to you from the feedback form.

Sent by: #AUTHOR#
Sender's e-mail: #AUTHOR_EMAIL#

Message text:
#TEXT#

This notification has been generated automatically.";
// ^^^^^^^^^^^^    EVENTS   ^^^^^^^^^^^^//