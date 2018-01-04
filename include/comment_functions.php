<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 20 oct. 07 at 14:38:20
 * ****************************************************************************
 */

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

use XoopsModules\Birthday;

/**
 * @param $userId
 * @param $total_num
 */
function birthday_com_update($userId, $total_num)
{
    $birthdayHandler = new Birthday\UserBirthdayHandler($db);

    $birthdayHandler->updateCommentsCount($userId, $total_num);
}

/**
 * @param $comment
 */
function birthday_com_approve(&$comment)
{
    // notification mail here
}
