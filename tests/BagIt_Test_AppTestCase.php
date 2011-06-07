<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Application test case for BagIt plugin.
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

class BagIt_Test_AppTestCase extends Omeka_Test_AppTestCase
{

    const PLUGIN_NAME = 'BagIt';

    /**
     * Initialize helper and broker.
     *
     * @return void
     */
    public function setUp()
    {

        parent::setUp();

        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        $plugin_broker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($plugin_broker, self::PLUGIN_NAME);

        $plugin_helper = new Omeka_Test_Helper_Plugin;
        $plugin_helper->setUp(self::PLUGIN_NAME);

    }



}
