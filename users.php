<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 19:41:07
 * ****************************************************************************
 */

/**
 * Affiche la liste de tous les utilisateurs (ou de tous les utilisateurs dont c'est l'anniversaire aujourd'hui)
 */

use Xmf\Request;

require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'birthday_users.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

$start = Request::getInt('start', 0, 'GET');
$limit = $utility::getModuleOption('perpage');    // Nombre maximum d'éléments à afficher
$users = [];

if (isset($xoopsConfig) && file_exists(BIRTHDAY_PATH . 'language/' . $xoopsConfig['language'] . '/blocks.php')) {
    require_once BIRTHDAY_PATH . 'language/' . $xoopsConfig['language'] . '/blocks.php';
} else {
    require_once BIRTHDAY_PATH . 'language/english/blocks.php';
}

if (Request::hasVar('op', 'GET') && 'today' === $_GET['op']) {    // Les utilisateurs dont l'anniversaire est aujourd'hui
    $itemsCount = $birthdayHandler->getTodayBirthdaysCount();
    if ($itemsCount > $limit) {
        $pagenav = new \XoopsPageNav($itemsCount, $limit, $start, 'start', 'op=today');
    }
    $users = $birthdayHandler->getTodayBirthdays($start, $limit);
} else {    // Tous les utilisateurs
    $itemsCount = $birthdayHandler->getAllUsersCount();
    if ($itemsCount > $limit) {
        $pagenav = new \XoopsPageNav($itemsCount, $limit, $start, 'start');
    }
    if (1 == $utility::getModuleOption('userslist_sortorder')) {    // Sort by date
        $sort  = 'birthday_date';
        $order = 'ASC';
    } else {
        $sort  = 'birthday_lastname';
        $order = 'ASC';
    }
    $users = $birthdayHandler->getAllUsers($start, $limit, $sort, $order);
}
if (count($users) > 0) {
    foreach ($users as $user) {
        $xoopsTpl->append('birthday_users', $user->toArray());
    }
}
if (isset($pagenav) && is_object($pagenav)) {
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
}
$pageTitle       = _BIRTHDAY_USERS_LIST . ' - ' . $utility::getModuleName();
$metaDescription = $pageTitle;
$metaKeywords    = '';
$utility::setMetas($pageTitle, $metaDescription, $metaKeywords);

$path       = [BIRTHDAY_URL . 'users.php' => _BIRTHDAY_USERS_LIST];
$breadcrumb = $utility::breadcrumb($path);
$xoopsTpl->assign('breadcrumb', $breadcrumb);
require_once XOOPS_ROOT_PATH . '/footer.php';
