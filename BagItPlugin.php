<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Initialization class.
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

// {{{ requires
require_once BAGIT_PLUGIN_DIRECTORY . '/BagItPlugin.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/helpers/BagItFunctions.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/lib/BagItPHP/lib/bagit.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/models/BagitFileCollection.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/models/BagitFileCollectionAssociation.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/models/Table/Table_BagitFileCollection.php';
require_once BAGIT_PLUGIN_DIRECTORY . '/models/Table/Table_BagitFileCollectionAssociation.php';
// }}}

function err($msg) {
    error_log("$msg\n", 3, "/tmp/bagit.log");
}

class BagItPlugin extends Omeka_Plugin_AbstractPlugin
{

    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'define_routes',
        'admin_head'
    );

    protected $_filters = array(
        'admin_navigation_main'
    );

    /**
     * Create tables for file collections and file associations.
     *
     * @return void
     */
    public function hookInstall() {
        $db = $this->_db;

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
    public function hookUninstall() {
        $db = $this->_db;

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
    public function hookDefineAcl($args) {
        $acl = $args['acl'];
        if (! $acl->has('Bagit_Collections')) {
            $acl->addResource('Bagit_Collections');
        }
        $acl->allow(array('super', 'admin'), 'Bagit_Collections');
    }

    /**
     * Wire up the routes in routes.ini.
     *
     * @param object $router Router passed in by the front controller.
     *
     * @return void
     */
    public function hookDefineRoutes($args) {
        $router = $args['router'];
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
    public function hookAdminHead($args) {
        $fc     = Zend_Registry::get('bootstrap')->getResource('frontcontroller');
        $req    = $fc->getRequest();
        $module = $req->getModuleName();

        if ($module == 'bag-it') {
            queue_css_file('bagit-interface');
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
    public function filterAdminNavigationMain($tabs) {
        $tabs[] = array(
            'label' => 'BagIt',
            'uri'   => url('bag-it/collections')
        );

        return $tabs;
    }
}
