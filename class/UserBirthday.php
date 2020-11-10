<?php

namespace XoopsModules\Birthday;

/**
 * ****************************************************************************
 * birthday - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 * Created on 10 juil. 08 at 13:27:45
 * ****************************************************************************
 */

use XoopsModules\Birthday;



//use XoopsModules\Birthday\Common;

//require_once  dirname(__DIR__) . '/include/common.php';

/**
 * Class UserBirthday
 */
class UserBirthday extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('birthday_id', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('birthday_uid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('birthday_date', \XOBJ_DTYPE_TIMESTAMP, null, false);
        $this->initVar('birthday_photo', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('birthday_description', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('birthday_firstname', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('birthday_lastname', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('birthday_comments', \XOBJ_DTYPE_INT, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);
    }

    /**
     * Retourne l'URL de l'image
     * @return string L'URL
     */
    public function getPictureUrl()
    {
        $utility = new Birthday\Utility();
        if ('' != \xoops_trim($this->getVar('birthday_photo'))) {
            return $utility::getModuleOption('folder_url') . '/' . $this->getVar('birthday_photo');
        }

        return '';
    }

    /**
     * Retourne le chemin de l'image
     * @return string Le chemin
     */
    public function getPicturePath()
    {
        $utility = new Birthday\Utility();
        if ('' != \xoops_trim($this->getVar('birthday_photo'))) {
            return $utility::getModuleOption('folder_path') . '/' . $this->getVar('birthday_photo');
        }

        return '';
    }

    /**
     * Indique si l'image existe
     *
     * @return bool Vrai si l'image existe sinon faux
     */
    public function pictureExists()
    {
        $utility = new Birthday\Utility();
        $return  = false;
        if ('' != \xoops_trim($this->getVar('birthday_photo'))
            && \file_exists($utility::getModuleOption('folder_path') . '/' . $this->getVar('birthday_photo'))) {
            $return = true;
        }

        return $return;
    }

    /**
     * Supprime l'image associ�e
     */
    public function deletePicture()
    {
        $utility = new Birthday\Utility();
        if ($this->pictureExists()) {
            @\unlink($utility::getModuleOption('folder_path') . '/' . $this->getVar('birthday_photo'));
        }
        $this->setVar('birthday_photo', '');
    }

    /**
     * Rentourne la chaine � envoyer dans une balise <a> pour l'attribut href
     *
     * @return string
     */
    public function getHrefTitle()
    {
        $utility = new Birthday\Utility();

        return $utility::makeHrefTitle(\xoops_trim($this->getVar('birthday_lastname')) . ' ' . \xoops_trim($this->getVar('birthday_firstname')));
    }

    /**
     * Retourne l'utilisateur Xoops li� � l'enregistrement courant
     */
    public function getXoopsUser()
    {
        $ret = null;
        static $memberHandler;
        if ($this->getVar('birthday_uid') > 0) {
            if (!isset($memberHandler)) {
                $memberHandler = \xoops_getHandler('member');
            }
            $user = $memberHandler->getUser($this->getVar('birthday_uid'));
            if (\is_object($user)) {
                $ret = $user;
            }
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return \xoops_trim($this->getVar('birthday_lastname')) . ' ' . \xoops_trim($this->getVar('birthday_firstname'));
    }

    /**
     * Retourne les �l�ments format�s pour affichage
     *
     * @param string $format Le format � utiliser
     * @return array  Les donn�es formatt�es
     */
    public function toArray($format = 's')
    {
        $ret = [];
        foreach ($this->vars as $k => $v) {
            $ret[$k] = $this->getVar($k, $format);
        }
        $ret['birthday_full_imgurl'] = $this->getPictureUrl();
        $ret['birthday_href_title']  = $this->getHrefTitle();
        $user                        = null;
        $user                        = $this->getXoopsUser();
        if (\is_object($user)) {
            $ret['birthday_user_name']        = $user->getVar('name');
            $ret['birthday_user_uname']       = $user->getVar('uname');
            $ret['birthday_user_email']       = $user->getVar('email');
            $ret['birthday_user_url']         = $user->getVar('url');
            $ret['birthday_user_user_avatar'] = $user->getVar('user_avatar');
            $ret['birthday_user_user_from']   = $user->getVar('user_from');
        }
        $ret['birthday_formated_date'] = \formatTimestamp(\strtotime($this->getVar('birthday_date')), 's');
        $ret['birthday_fullname']      = $this->getFullName();

        return $ret;
    }
}
