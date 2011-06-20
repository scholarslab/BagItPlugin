<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * BagIt plugin for Omeka implements the BagIt 0.96 specification. Allows users
 * to (a) create Bags containing files on the site and (b) import Bags and access
 * the files through the Dropbox interface.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package omeka
 * @subpackage BagIt
 * @author Scholars' Lab
 * @author David McClure (david.mcclure@virginia.edu)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 * PHP version 5
 *
 */
?>

<?php

// {{{ constants
define('BAGIT_PLUGIN_DIRECTORY', dirname(__FILE__));
define('BAGIT_BAG_DIRECTORY', dirname(__FILE__) . '/bags');
define('OMEKA_FILES_RELATIVE_DIRECTORY', 'archive/files');
define('BAGIT_TMP_DIRECTORY', BAGIT_PLUGIN_DIRECTORY . '/bagtmp');
define('BAGIT_TESTS_DIRECTORY', BAGIT_PLUGIN_DIRECTORY . '/tests');
// }}}

// {{{ requires
require_once BAGIT_PLUGIN_DIRECTORY . '/helpers/BagItFunctions.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/lib/BagItPHP/lib/bagit.php';
// }}}

// {{{ hooks
add_plugin_hook('install', 'bagit_install');
add_plugin_hook('uninstall', 'bagit_uninstall');
add_plugin_hook('define_acl', 'bagit_defineAcl');
add_plugin_hook('define_routes', 'bagit_defineRoutes');
add_plugin_hook('admin_theme_header', 'bagit_adminThemeHeader');
// }}}

// {{{ filters
add_filter('admin_navigation_main', 'bagit_adminNavigationMain');
// }}}

/**
 * Create tables for file collections and file associations.
 *
 * @return void
 */
function bagit_install()
{

    $db = get_db();

    $db->query("
        CREATE TABLE IF NOT EXISTS `$db->BagitFileCollection` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT primary key,
            `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
            `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX(name(60))
        ) ENGINE = innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
    ");

    $db->query("
        CREATE TABLE IF NOT EXISTS `$db->BagitFileCollectionAssociation` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT primary key,
            `file_id` int(10) unsigned NOT NULL,
            `collection_id` int(10) unsigned NOT NULL
        ) ENGINE = innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
    ");

}

/**
 * Drop tables.
 *
 * @return void
 */
function bagit_uninstall()
{

    $db = get_db();

    $db->query("DROP TABLE IF EXISTS `$db->BagitFileCollection`");
    $db->query("DROP TABLE IF EXISTS `$db->BagitFileCollectionAssociation`");

}

/**
 * Define access privileges.
 *
 * @param $acl The access management object passed in by the front controller.
 *
 * @return void
 */
function bagit_defineAcl($acl)
{

    if (version_compare(OMEKA_VERSION, '2.0-dev', '<')) {
        $indexResource = new Omeka_Acl_Resource('Bagit_Collections');
    } else {
        $indexResource = new Zend_Acl_Resource('Bagit_Collections');
    }

    $acl->add($indexResource);
    $acl->allow('super', 'Bagit_Collections');
    $acl->allow('admin', 'Bagit_Collections');

}

/**
 * Wire up the routes in routes.ini.
 *
 * @param object $router Router passed in by the front controller.
 *
 * @return void
 */
function bagit_defineRoutes($router)
{

    $router->addConfig(new Zend_Config_Ini(BAGIT_PLUGIN_DIRECTORY .
        DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));

}

/**
 * Add custom css.
 *
 * @param object $request Page request passed in by the 'admin_theme_header'
 * hook callback.
 *
 * @return void
 */
function bagit_adminThemeHeader($request)
{

    if ($request->getModuleName() == 'bag-it') {
        queue_css('bagit-interface');
    }

}

/**
 * Add a link to the administrative interface for the plugin.
 *
 * @param array $nav An array of main administrative links passed in
 * by the 'admin_navigation_main' filter callback.
 *
 * @return array $nav The array of links, modified to include the
 * link to the BagIt administrative interface.
 */
function bagit_adminNavigationMain($nav)
{

    if (has_permission('Bagit_Collections', 'browse')) {
        $nav['BagIt'] = uri('bag-it');
    }

    return $nav;

}
