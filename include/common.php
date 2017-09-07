<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 13:35:06
 * ****************************************************************************
 */
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

if (!defined('BIRTHDAY_DIRNAME')) {
    define('BIRTHDAY_DIRNAME', 'birthday');
    define('BIRTHDAY_URL', XOOPS_URL . '/modules/' . BIRTHDAY_DIRNAME . '/');
    define('BIRTHDAY_PATH', XOOPS_ROOT_PATH . '/modules/' . BIRTHDAY_DIRNAME . '/');
    define('BIRTHDAY_CACHE_PATH', XOOPS_UPLOAD_PATH . '/' . BIRTHDAY_DIRNAME . '/');

    define('BIRTHDAY_IMAGES_URL', BIRTHDAY_URL . 'assets/images/');
    define('BIRTHDAY_IMAGES_PATH', BIRTHDAY_PATH . 'assets/images/');
    define('BIRTHDAY_THUMB', 'thumb_');
}
$myts = MyTextSanitizer::getInstance();

// Chargement des handler et des autres classes
require_once BIRTHDAY_PATH . 'class/utility.php';
//if (!class_exists('PEAR')) {
//    require_once BIRTHDAY_PATH . 'class/PEAR.php';
//}
$hBdUsersBirthday = xoops_getModuleHandler('users_birthday', BIRTHDAY_DIRNAME);

// Definition of images
if (!defined('_BIRTHDAY_EDIT')) {
    if (!isset($xoopsConfig)) {
        global $xoopsConfig;
    }
    if (isset($xoopsConfig) && file_exists(BIRTHDAY_PATH . 'language/' . $xoopsConfig['language'] . '/main.php')) {
        require_once BIRTHDAY_PATH . 'language/' . $xoopsConfig['language'] . '/main.php';
    } else {
        require_once BIRTHDAY_PATH . 'language/english/main.php';
    }

    $birthdayIcons = [
        'edit'   => "<img src='" . BIRTHDAY_IMAGES_URL . "edit.png' alt='" . _BIRTHDAY_EDIT . '\' align=\'middle\'>',
        'delete' => "<img src='" . BIRTHDAY_IMAGES_URL . "delete.png' alt='" . _BIRTHDAY_DELETE . '\' align=\'middle\'>'
    ];
}
