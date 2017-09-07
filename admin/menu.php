<?php
/**
 * ****************************************************************************
 * Birthday - MODULE FOR XOOPS
 * Script made by Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 jully. 08 at 11:32:40
 * ****************************************************************************
 */

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

// Load language files
$moduleHelper->loadLanguage('admin');
$moduleHelper->loadLanguage('modinfo');
$moduleHelper->loadLanguage('main');

$adminmenu[] = [
    'title' => _MI_BIRTHDAY_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
];

$adminmenu[] = [
    'title' => _MI_BIRTHDAY_BIRTHDAYS,
    'link'  => 'admin/main.php',
    'icon'  => './assets/images/cake.png'
];

$adminmenu[] = [
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];
