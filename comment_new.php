<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 20 oct. 07 at 14:38:20
 * ****************************************************************************
 */

require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';
$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    include XOOPS_ROOT_PATH . '/modules/birthday/include/common.php';
    $user = null;
    $user = $birthdayHandler->get($com_itemid);
    if (is_object($user)) {
        $com_replytitle = $user->getFullName();
        require XOOPS_ROOT_PATH . '/include/comment_new.php';
    } else {
        exit();
    }
}
