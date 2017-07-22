<?php
/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 11:38:52

 * ****************************************************************************
 */
require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../include/common.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$adminObject = \Xmf\Module\Admin::getInstance();

$op = 'default';
if (isset($_POST['op'])) {
    $op = $_POST['op'];
} elseif (isset($_GET['op'])) {
    $op = $_GET['op'];
}

// Lecture de certains param�tres de l'application ********************************************************************
$limit         = BirthdayUtility::getModuleOption('perpage');    // Nombre maximum d'�l�ments � afficher
$baseurl       = BIRTHDAY_URL . 'admin/' . basename(__FILE__);    // URL de ce script
$conf_msg      = BirthdayUtility::javascriptLinkConfirm(_AM_BIRTHDAY_CONF_DELITEM);
$images_width  = BirthdayUtility::getModuleOption('images_width');
$images_height = BirthdayUtility::getModuleOption('images_height');
$destname      = '';

$cacheFolder = XOOPS_UPLOAD_PATH . '/' . BIRTHDAY_DIRNAME;
if (!is_dir($cacheFolder)) {
    mkdir($cacheFolder, 0777);
    file_put_contents($cacheFolder . '/index.html', '<script>history.go(-1);</script>');
}

switch ($op) {
    // ****************************************************************************************************************
    case 'default':    // List birthdays and show form to add a someone
        // ****************************************************************************************************************
        xoops_cp_header();
        //echo '<h1>'.BirthdayUtility::getModuleName().'</h1>';
        $adminObject->displayNavigation(basename(__FILE__));

        $start      = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        $itemsCount = $hBdUsersBirthday->getCount();
        if ($itemsCount > $limit) {
            $pagenav = new XoopsPageNav($itemsCount, $limit, $start, 'start');
        }
        if (isset($pagenav) && is_object($pagenav)) {
            echo "<div align='right'>" . $pagenav->renderNav() . '</div>';
        }
        if ($itemsCount > 0) {
            global $birthdayIcons;
            $class = '';
            //$items = $hBdUsersBirthday->getItems($start, $limit, 'birthday_lastname');

            $tblItems = [];
            //$critere = new Criteria($this->keyName, 0 ,'<>');
            $critere = new Criteria('birthday_id', 0, '<>');
            $critere->setLimit($limit);
            $critere->setStart($start);
            $critere->setSort('birthday_lastname');
            //                  $critere->setOrder($order);
            //                  $tblItems = $this->getObjects($critere, $idAsKey);

            $items = $hBdUsersBirthday->getObjects($start, $limit, 'birthday_lastname');

            echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
            echo "<tr><th align='center'>" . _BIRTHDAY_DATE . "</th><th align='center'>" . _BIRTHDAY_USERNAME . "</th><th align='center'>" . _BIRTHDAY_LASTNAME . ', ' . _BIRTHDAY_FIRSTNAME . "</th><th align='center'>" . _AM_BIRTHDAY_ACTION . '</th></tr>';
            foreach ($items as $item) {
                $class = ($class === 'even') ? 'odd' : 'even';
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
                echo "<td align='center'>" . BirthdayUtility::SQLDateToHuman($item->getVar('birthday_date')) . '</td>';
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
        $item = $hBdUsersBirthday->create(true);
        $form = $hBdUsersBirthday->getForm($item, $baseurl);
        $form->display();
        break;

    // ****************************************************************************************************************
    case 'maintain':    // Maintenance des tables et du cache
        // ****************************************************************************************************************
        xoops_cp_header();
        require_once __DIR__ . '/../xoops_version.php';
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
        BirthdayUtility::updateCache();
        $hBdUsersBirthday->forceCacheClean();
        BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 2);
        break;

    // ****************************************************************************************************************
    case 'edit':    // Edition d'un utilisateur existant
        // ****************************************************************************************************************
        xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (empty($id)) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_ERROR_1, $baseurl, 5);
        }
        // Item exits ?
        $item = null;
        $item = $hBdUsersBirthday->get($id);
        if (!is_object($item)) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_NOT_FOUND, $baseurl, 5);
        }
        $form = $hBdUsersBirthday->getForm($item, $baseurl);
        $form->display();
        break;

    // ****************************************************************************************************************
    case 'saveedit':    // Enregistrement des modifications
        // ****************************************************************************************************************
        xoops_cp_header();
        $adminObject->displayNavigation(basename(__FILE__));
        $result = $hBdUsersBirthday->saveUser();
        if ($result) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 1);
        } else {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }
        break;

    // ****************************************************************************************************************
    case 'delete':    // Suppression d'un utilisateur
        // ****************************************************************************************************************
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (empty($id)) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_ERROR_1, $baseurl, 5);
        }
        // Item exits ?
        $item = null;
        $item = $hBdUsersBirthday->get($id);
        if (!is_object($item)) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_NOT_FOUND, $baseurl, 5);
        }
        $result = $hBdUsersBirthday->deleteUser($item);
        if ($result) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_OK, $baseurl, 1);
        } else {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }

}
require_once __DIR__ . '/admin_footer.php';
//xoops_cp_footer();
