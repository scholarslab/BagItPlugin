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

class BagItPlugin
{

    private static $_hooks = array(
        'install',
        'uninstall',
        // 'define_acl',
        'define_routes',
        'admin_theme_header'
    );

    private static $_filters = array(
        'admin_navigation_main'
    );

    private $_db;

    /**
     * Delete option 'bagit_version' in the _options, drop tables.
     * table.
     *
     * @return void
     */
    public function __construct()
    {

        $this->_db = get_db();
        self::addHooksAndFilters();

    }

    /**
     * Iterate over hooks and filters, define callbacks.
     *
     * @return void
     */
    public function addHooksAndFilters()
    {

        foreach (self::$_hooks as $hookName) {
            $functionName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $functionName));
        }

        foreach (self::$_filters as $filterName) {
            $functionName = Inflector::variablize($filterName);
            add_filter($filterName, array($this, $functionName));
        }

    }

    /**
     * Create tables for file collections and file associations.
     *
     * @return void
     */
    public function install()
    {

        $this->_db->query("
            CREATE TABLE IF NOT EXISTS `$this->_db->BagitFileCollection` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT primary key,
                `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
                `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX(name(60))
            ) ENGINE = innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ");

        $this->_db->query("
            CREATE TABLE IF NOT EXISTS `$this->_db->BagitFileCollectionAssociation` (
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
    public function uninstall()
    {

        $this->_db->query("DROP TABLE IF EXISTS `$this->_db->BagitFileCollection`");
        $this->_db->query("DROP TABLE IF EXISTS `$this->_db->BagitFileCollectionAssociation`");

    }

    /**
     * Wire up the routes in routes.ini.
     *
     * @param object $router Router passed in by the front controller
     *
     * @return void
     */
    public function defineRoutes($router)
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
    public function adminThemeHeader($request)
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
    public function adminNavigationMain($nav)
    {

        $nav['BagIt'] = uri('bag-it');
        return $nav;

    }

}
