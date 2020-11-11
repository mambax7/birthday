<?php
/**
 * ****************************************************************************
 * Birthday - MODULE FOR XOOPS
 * Script made by Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 jully. 08 at 11:32:40
 * ****************************************************************************
 */

use XoopsModules\Birthday;
use Xmf\Module\Admin;
use XoopsModules\Birthday\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require_once dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = \XoopsModules\Birthday\Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_BIRTHDAY_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_BIRTHDAY_BIRTHDAYS,
    'link'  => 'admin/main.php',
    'icon'  => './assets/images/cake.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

$adminmenu[] = [
    'title' => _MI_BIRTHDAY_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
