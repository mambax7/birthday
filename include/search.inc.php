<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 11 juil. 08 at 14:53:56
 * ****************************************************************************
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */

use XoopsModules\Birthday;

/**
 * @param array $queryarray
 * @param       $andor
 * @param       $limit
 * @param       $offset
 * @param       $userid
 * @return array
 */
function birthday_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    require_once __DIR__ . '/common.php';
    //    require_once XOOPS_ROOT_PATH . '/modules/birthday/class/UserBirthday.php';

    // Recherche dans les produits
    $sql = 'SELECT birthday_id, birthday_firstname, birthday_lastname, birthday_date, birthday_uid FROM ' . $xoopsDB->prefix('users_birthday') . ' WHERE (birthday_id) <> 0 ';
    if (0 != $userid) {
        $sql .= '  AND birthday_uid = ' . $userid;
    }
    $sql .= ') ';

    $tmpObject = new Birthday\UserBirthday();
    $datas     = &$tmpObject->getVars();
    $tblFields = [];
    $cnt       = 0;
    foreach ($datas as $key => $value) {
        if (XOBJ_DTYPE_TXTBOX == $value['data_type'] || XOBJ_DTYPE_TXTAREA == $value['data_type']) {
            if (0 == $cnt) {
                $tblFields[] = $key;
            } else {
                $tblFields[] = ' OR ' . $key;
            }
            ++$cnt;
        }
    }

    $count = is_array($queryarray) ? count($queryarray) : 0;
    $more  = '';
    if (is_array($queryarray) && $count > 0) {
        $cnt  = 0;
        $sql  .= ' AND (';
        $more = ')';
        foreach ($queryarray as $oneQuery) {
            $sql  .= '(';
            $cond = " LIKE '%" . $oneQuery . "%' ";
            $sql  .= implode($cond, $tblFields) . $cond . ')';
            ++$cnt;
            if ($cnt != $count) {
                $sql .= ' ' . $andor . ' ';
            }
        }
    }
    $sql    .= $more . ' ORDER BY birthday_date DESC';
    $i      = 0;
    $ret    = [];
    $myts   = \MyTextSanitizer::getInstance();
    $result = $xoopsDB->query($sql, $limit, $offset);
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'assets/images/crown.png';
        $ret[$i]['link']  = 'user.php?birthday_id=' . $myrow['birthday_id'];
        $ret[$i]['title'] = htmlspecialchars($myrow['birthday_lastname'] . ' ' . $myrow['birthday_firstname']);
        $ret[$i]['time']  = strtotime($myrow['birthday_date']);
        $ret[$i]['uid']   = $myrow['birthday_uid'];
        ++$i;
    }

    return $ret;
}
