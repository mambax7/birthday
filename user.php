<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 18:39:52
 * ****************************************************************************
 */

use XoopsModules\Birthday;

/**
 * Affichage de la page d'un utilisateur
 */
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'birthday_user.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$case = 0;
if (isset($_GET['birthday_id'])) {
    $uid  = (int)$_GET['birthday_id'];
    $case = 1;
} elseif (isset($_GET['birthday_uid'])) {
    $uid  = (int)$_GET['birthday_uid'];
    $case = 2;
} elseif (isset($xoopsUser) && is_object($xoopsUser)) {
    $uid  = $xoopsUser->getVar('uid');
    $case = 3;
}

$user = null;
switch ($case) {
    case 0:    // Unknow user
        $utility::redirect(_BIRTHDAY_ERROR2, 'users.php', 3);
        break;

    case 1:    // birthday_id
        $user = $birthdayHandler->get($uid);
        break;

    case 2:    // birthday_uid
    case 3:    // uid
        $user = $birthdayHandler->getFromUid($uid);
        break;
}
if (is_object($user)) {
    $xoopsTpl->assign('birthday_user', $user->toArray());
    $pageTitle       = $user->getFullName() . ' - ' . $utility::getModuleName();
    $metaDescription = $pageTitle;
    $metaKeywords    = $utility::createMetaKeywords($user->getVar('birthday_description'));
    $utility::setMetas($pageTitle, $metaDescription, $metaKeywords);
}
$path       = [
    BIRTHDAY_URL . 'users.php' => _BIRTHDAY_USERS_LIST,
    BIRTHDAY_URL . 'user.php'  => $user->getFullName()
];
$breadcrumb = $utility::breadcrumb($path);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
