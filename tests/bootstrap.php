<?php

if (!($omekaDir = getenv('OMEKA_DIR'))) {
    $omekaDir = dirname(dirname(dirname(dirname(__FILE__))));
}

require_once $omekaDir . '/application/tests/bootstrap.php';
//require_once $omekaDir . '/plugins/BagItPlugin/BagItPlugin.php';
require_once 'BagIt_Test_AppTestCase.php';