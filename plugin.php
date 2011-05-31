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
 * @author David McClure (david.mcclure@virginia.edu)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version 
 *
 * PHP version 5
 *
 */
?>

<?php

// {{{ constants
define('BAGIT_PLUGIN_VERSION', get_plugin_ini('BagIt', 'version'));
// }}}

// {{{ hooks
add_plugin_hook('install', 'bagitInstall');
add_plugin_hook('uninstall', 'bagitUninstall');
// }}}

function bagitInstall()
{
    set_option('bagit_version', BAGIT_PLUGIN_VERSION);
}

function bagitUninstall()
{
    delete_option('bagit_version');
}
