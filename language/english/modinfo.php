<?php

define('_MI_BD_TITRE', 'Birthdays');
define('_MI_BD_DESC', "Show Members' Birthday");

define('_MI_BIRTHDAY_IMAGES_WIDTH', 'Images width');
define('_MI_BIRTHDAY_IMAGES_HEIGHT', 'Images height');

define('_MI_BIRTHDAY_FOLDER_PATH', "Folder's path where to save pictures (without trialing slash)");
define('_MI_BIRTHDAY_FOLDER_URL', "Folder's URL where to save pictures (without trialing slash)");
define('_MI_BIRTHDAY_PERPAGE', 'Items per page');

define('_MI_BIRTHDAY_MIMETYPES', 'Enter authorised Mime Types for upload (separated them on a new line)');
define('_MI_BIRTHDAY_UPLOADFILESIZE', 'MAX Filesize Upload (KB) 1048576 = 1 Meg');

define('_MI_BIRTHDAY_FORM_OPTIONS', 'Text editor to use');
define('_MI_BIRTHDAY_FORM_OPTIONS_DESC', "Select the text editor to use. If you have a 'simple' installation (e.g you use only Xoops core editor class, provided in the standard Xoops core package), then you can just select DHTML and Compact");

define('_MI_BIRTHDAY_FORM_COMPACT', 'Compact');
define('_MI_BIRTHDAY_FORM_DHTML', 'DHTML');
define('_MI_BIRTHDAY_FORM_SPAW', 'Spaw Editor');
define('_MI_BIRTHDAY_FORM_HTMLAREA', 'HtmlArea Editor');
define('_MI_BIRTHDAY_FORM_FCK', 'FCK Editor');
define('_MI_BIRTHDAY_FORM_KOIVI', 'Koivi Editor');
define('_MI_BIRTHDAY_FORM_TINYEDITOR', 'TinyEditor');
define('_MI_BIRTHDAY_HOME', 'Home');
define('_MI_BIRTHDAY_ENABLE_USERS', 'Enable users to fill their form?');
define('_MI_BIRTHDAY_USERS_LIST', 'Users Lists');
define('_MI_BIRTHDAY_SORT_ORDER', 'Sort order on the users page');
define('_MI_BIRTHDAY_SORT_ORDER1', 'By date');
define('_MI_BIRTHDAY_SORT_ORDER2', 'By name');
define('_MI_BIRTHDAY_USE_CAPTCHA', "Use CAPTCHA on user's page?");

//2.3

define('_MI_BIRTHDAY_BIRTHDAYS', 'Birthdays');
define('_MI_BIRTHDAY_MAINTAIN', 'Maintain cache/tables');
define('_MI_BIRTHDAY_ABOUT', 'About');

//2.4
//Help
define('_MI_BIRTHDAY_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_BIRTHDAY_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_BIRTHDAY_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_BIRTHDAY_OVERVIEW', 'Overview');

//define('_MI_BIRTHDAY_HELP_DIR', __DIR__);

//help multi-page
define('_MI_BIRTHDAY_DISCLAIMER', 'Disclaimer');
define('_MI_BIRTHDAY_LICENSE', 'License');
define('_MI_BIRTHDAY_SUPPORT', 'Support');
//2.4.0
define('_MI_BIRTHDAY_SHOW_SAMPLE_BUTTON', 'Show Sample Button?');
define('_MI_BIRTHDAY_SHOW_SAMPLE_BUTTON_DESC', 'If yes, the "Add Sample Data" button will be visible to the Admin. It is Yes as a default for first installation.');

//Tag
define('_MI_BIRTHDAY_USETAG', 'Use tags?');
define('_MI_BIRTHDAY_USETAGDSC', 'Tags module required \"TAG\"');

//Editors
define('MI_BIRTHDAY_EDITOR_ADMIN', 'Editor: Admin');
define('MI_BIRTHDAY_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_BIRTHDAY_EDITOR_USER', 'Editor: User');
define('MI_BIRTHDAY_EDITOR_USER_DESC', 'Select the Editor to use by the User');
