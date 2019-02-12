<?php

/**
 * Permet à l'utilisateur courant de modifier sa fiche (si l'option adéquate est activée)
 */

use XoopsModules\Birthday;

require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'birthday_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require XOOPS_ROOT_PATH . '/modules/birthday/include/function.php';

$utility = new Birthday\Utility();

$baseurl = BIRTHDAY_URL . basename(__FILE__);    // URL de ce script
$uid     = 0;
if (is_object($xoopsUser) && $utility::getModuleOption('enable_users')) {
    $uid = (int)$xoopsUser->getVar('uid');
} else {
    $utility::redirect(_BIRTHDAY_ERROR1, 'users.php', 4);
}

$op = \Xmf\Request::getCmd('op', 'default');

switch ($op) {
    case 'default':
        $item    = $birthdayHandler->getFromUid($uid);
        $captcha = '';
        if ($utility::getModuleOption('use_captcha')) {
            require_once BIRTHDAY_PATH . 'class/Numeral.php';
            $numcap                      = new Birthday\Text_CAPTCHA_Numeral();
            $_SESSION['birthday_answer'] = $numcap->getAnswer();
            $captcha                     = $numcap->getOperation();
        }
        $form = $birthdayHandler->getForm($item, $baseurl, false, $captcha);
        $xoopsTpl->assign('form', $form->render());
        break;
    case 'saveedit':
        if (\Xmf\Request::hasVar('captcha', 'POST') && isset($_SESSION['birthday_answer'])
            && $utility::getModuleOption('use_captcha')) {
            if ($_POST['captcha'] != $_SESSION['birthday_answer']) {
                $utility::redirect(_BIRTHDAY_CAPTCHA_WRONG, 'index.php', 4);
            }
        }
        $result = $birthdayHandler->saveUser(true);
        if ($result) {
            $utility::redirect(_AM_BIRTHDAY_SAVE_OK, 'users.php', 1);
        } else {
            $utility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
