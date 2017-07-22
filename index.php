<?php

/**
 * Permet à l'utilisateur courant de modifier sa fiche (si l'option adéquate est activée)
 */
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'birthday_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require XOOPS_ROOT_PATH . '/modules/birthday/include/function.php';

$baseurl = BIRTHDAY_URL . basename(__FILE__);    // URL de ce script
$uid     = 0;
if (is_object($xoopsUser) && BirthdayUtility::getModuleOption('enable_users')) {
    $uid = (int)$xoopsUser->getVar('uid');
} else {
    BirthdayUtility::redirect(_BIRTHDAY_ERROR1, 'users.php', 4);
}

$op = isset($_POST['op']) ? $_POST['op'] : 'default';

switch ($op) {
    case 'default':
        $item    = $hBdUsersBirthday->getFromUid($uid);
        $captcha = '';
        if (BirthdayUtility::getModuleOption('use_captcha')) {
            require_once BIRTHDAY_PATH . 'class/Numeral.php';
            $numcap                      = new birthday_Text_CAPTCHA_Numeral;
            $_SESSION['birthday_answer'] = $numcap->getAnswer();
            $captcha                     = $numcap->getOperation();
        }
        $form = $hBdUsersBirthday->getForm($item, $baseurl, false, $captcha);
        $xoopsTpl->assign('form', $form->render());
        break;

    case 'saveedit':
        if (isset($_POST['captcha']) && isset($_SESSION['birthday_answer'])
            && BirthdayUtility::getModuleOption('use_captcha')) {
            if ($_POST['captcha'] != $_SESSION['birthday_answer']) {
                BirthdayUtility::redirect(_BIRTHDAY_CAPTCHA_WRONG, 'index.php', 4);
            }
        }
        $result = $hBdUsersBirthday->saveUser(true);
        if ($result) {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_OK, 'users.php', 1);
        } else {
            BirthdayUtility::redirect(_AM_BIRTHDAY_SAVE_PB, $baseurl, 3);
        }
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
