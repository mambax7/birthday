<?php

namespace XoopsModules\Birthday;

use XoopsModules\Birthday;
use XoopsModules\Birthday\Common;
use XoopsModules\Birthday\Constants;

$moduleDirName = \basename(\dirname(__DIR__));
\xoops_loadLanguage('admin', $moduleDirName);
if (!\class_exists(\ucfirst($moduleDirName) . 'DummyObject')) {
    \xoops_load('dummy', $moduleDirName);
}

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    const MODULE_NAME = 'birthday';

    /**
     * Access the only instance of this class
     *
     * @return \XoopsModules\Birthday\Utility
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Returns a module's option (with cache)
     *
     * @param string $option module option's name
     * @return mixed  option's value
     */
    public static function getModuleOption($option)
    {
        global $xoopsModuleConfig, $xoopsModule;
        $repmodule = self::MODULE_NAME;
        static $tbloptions = [];
        if (\is_array($tbloptions) && \array_key_exists($option, $tbloptions)) {
            return $tbloptions[$option];
        }

        $retval = false;
        if (isset($xoopsModuleConfig)
            && (\is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule
                && $xoopsModule->getVar('isactive'))) {
            if (isset($xoopsModuleConfig[$option])) {
                $retval = $xoopsModuleConfig[$option];
            }
        } else {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = \xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($repmodule);
            /** @var \XoopsConfigHandler $configHandler */
            $configHandler = \xoops_getHandler('config');
            if ($module) {
                $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
                if (isset($moduleConfig[$option])) {
                    $retval = $moduleConfig[$option];
                }
            }
        }
        $tbloptions[$option] = $retval;

        return $retval;
    }

    /**
     * Create (in a link) a javascript confirmation's box
     *
     * @param string $message Message to display
     * @param bool   $form    Is this a confirmation for a form ?
     * @return string  the javascript code to insert in the link (or in the form)
     */
    public static function javascriptLinkConfirm($message, $form = false)
    {
        if (!$form) {
            return "onclick=\"javascript:return confirm('" . \str_replace('\'', ' ', $message) . '\')"';
        }

        return "onSubmit=\"javascript:return confirm('" . \str_replace('\'', ' ', $message) . '\')"';
    }

    /**
     * Set the page's title, meta description and meta keywords
     * Datas are supposed to be sanitized
     *
     * @param string $pageTitle       Page's Title
     * @param string $metaDescription Page's meta description
     * @param string $metaKeywords    Page's meta keywords
     */
    public static function setMetas($pageTitle = '', $metaDescription = '', $metaKeywords = '')
    {
        global $xoTheme, $xoTheme, $xoopsTpl;
        $xoopsTpl->assign('xoops_pagetitle', $pageTitle);
        if (isset($xoTheme) && \is_object($xoTheme)) {
            if (!empty($metaKeywords)) {
                $xoTheme->addMeta('meta', 'keywords', $metaKeywords);
            }
            if (!empty($metaDescription)) {
                $xoTheme->addMeta('meta', 'description', $metaDescription);
            }
        } elseif (isset($xoopsTpl) && \is_object($xoopsTpl)) {    // Compatibility for old Xoops versions
            if (!empty($metaKeywords)) {
                $xoopsTpl->assign('xoops_meta_keywords', $metaKeywords);
            }
            if (!empty($metaDescription)) {
                $xoopsTpl->assign('xoops_meta_description', $metaDescription);
            }
        }
    }

    /**
     * Remove module's cache
     */
    public static function updateCache()
    {
        global $xoopsModule;
        $folder  = $xoopsModule->getVar('dirname');
        $tpllist = [];
        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $tplfileHandler = \xoops_getHandler('tplfile');
        $tpllist        = $tplfileHandler->find(null, null, null, $folder);
        \xoops_template_clear_module_cache($xoopsModule->getVar('mid'));            // Clear module's blocks cache

        foreach ($tpllist as $onetemplate) {    // Remove cache for each page.
            if ('module' === $onetemplate->getVar('tpl_type')) {
                //  Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
                $files_del = [];
                $files_del = \glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*', GLOB_NOSORT);
                if ($files_del && \is_array($files_del)) {
                    foreach ($files_del as $one_file) {
                        if (\is_file($one_file)) {
                            \unlink($one_file);
                        }
                    }
                }
            }
        }
    }

    /**
     * Redirect user with a message
     *
     * @param string $message message to display
     * @param string $url     The place where to go
     * @param int    $time    Time to wait before to redirect
     */
    public static function redirect($message = '', $url = 'index.php', $time = 2)
    {
        \redirect_header($url, $time, $message);
    }

    /**
     * Internal function used to get the handler of the current module
     *
     * @return \XoopsModule The module
     */
    protected static function _getModule()
    {
        static $mymodule;
        if (!isset($mymodule)) {
            global $xoopsModule;
            if (isset($xoopsModule) && \is_object($xoopsModule) && BIRTHDAY_DIRNAME == $xoopsModule->getVar('dirname')) {
                $mymodule = &$xoopsModule;
            } else {
                $moduleHandler = \xoops_getHandler('module');
                $mymodule      = $moduleHandler->getByDirname(BIRTHDAY_DIRNAME);
            }
        }

        return $mymodule;
    }

    /**
     * Returns the module's name (as defined by the user in the module manager) with cache
     * @return string Module's name
     */
    public static function getModuleName()
    {
        static $moduleName;
        if (!isset($moduleName)) {
            $mymodule   = self::_getModule();
            $moduleName = $mymodule->getVar('name');
        }

        return $moduleName;
    }

    /**
     * Create a title for the href tags inside html links
     *
     * @param string $title Text to use
     * @return string Formated text
     */
    public static function makeHrefTitle($title)
    {
        $s = "\"'";
        $r = '  ';

        return strtr($title, $s, $r);
    }

    /**
     * Verify that the current user is a member of the Admin group
     *
     * @return bool Admin or not
     */
    public function isAdmin()
    {
        global $xoopsUser, $xoopsModule;
        if (\is_object($xoopsUser)) {
            if (\in_array(XOOPS_GROUP_ADMIN, $xoopsUser->getGroups())) {
                return true;
            }
            if (isset($xoopsModule)) {
                if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
                    return true;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    /**
     * Returns the current date in the Mysql format
     *
     * @return string Date in the Mysql format
     */
    public function getCurrentSQLDate()
    {
        return \date('Y-m-d');    // 2007-05-02
    }

    /**
     * Convert a Mysql date to the human's format
     *
     * @param string $date The date to convert
     * @param string $format
     *
     * @return string The date in a human form
     */
    public static function SQLDateToHuman($date, $format = 's')
    {
        if ('0000-00-00' != $date && '' != \xoops_trim($date)) {
            return \formatTimestamp(\strtotime($date), $format);
        }

        return '';
    }

    /**
     * Convert a timestamp to a Mysql date
     *
     * @param int $timestamp The timestamp to use
     * @return string  The date in the Mysql format
     */
    public function timestampToMysqlDate($timestamp)
    {
        return \date('Y-m-d', (int)$timestamp);
    }

    /**
     * This function indicates if the current Xoops version needs to add asterisks to required fields in forms
     *
     * @param mixed $folder
     * @param mixed $fileName
     * @param mixed $trimName
     * @return bool Yes = we need to add them, false = no
     */
    //  function needsAsterisk()
    //  {
    //      if (self::isX22() || self::isX23()) {
    //          return false;
    //      }
    //      if (strpos(strtolower(XOOPS_VERSION), 'impresscms') !== false) {
    //          return false;
    //      }
    //      if (strpos(strtolower(XOOPS_VERSION), 'legacy') === false) {
    //          $xv = xoops_trim(str_replace('XOOPS ','',XOOPS_VERSION));
    //          if ((int)(substr($xv,4,2)) >= 17) {
    //              return false;
    //          }
    //      }
    //      return true;
    //  }

    /**
     * Mark the mandatory fields of a form with a star
     *
     * @param object $sform    The form to modify
     * @param string $caracter The character to use to mark fields
     * @return object The modified form
     */
    //  function &formMarkRequiredFields(&$sform)
    //  {
    //      if (self::needsAsterisk()) {
    //          $tblRequired = [];
    //          foreach ($sform->getRequired() as $item) {
    //              $tblRequired[] = $item->_name;
    //          }
    //          $tblElements = [];
    //          $tblElements = & $sform->getElements();
    //          $cnt = count($tblElements);
    //          for ($i=0; $i<$cnt; ++$i) {
    //              if( is_object($tblElements[$i]) && in_array($tblElements[$i]->_name, $tblRequired)
    //              ) {
    //                  $tblElements[$i]->_caption .= ' *';
    //              }
    //          }
    //      }
    //      return $sform;
    //  }

    /**
     * Create a unique upload filename
     *
     * @param string $folder   The folder where the file will be saved
     * @param string $fileName Original filename (coming from the user)
     * @param bool   $trimName Do we need to create a short unique name ?
     * @return string  The unique filename to use (with its extension)
     */
    public static function createUploadName($folder, $fileName, $trimName = false)
    {
        $workingfolder = $folder;
        if ('/' !== \xoops_substr($workingfolder, mb_strlen($workingfolder) - 1, 1)) {
            $workingfolder .= '/';
        }
        $ext  = \basename($fileName);
        $ext  = \explode('.', $ext);
        $ext  = '.' . $ext[\count($ext) - 1];
        $true = true;
        while ($true) {
            $ipbits = \explode('.', $_SERVER['REMOTE_ADDR']);
            [$usec, $sec] = \explode(' ', \microtime());
            $usec = ($usec * 65536);
            $sec  = ((int)$sec) & 0xFFFF;

            if ($trimName) {
                $uid = \sprintf('%06x%04x%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = \sprintf('%08x-%04x-%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if (!\file_exists($workingfolder . $uid . $ext)) {
                $true = false;
            }
        }

        return $uid . $ext;
    }

    /**
     * Create the meta keywords based on the content
     *
     * @param string $content Content from which we have to create metakeywords
     * @return string The list of meta keywords
     */
    public static function createMetaKeywords($content)
    {
        $keywordscount = 50;
        $keywordsorder = 0;

        $tmp = [];
        // Search for the "Minimum keyword length"
        if (\Xmf\Request::hasVar('birthday_keywords_limit', 'SESSION')) {
            $limit = $_SESSION['birthday_keywords_limit'];
        } else {
            $configHandler                       = \xoops_getHandler('config');
            $xoopsConfigSearch                   = $configHandler->getConfigsByCat(\XOOPS_CONF_SEARCH);
            $limit                               = $xoopsConfigSearch['keyword_min'];
            $_SESSION['birthday_keywords_limit'] = $limit;
        }
        $myts            = \MyTextSanitizer::getInstance();
        $content         = \str_replace('<br>', ' ', $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = \strip_tags($content);
        $content         = mb_strtolower($content);
        $search_pattern  = [
            '&nbsp;',
            "\t",
            "\r\n",
            "\r",
            "\n",
            ',',
            '.',
            '\'',
            ';',
            ':',
            ')',
            '(',
            '"',
            '?',
            '!',
            '{',
            '}',
            '[',
            ']',
            '<',
            '>',
            '/',
            '+',
            '-',
            '_',
            '\\',
            '*',
        ];
        $replace_pattern = [
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        $content         = \str_replace($search_pattern, $replace_pattern, $content);
        $keywords        = \explode(' ', $content);
        switch ($keywordsorder) {
            case 0:    // Ordre d'apparition dans le texte
                $keywords = \array_unique($keywords);
                break;
            case 1:    // Ordre de fr�quence des mots
                $keywords = \array_count_values($keywords);
                \asort($keywords);
                $keywords = \array_keys($keywords);
                break;
            case 2:    // Ordre inverse de la fr�quence des mots
                $keywords = \array_count_values($keywords);
                \arsort($keywords);
                $keywords = \array_keys($keywords);
                break;
        }
        // Remove black listed words
        if ('' != \xoops_trim(self::getModuleOption('metagen_blacklist'))) {
            $metagen_blacklist = \str_replace("\r", '', self::getModuleOption('metagen_blacklist'));
            $metablack         = \explode("\n", $metagen_blacklist);
            \array_walk($metablack, '\trim');
            $keywords = \array_diff($keywords, $metablack);
        }

        foreach ($keywords as $keyword) {
            if (!\is_numeric($keyword) && mb_strlen($keyword) >= $limit) {
                $tmp[] = $keyword;
            }
        }
        $tmp = \array_slice($tmp, 0, $keywordscount);
        if (\count($tmp) > 0) {
            return \implode(',', $tmp);
        }
        if (!isset($configHandler) || !\is_object($configHandler)) {
            $configHandler = \xoops_getHandler('config');
        }
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(\XOOPS_CONF_METAFOOTER);
        if (isset($xoopsConfigMetaFooter['meta_keywords'])) {
            return $xoopsConfigMetaFooter['meta_keywords'];
        }

        return '';
    }

    /**
     * Fonction charg�e de g�rer l'upload
     *
     * @param int    $indice L'indice du fichier � t�l�charger
     * @param string $dstpath
     * @param null   $mimeTypes
     * @param null   $uploadMaxSize
     * @return mixed   True si l'upload s'est bien d�roul� sinon le message d'erreur correspondant
     */
    public static function uploadFile($indice, $dstpath = XOOPS_UPLOAD_PATH, $mimeTypes = null, $uploadMaxSize = null)
    {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        global $destname;
        if (\Xmf\Request::hasVar('xoops_upload_file', 'POST')) {
            require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            $fldname = '';
            $fldname = $_FILES[$_POST['xoops_upload_file'][$indice]];
            $fldname = @\get_magic_quotes_gpc() ? \stripslashes($fldname['name']) : $fldname['name'];
            if (\xoops_trim('' != $fldname)) {
                $destname = self::createUploadName($dstpath, $fldname, true);
                if (null === $mimeTypes) {
                    $permittedtypes = \explode("\n", \str_replace("\r", '', self::getModuleOption('mimetypes')));
                    \array_walk($permittedtypes, '\trim');
                } else {
                    $permittedtypes = $mimeTypes;
                }
                $uploadSize = $uploadMaxSize;
                if (null === $uploadMaxSize) {
                    $uploadSize = self::getModuleOption('maxuploadsize');
                }
                $uploader = new \XoopsMediaUploader($dstpath, $permittedtypes, $uploadSize);
                //$uploader->allowUnknownTypes = true;
                $uploader->setTargetFileName($destname);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][$indice])) {
                    if ($uploader->upload()) {
                        return true;
                    }

                    return _ERRORS . ' ' . \htmlentities($uploader->getErrors(), \ENT_QUOTES | \ENT_HTML5);
                }

                return \htmlentities($uploader->getErrors(), \ENT_QUOTES | \ENT_HTML5);
            }

            return false;
        }

        return false;
    }

    /**
     * Resize a Picture to some given dimensions
     *
     * @param string $src_path     Picture's source
     * @param string $dst_path     Picture's destination
     * @param int    $param_width  Maximum picture's width
     * @param int    $param_height Maximum picture's height
     *
     * @param bool   $keep_original
     * @return int
     * @author GIJOE
     *
     */
    public static function resizePicture($src_path, $dst_path, $param_width, $param_height, $keep_original = false)
    {
        if (!\is_readable($src_path)) {
            return 0;
        }

        [$width, $height, $type] = \getimagesize($src_path);
        switch ($type) {
            case 1: // GIF
                if (!$keep_original) {
                    @\rename($src_path, $dst_path);
                } else {
                    @\copy($src_path, $dst_path);
                }

                return 2;
            case 2: // JPEG
                $src_img = \imagecreatefromjpeg($src_path);
                break;
            case 3: // PNG
                $src_img = \imagecreatefrompng($src_path);
                break;
            default:
                if (!$keep_original) {
                    @\rename($src_path, $dst_path);
                } else {
                    @\copy($src_path, $dst_path);
                }

                return 2;
        }

        if ($param_width > 0 && $param_height > 0) {
            if ($width > $param_width || $height > $param_height) {
                if ($width / $param_width > $height / $param_height) {
                    $new_w = $param_width;
                    $scale = $width / $new_w;
                    $new_h = (int)\round($height / $scale);
                } else {
                    $new_h = $param_height;
                    $scale = $height / $new_h;
                    $new_w = (int)\round($width / $scale);
                }
                $dst_img = \imagecreatetruecolor($new_w, $new_h);
                \imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
            }
        }

        if (isset($dst_img) && \is_resource($dst_img)) {
            switch ($type) {
                case 2: // JPEG
                    \imagejpeg($dst_img, $dst_path);
                    \imagedestroy($dst_img);
                    break;
                case 3: // PNG
                    \imagepng($dst_img, $dst_path);
                    \imagedestroy($dst_img);
                    break;
            }
        }

        \imagedestroy($src_img);
        if (!\is_readable($dst_path)) {
            if (!$keep_original) {
                @\rename($src_path, $dst_path);
            } else {
                @\copy($src_path, $dst_path);
            }

            return 3;
        }
        if (!$keep_original) {
            @\unlink($src_path);
        }

        return 1;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function close_tags($string)
    {
        // match opened tags
        if (\preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
            $start_tags = $start_tags[1];

            // match closed tags
            if (\preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
                $complete_tags = [];
                $end_tags      = $end_tags[1];

                foreach ($start_tags as $key => $val) {
                    $posb = \array_search($val, $end_tags, true);
                    if (\is_int($posb)) {
                        unset($end_tags[$posb]);
                    } else {
                        $complete_tags[] = $val;
                    }
                }
            } else {
                $complete_tags = $start_tags;
            }

            $complete_tags = \array_reverse($complete_tags);
            for ($i = 0, $iMax = \count($complete_tags); $i < $iMax; ++$i) {
                $string .= '</' . $complete_tags[$i] . '>';
            }
        }

        return $string;
    }

    /**
     * @param        $string
     * @param int    $length
     * @param string $etc
     * @param bool   $break_words
     *
     * @return mixed|string
     */
    public function truncate_tagsafe($string, $length = 80, $etc = '...', $break_words = false)
    {
        if (0 == $length) {
            return '';
        }

        if (mb_strlen($string) > $length) {
            $length -= mb_strlen($etc);
            if (!$break_words) {
                $string = \preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length + 1));
                $string = \preg_replace('/<[^>]*$/', '', $string);
                $string = self::close_tags($string);
            }

            return $string . $etc;
        }

        return $string;
    }

    /**
     * Create an infotip
     * @param $text
     * @return string
     */
    public function makeInfotips($text)
    {
        $infotips = self::getModuleOption('infotips');
        if ($infotips > 0) {
            $myts = \MyTextSanitizer::getInstance();

            return htmlspecialchars(\xoops_substr(\strip_tags($text), 0, $infotips));
        }

        return '';
    }

    /**
     * Retourne un breadcrumb en fonction des param�tres pass�s et en partant (d'office) de la racine du module
     *
     * @param array  $path  Le chemin complet (except� la racine) du breadcrumb sous la forme cl�=url valeur=titre
     * @param string $raquo Le s�parateur par d�faut � utiliser
     * @return string le breadcrumb
     */
    public static function breadcrumb($path, $raquo = ' &raquo; ')
    {
        $breadcrumb        = '';
        $workingBreadcrumb = [];
        if (\is_array($path)) {
            $moduleName          = self::getModuleName();
            $workingBreadcrumb[] = "<a href='" . BIRTHDAY_URL . '\' title=\'' . self::makeHrefTitle($moduleName) . '\'>' . $moduleName . '</a>';
            foreach ($path as $url => $title) {
                $workingBreadcrumb[] = "<a href='" . $url . '\'>' . $title . '</a>';
            }
            $cnt = \count($workingBreadcrumb);
            for ($i = 0; $i < $cnt; ++$i) {
                if ($i == $cnt - 1) {
                    $workingBreadcrumb[$i] = \strip_tags($workingBreadcrumb[$i]);
                }
            }
            $breadcrumb = \implode($raquo, $workingBreadcrumb);
        }

        return $breadcrumb;
    }

    /**
     * @param \Xmf\Module\Helper $helper
     * @param array|null         $options
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
    public static function getEditor($helper = null, $options = null)
    {
        /** @var Birthday\Helper $helper */
        if (null === $options) {
            $options           = [];
            $options['name']   = 'Editor';
            $options['value']  = 'Editor';
            $options['rows']   = 10;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '400px';
        }

        if (null === $helper) {
            $helper = Helper::getInstance();
        }

        $isAdmin = $helper->isUserAdmin();

        if (\class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor(\ucfirst($options['name']), $helper->getConfig('editorAdmin'), $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor(\ucfirst($options['name']), $helper->getConfig('editorUser'), $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(\ucfirst($options['name']), $options['name'], $options['value'], '100%', '100%');
        }

        //        $form->addElement($descEditor);

        return $descEditor;
    }
}
