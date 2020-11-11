<?php

namespace XoopsModules\Birthday;

/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 13:27:45
 * ****************************************************************************
 */

use Xmf\Request;
use XoopsModules\Birthday;



//require_once XOOPS_ROOT_PATH . '/kernel/object.php';
//if (!class_exists('Birthday_XoopsPersistableObjectHandler')) {
//  require_once XOOPS_ROOT_PATH.'/modules/birthday/class/PersistableObjectHandler.php';
//}

//require_once  dirname(__DIR__) . '/include/common.php';

/**
 * Class BirthdayBirthdayHandler
 */
class UserBirthdayHandler extends \XoopsPersistableObjectHandler //Birthday_XoopsPersistableObjectHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    { //                                Table           Classe          Id              Description
        parent::__construct($db, 'users_birthday', UserBirthday::class, 'birthday_id', 'birthday_lastname');
    }

    /**
     * Retourne un utilisateur � partir de son uid
     *
     * @param int $uid L'ID Xoops recherch�
     * @return \XoopsObject
     */
    public function getFromUid($uid)
    {
        $criteria = new \Criteria('birthday_uid', (int)$uid, '=');
        if ($this->getCount($criteria) > 0) {
            $temp = [];
            $temp = $this->getObjects($criteria);
            if (\count($temp) > 0) {
                return $temp[0];
            }
        }

        return $this->create(true);
    }

    /**
     * Cr�ation du formulaire de saisie
     *
     * @param UserBirthday $item           L'�l�ment � ajouter/modifier
     * @param string       $baseurl        L'url de destination
     * @param bool         $withUserSelect Indique s'il faut inclure la liste de s�lection de l'utilisateur
     * @param bool|string  $captcha        Indique s'il faut utiliser un captcha
     *
     * @return \XoopsThemeForm Le formulaire � utiliser
     */
    public function getForm(UserBirthday $item, $baseurl, $withUserSelect = true, $captcha = '')
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        //        require_once XOOPS_ROOT_PATH . '/modules/birthday/class/formtextdateselect.php';

        global $xoopsModuleConfig;
        $helper  = Birthday\Helper::getInstance();
        $utility = new Birthday\Utility();

        $edit = true;
        if ($item->isNew()) {
            $edit = false;
        }

        if ($edit) {
            $labelSubmit = _AM_BIRTHDAY_MODIFY;
            $title       = _AM_BIRTHDAY_MODIFY_ITEM;
        } else {
            $labelSubmit = _AM_BIRTHDAY_ADD;
            $title       = _AM_BIRTHDAY_ADD_ITEM;
        }
        // Formulaire de cr�ation
        $sform = new \XoopsThemeForm($title, 'frmadd', $baseurl);
        $sform->setExtra('enctype="multipart/form-data"');
        $sform->addElement(new \XoopsFormHidden('op', 'saveedit'));
        $sform->addElement(new \XoopsFormHidden('birthday_id', $item->getVar('birthday_id')));
        if ($withUserSelect) {
            $selectUser = new \XoopsFormSelectUser(_BIRTHDAY_USERNAME, 'birthday_uid', true, $item->getVar('birthday_uid', 'e'));
            $selectUser->setDescription(_AM_BIRTHDAY_USE_ANONYMOUS);
            $sform->addElement($selectUser);
        }
        $date = \strtotime($item->getVar('birthday_date'));
        $sform->addElement(new \XoopsFormTextDateSelect(_BIRTHDAY_DATE, 'birthday_date', 15, $date));
        $sform->addElement(new \XoopsFormText(_BIRTHDAY_FIRSTNAME, 'birthday_firstname', 50, 150, $item->getVar('birthday_firstname', 'e')), false);
        $sform->addElement(new \XoopsFormText(_BIRTHDAY_LASTNAME, 'birthday_lastname', 50, 150, $item->getVar('birthday_lastname', 'e')), false);
        //      $editor = $utility::getWysiwygForm(_BIRTHDAY_DESCRIPTION, 'birthday_description', $item->getVar('birthday_description', 'e'), 15, 60, 'description_hidden');
        //      if ($editor) {
        //            $sform->addElement($editor, false);
        //        }
        $editor_tray = new \XoopsFormElementTray(_BIRTHDAY_DESCRIPTION, '<br>');

        //set Editor options
        $options['name']   = 'birthday_description';
        $options['value']  = $item->getVar('birthday_description', 'e');
        $options['rows']   = 25;
        $options['cols']   = '100%';
        $options['width']  = '100%';
        $options['height'] = '600px';

        $editor = $utility::getEditor($helper, $options);
        $editor_tray->addElement($editor);
        $sform->addElement($editor_tray);

        if ($edit && $item->pictureExists() && '' != \trim($item->getVar('birthday_photo'))) {
            $pictureTray = new \XoopsFormElementTray(_AM_BIRTHDAY_CURRENT_PICTURE, '<br>');
            $pictureTray->addElement(new \XoopsFormLabel('', "<img src='" . $item->getPictureUrl() . '\' alt=\'\' border=\'0\'>'));
            $deleteCheckbox = new \XoopsFormCheckBox('', 'delpicture');
            $deleteCheckbox->addOption(1, _DELETE);
            $pictureTray->addElement($deleteCheckbox);
            $sform->addElement($pictureTray);
            unset($pictureTray, $deleteCheckbox);
        }
        $sform->addElement(new \XoopsFormFile(_AM_BIRTHDAY_PICTURE, 'attachedfile', $utility::getModuleOption('maxuploadsize')), false);
        if ('' != \xoops_trim($captcha)) {
            $captcaField = new \XoopsFormText(_BIRTHDAY_PLEASESOLVE, 'captcha', 30, 30, '');
            $captcaField->setDescription($captcha);
            $sform->addElement($captcaField, true);
        }

        $buttonTray = new \XoopsFormElementTray('', '');
        $submit_btn = new \XoopsFormButton('', 'post', $labelSubmit, 'submit');
        $buttonTray->addElement($submit_btn);
        $sform->addElement($buttonTray);

        //$sform = $utility::formMarkRequiredFields($sform);
        return $sform;
    }

    /**
     * Enregistre un utilisateur apr�s modification (ou ajout)
     *
     * @param bool $withCurrentUser Indique s'il faut prendre l'utilisateur courant ou pas
     * @return bool Vrai si l'enregistrement a r�ussi sinon faux
     */
    public function saveUser($withCurrentUser = false)
    {
        global $destname;
        $utility       = new Birthday\Utility();
        $images_width  = $utility::getModuleOption('images_width');
        $images_height = $utility::getModuleOption('images_height');
        $id            = \Xmf\Request::getInt('birthday_id', 0, 'POST');
        if (!empty($id)) {
            $edit = true;
            $item = $this->get($id);
            if (!\is_object($item)) {
                return false;
            }
            $item->unsetNew();
        } else {
            $edit = false;
            $item = $this->create(true);
        }
        $item->setVars($_POST);
        if ($withCurrentUser) {
            global $xoopsUser;
            $item->setVar('birthday_uid', $xoopsUser->getVar('uid'));
        }
        if (\Xmf\Request::hasVar('delpicture', 'POST') && 1 == \Xmf\Request::getInt('delpicture', 0, 'POST')) {
            if ($item->pictureExists() && '' != \trim($item->getVar('birthday_photo'))) {
                $item->deletePicture();
            }
            $item->setVar('birthday_photo', '');
        }

        $uploadFolder = $utility::getModuleOption('folder_path');

        $return = $utility::uploadFile(0, $uploadFolder);

        if (true === $return) {
            $newDestName = $utility::createUploadName($uploadFolder, \basename($destname), true);
            $retval      = $utility::resizePicture($uploadFolder . '/' . $destname, $uploadFolder . '/' . $newDestName, $images_width, $images_height);
            if (1 == $retval || 3 == $retval) {
                $item->setVar('birthday_photo', $newDestName);
            }
        } else {
            if (false !== $return) {
                echo $return;
            }
        }

        $tempDate = \date(_SHORTDATESTRING, \strtotime(Request::getString('birthday_date', '', 'POST')));

        $item->setVar('birthday_date', $tempDate);

        $res = $this->insert($item);
        if ($res) {
            $utility::updateCache();
        }

        return $res;
    }

    /**
     * Suppression d'un utilisateur
     *
     * @param UserBirthday $user L'utilisateur � supprimer
     * @return bool        Le r�sultat de la suppression
     */
    public function deleteUser(UserBirthday $user)
    {
        $utility = new Birthday\Utility();
        $user->deletePicture();
        $res = $this->delete($user, true);
        if ($res) {
            $utility::updateCache();
        }

        return $res;
    }

    /**
     * Mise � jour du compteur de commentaires pour un utilisateur
     *
     * @param int $userId
     * @param     $commentsCount
     * @internal param int $total_num
     */
    public function updateCommentsCount($userId, $commentsCount)
    {
        $userId        = (int)$userId;
        $commentsCount = (int)$commentsCount;
        $user          = null;
        $user          = $this->get($userId);
        if (\is_object($user)) {
            $criteria = new \Criteria('birthday_id', $userId, '=');
            $this->updateAll('birthday_comments', $commentsCount, $criteria, true);
        }
    }

    /**
     * Retourne les anniversaires du jour
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array  Objets de type Birthday
     */
    public function getTodayBirthdays($start = 0, $limit = 0, $sort = 'birthday_lastname', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('day(birthday_date)', \date('j'), '='));
        $criteria->add(new \Criteria('month(birthday_date)', \date('n'), '='));
        $criteria->setStart($start);
        if ($limit > 0) {
            $criteria->setLimit($limit);
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);

        return $this->getObjects($criteria);
    }

    /**
     * Retourne le nombre total d'anniversaires du jour
     * @return int
     */
    public function getTodayBirthdaysCount()
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('day(birthday_date)', \date('j'), '='));
        $criteria->add(new \Criteria('month(birthday_date)', \date('n'), '='));

        return $this->getCount($criteria);
    }

    /**
     * Retourne le nombre total d'utilisateurs
     *
     * @return int
     */
    public function getAllUsersCount()
    {
        return $this->getCount();
    }

    /**
     * Retourne la liste de tous les utilisateurs
     *
     * @param int    $start Position de d�part
     * @param int    $limit Nombre maximum d'enregistrements
     * @param string $sort  Champ � utiliser pour le tri
     * @param string $order Ordre de tri
     * @return array   Objets de type Birthday
     */
    public function getAllUsers($start = 0, $limit = 0, $sort = 'birthday_lastname', $order = 'ASC')
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('birthday_id', 0, '<>'));
        $criteria->setStart($start);
        if ($limit > 0) {
            $criteria->setLimit($limit);
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);

        return $this->getObjects($criteria);
    }
}
