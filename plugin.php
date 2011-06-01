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
define('BAGIT_PLUGIN_VERSION', get_plugin_ini('BagIt', 'version'));
// }}}


// {{{ hooks
add_plugin_hook('install', 'bagitInstall');
add_plugin_hook('uninstall', 'bagitUninstall');
add_plugin_hook('define_acl', 'bagitDefineAcl');
add_plugin_hook('admin_theme_header', 'bagitAdminThemeHeader');
// }}}

// {{{ filters
add_filter('admin_navigation_main', 'bagitAdminNavigationMain');
// }}}


/**
 * Install the plugin. Set option 'bagit_version" in the _options table
 *
 * @return void
 */
function bagitInstall()
{
    set_option('bagit_version', BAGIT_PLUGIN_VERSION);
}

/**
 * Uninstall the plugin. delete option 'bagit_version" in the _options
 * table.
 *
 * @return void
 */
function bagitUninstall()
{
    delete_option('bagit_version');
}

/**
 * Define the access control list, instantiate controller resources.
 *
 * @param object $acl Access control list passed in by the 'define_acl'
 * hook callback.
 *
 * @return void
 */
function bagitDefineAcl($acl)
{

    if (version_compare(OMEKA_VERSION, '2.0-dev', '<')) {

        $resource = new Omeka_Acl_Resource('BagIt_Index');
        $resource->add(array('index')); // Add all controller actions here (?)

    } else {

        $resource = new Zend_Acl_Resource('BagIt_Index');

    }

    $acl->add($resource);
    $acl->allow('super', 'BagIt_Index');
    $acl->allow('admin', 'BagIt_Index');

}

/**
 * Add custom css to the administrative interface.
 *
 * @param object $request Page request passed in by the 'admin_theme_header'
 * hook callback.
 *
 * @return void
 */
function bagitAdminThemeHeader($request)
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
function bagitAdminNavigationMain($nav)
{

    if (has_permission('BagIt_Index', 'index')) {
        $nav['BagIt'] = uri('bag-it');
    }

    return $nav;

}


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
