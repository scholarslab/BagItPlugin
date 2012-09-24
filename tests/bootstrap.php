<?php

if (!($omekaDir = getenv('OMEKA_DIR'))) {
    $omekaDir = dirname(dirname(dirname(dirname(__FILE__))));
}
if (!defined('BAGIT_PLUGIN_DIRECTORY')) {
    define(
        'BAGIT_PLUGIN_DIRECTORY',
        '..'
    );
}

require_once $omekaDir . '/application/tests/bootstrap.php';
require_once $omekaDir . '/plugins/BagIt/BagItPlugin.php';
require_once 'BagIt_Test_AppTestCase.php';
