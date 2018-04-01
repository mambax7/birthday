<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);

// ------------------- Informations ------------------- //
$modversion = [
    'version'             => 2.4,
    'module_status'       => 'Beta 3',
    'release_date'        => '2017/12/26',
    'name'                => _MI_BD_TITRE,
    'description'         => _MI_BD_DESC,
    'official'            => 0,
    //1 indicates official XOOPS module supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'Hervé Thouzard',
    'credits'             => 'XOOPS Development Team',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    // ------------------- Folders & Files -------------------
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog.txt",
    //
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    // images
    'image'               => 'assets/images/logoModule.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    //Frameworks
    //    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    //    'sysicons16'          => 'Frameworks/moduleclasses/icons/16',
    //    'sysicons32'          => 'Frameworks/moduleclasses/icons/32',
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb/viewforum.php?forum=28/',
    'support_name'        => 'Support Forum',
    'submit_bug'          => 'https://github.com/XoopsModules25x/' . $moduleDirName . '/issues',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // ------------------- Min Requirements -------------------
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.9',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    // ------------------- Admin Menu -------------------
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Main Menu -------------------
    'hasMain'             => 1,
    'sub'                 => [
        [
            'name' => _MI_BIRTHDAY_USERS_LIST,
            'url'  => 'users.php'
        ],
    ],

    // ------------------- Install/Update -------------------
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    'onUninstall'         => 'include/onuninstall.php',
    // -------------------  PayPal ---------------------------
    'paypal'              => [
        'business'      => 'foundation@xoops.org',
        'item_name'     => 'Donation : ' . _MI_BD_TITRE,
        'amount'        => 0,
        'currency_code' => 'USD'
    ],
    // ------------------- Search ---------------------------
    'hasSearch'           => 1,
    'search'              => [
        'file' => 'include/search.inc.php',
        'func' => 'birthday_search'
    ],
    // ------------------- Comments -------------------------
    'hasComments'         => 1,
    'comments'            => [
        'pageName'     => 'user.php',
        'itemName'     => 'birthday_id',
        'callbackFile' => 'include/comment_functions.php',
        'callback'     => [
            'approve' => 'birthday_com_approve',
            'update'  => 'birthday_com_update'
        ],
    ],
    // ------------------- Mysql -----------------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
    'tables'              => [
        //        $moduleDirName . '_' . 'XXX',
        'users_birthday'

    ],
];

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_BIRTHDAY_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_BIRTHDAY_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_BIRTHDAY_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_BIRTHDAY_SUPPORT, 'link' => 'page=support'],
];

// ------------------- Blocks ------------------- //
$modversion['blocks'][] = [
    'file'        => 'b_birthday.php',
    'name'        => _MI_BD_TITRE,
    'description' => '_MI_BD_DESC',
    'show_func'   => 'b_birthday_show',
    'edit_func'   => 'b_birthday_edit',
    'options'     => '5', // nombre d'éléments visibles
    'template'    => 'birthday_block_birthday.tpl',
];

// ------------------- Templates ------------------- //

$modversion['templates'] = [
    ['file' => 'birthday_index.tpl', 'description' => 'Index page'],
    ['file' => 'birthday_user.tpl', 'description' => 'Display a user page'],
    ['file' => 'birthday_users.tpl', 'description' => 'List of Users'],
];

// ------------------- Preferences Options ------------------- //

/**
 * Images width
 */

$modversion['config'][] = [
    'name'        => 'images_width',
    'title'       => '_MI_BIRTHDAY_IMAGES_WIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 150,
];
/**
 * Images height
 */

$modversion['config'][] = [
    'name'        => 'images_height',
    'title'       => '_MI_BIRTHDAY_IMAGES_HEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 150,
];
/**
 * Folder's path (where to save pictures)
 */

$modversion['config'][] = [
    'name'        => 'folder_path',
    'title'       => '_MI_BIRTHDAY_FOLDER_PATH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
];
/**
 * Folder's url (where to save pictures)
 */

$modversion['config'][] = [
    'name'        => 'folder_url',
    'title'       => '_MI_BIRTHDAY_FOLDER_URL',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_UPLOAD_URL . '/' . $moduleDirName . '/images',
];
/**
 * Items count per page
 */
$modversion['config'][] = [
    'name'        => 'perpage',
    'title'       => '_MI_BIRTHDAY_PERPAGE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 15,
];
/**
 * Mime Types
 * Default values : Web pictures (png, jpeg)
 */
//$modversion['config'][] = [
//    'name'        => 'mimetypes',
//    'title'       => '_MI_BIRTHDAY_MIMETYPES',
//    'description' => '',
//    'formtype'    => 'textarea',
//    'valuetype'   => 'text',
//    'default'     => "image/jpeg\nimage/pjpeg\nimage/x-png\nimage/png",
//];

//Uploads : mimetypes of images
$modversion['config'][] = [
    'name'        => 'mimetypes',
    'title'       => '_MI_BIRTHDAY_MIMETYPES',
    'description' => '',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'],
    'options'     => [
        'bmp'   => 'image/bmp',
        'gif'   => 'image/gif',
        'pjpeg' => 'image/pjpeg',
        'jpeg'  => 'image/jpeg',
        'jpg'   => 'image/jpg',
        'jpe'   => 'image/jpe',
        'png'   => 'image/png'
    ]
];

/**
 * MAX Filesize Upload in kilo bytes
 */
$modversion['config'][] = [
    'name'        => 'maxuploadsize',
    'title'       => '_MI_BIRTHDAY_UPLOADFILESIZE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1048576,
];
/**
 * Editor to use
 */

xoops_load('XoopsEditorHandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'editorAdmin',
    'title'       => 'MI_BIRTHDAY_EDITOR_ADMIN',
    'description' => 'MI_BIRTHDAY_EDITOR_DESC_ADMIN',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'tinymce'
];

$modversion['config'][] = [
    'name'        => 'editorUser',
    'title'       => 'MI_BIRTHDAY_EDITOR_USER',
    'description' => 'MI_BIRTHDAY_EDITOR_DESC_USER',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'dhtmltextarea'
];

/**
 * Enable users of your site to fill their form ?
 */
$modversion['config'][] = [
    'name'        => 'enable_users',
    'title'       => '_MI_BIRTHDAY_ENABLE_USERS',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
/**
 * Sort order
 */
$modversion['config'][] = [
    'name'        => 'userslist_sortorder',
    'title'       => '_MI_BIRTHDAY_SORT_ORDER',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        _MI_BIRTHDAY_SORT_ORDER1 => 1,
        _MI_BIRTHDAY_SORT_ORDER2 => 2
    ],
    'default'     => 2,
];
/**
 * Activate CAPTCHA ?
 */
$modversion['config'][] = [
    'name'        => 'use_captcha',
    'title'       => '_MI_BIRTHDAY_USE_CAPTCHA',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];


/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => '_MI_BIRTHDAY_SHOW_SAMPLE_BUTTON',
    'description' => '_MI_BIRTHDAY_SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
