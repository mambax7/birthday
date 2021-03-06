<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 11:38:52
 * ****************************************************************************
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Birthday;

require_once __DIR__ . '/admin_header.php';
//require_once  dirname(__DIR__, 3) . '/include/cp_header.php';

require_once dirname(__DIR__) . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$adminObject = Admin::getInstance();
$utility     = new Birthday\Utility();

$op = Request::getCmd('op', 'default');

// Lecture de certains param�tres de l'application ********************************************************************
$limit         = $utility::getModuleOption('perpage');    // Nombre maximum d'�l�ments � afficher
$baseurl       = BIRTHDAY_URL . 'admin/' . basename(__FILE__);    // URL de ce script
$conf_msg      = $utility::javascriptLinkConfirm(_AM_BIRTHDAY_CONF_DELITEM);
$images_width  = $utility::getModuleOption('images_width');
$images_height = $utility::getModuleOption('images_height');
$destname      = '';

$cacheFolder = XOOPS_UPLOAD_PATH . '/' . BIRTHDAY_DIRNAME;
if (!is_dir($cacheFolder)) {
    if (!mkdir($cacheFolder) && !is_dir($cacheFolder)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $cacheFolder));
    }
    file_put_contents($cacheFolder . '/index.html', '<script>history.go(-1);</script>');
}

switch ($op) {
    // ****************************************************************************************************************
    case 'default':    // List birthdays and show form to add a someone
        // ****************************************************************************************************************
        xoops_cp_header();
        //echo '<h1>'.$utility::getModuleName().'</h1>';
        $adminObject->displayNavigation(basename(__FILE__));

        $start = Request::getInt('start', 0, 'GET');
        //        $birthdayHandler = new Birthday\BirthdayHandler($db);
        $itemsCount = $birthdayHandler->getCount();
        if ($itemsCount > $limit) {
            $pagenav = new \XoopsPageNav($itemsCount, $limit, $start, 'start');
        }
        if (isset($pagenav) && is_object($pagenav)) {
            echo "<div align='right'>" . $pagenav->renderNav() . '</div>';
        }
        if ($itemsCount > 0) {
            global $birthdayIcons;
            $class = '';
            //$items = $birthdayHandler->getItems($start, $limit, 'birthday_lastname');

            $tblItems = [];
            //$critere = new \Criteria($this->keyName, 0 ,'<>');
            $critere = new \Criteria('birthday_id', 0, '<>');
            $critere->setLimit($limit);
            $critere->setStart($start);
            $critere->setSort('birthday_lastname');
            //                  $critere->setOrder($order);
            //                  $tblItems = $this->getObjects($critere, $idAsKey);

            //            $items = $birthdayHandler->getObjects($start, $limit, 'birthday_lastname');
            $items = &$birthdayHandler->getObjects($critere, $start, $limit, 'birthday_lastname');

            echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
            echo "<tr><th align='center'>" . _BIRTHDAY_DATE . "</th><th align='center'>" . _BIRTHDAY_USERNAME . "</th><th align='center'>" . _BIRTHDAY_LASTNAME . ', ' . _BIRTHDAY_FIRSTNAME . "</th><th align='center'>" . _AM_BIRTHDAY_ACTION . '</th></tr>';
            foreach ($items as $item) {
                $class = ('even' === $class) ? 'odd' : 'even';
                $id    = $item->getVar('birthday_id');
                $user  = null;
                $user  = $item->getXoopsUser();
                $uname = '';
                if (is_object($user)) {
                    $uname = $user->getVar('uname');
                }
                $action_edit   = "<a href='$baseurl?op=edit&id=" . $id . '\' title=\'' . _EDIT . '\'>' . $birthdayIcons['edit'] . '</a>';
                $action_delete = "<a href='$baseurl?op=delete&id=" . $id . '\' title=\'' . _DELETE . '\'' . $conf_msg . '>' . $birthdayIcons['delete'] . '</a>';

                echo "<tr class='" . $class . "'>\n";
                echo "<td align='center'>" . $utility::SQLDateToHuman($item->getVar('birthday_date')) . '</td>';
                echo "<td align='center'>" . $uname . '</td>';
                echo "<td align='center'>" . $item->getFullName() . '</td>';
                echo "<td align='center'>" . $action_edit . ' ' . $action_delete . '</td>';
                echo "</tr>\n";
            }
            echo "</table>\n";
            if (isset($pagenav) && is_object($pagenav)) {
                echo "<div align='left'>" . $pagenav->renderNav() . '</div>';
            }
            echo "<br><br>\n";
        }
        $item = $birthdayHandler->create();
        $form = $birthdayHandler->getForm($item, $baseurl);
        $form->display();
        break;
    // ****************************************************************************************************************
    case 'maintain':    // Maintenance des tables et du cache
        // ****************************************************************************************************************
        xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        require_once dirname(__DIR__) . '/xoops_version.php';
        $tables = [];
        foreach ($modversion['tables'] as $table) {
            $tables[] = $xoopsDB->prefix($table);
        }
        if (count($tables) > 0) {
            $list = implode(',', $tables);
            $xoopsDB->queryF('CHECK TABLE ' . $list);
            $xoopsDB->queryF('ANALYZE TABLE ' . $list);
            $xoopsDB->queryF('OPTIMIZE TABLE ' . $list);
        }
        $utility::updateCache();
        $birthdayHandler->forceCacheClean();
        $utility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 2);
        break;
    // ****************************************************************************************************************
    case 'edit':    // Edition d'un utilisateur existant
        // ****************************************************************************************************************
        xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        $id = Request::getInt('id', 0, 'GET');
        if (empty($id)) {
            $utility::redirect(_AM_BIRTHDAY_ERROR_1, $baseurl, 5);
        }
        // Item exits ?
        $item = null;
        $item = $birthdayHandler->get($id);
        if (!is_object($item)) {
            $utility::redirect(_AM_BIRTHDAY_NOT_FOUND, $baseurl, 5);
        }
        $form = $birthdayHandler->getForm($item, $baseurl);
        $form->display();
        break;
    // ****************************************************************************************************************
    case 'saveedit':    // Enregistrement des modifications
        // ****************************************************************************************************************
        xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        $result = $birthdayHandler->saveUser();
        if ($result) {
            $utility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 1);
        } else {
            $utility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }
        break;
    // ****************************************************************************************************************
    case 'delete':    // Suppression d'un utilisateur
        // ****************************************************************************************************************
        $id = Request::getInt('id', 0, 'GET');
        if (empty($id)) {
            $utility::redirect(_AM_BIRTHDAY_ERROR_1, $baseurl, 5);
        }
        // Item exits ?
        $item = null;
        $item = $birthdayHandler->get($id);
        if (!is_object($item)) {
            $utility::redirect(_AM_BIRTHDAY_NOT_FOUND, $baseurl, 5);
        }
        $result = $birthdayHandler->deleteUser($item);
        if ($result) {
            $utility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 1);
        } else {
            $utility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }
}
require_once __DIR__ . '/admin_footer.php';
//xoops_cp_footer();
