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
define('BAGIT_BAG_DIRECTORY', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bags');
define('BAGIT_PLUGIN_VERSION', get_plugin_ini('BagIt', 'version'));
define('OMEKA_FILES_RELATIVE_DIRECTORY', 'archive' . DIRECTORY_SEPARATOR . 'files');
define('BAGIT_TMP_DIRECTORY', BAGIT_PLUGIN_DIRECTORY . DIRECTORY_SEPARATOR . 'bagtmp');
define('BAGIT_TESTS_DIRECTORY', BAGIT_PLUGIN_DIRECTORY . DIRECTORY_SEPARATOR . 'tests');
// }}}

// {{{ requires
require_once BAGIT_PLUGIN_DIRECTORY . DIRECTORY_SEPARATOR . 'helpers' .
    DIRECTORY_SEPARATOR . 'BagItFunctions.php'; // Include the helper functions.
require_once BAGIT_PLUGIN_DIRECTORY . DIRECTORY_SEPARATOR . 'lib' .
    DIRECTORY_SEPARATOR . 'bagit.php'; // Include the BagIt library.
// }}}


