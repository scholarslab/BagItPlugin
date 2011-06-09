<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Interface tests.
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

class BagIt_BagItTest extends Omeka_Test_AppTestCase
{

    public function setUp()
    {

        parent::setUp();
        $this->helper = new BagIt_Test_AppTestCase;
        $this->helper->setUpPlugin();

    }

    public function testCreateBag()
    {

        $this->helper->createItem('Testing Item');
        $this->helper->createFiles();
        $this->helper->createFileCollection('Test Collection');

        $this->request->setMethod('POST')
            ->setPost(array(
                'file' => array(
                    '2' => 'add',
                    '6' => 'add',
                    '7' => 'add'
                )
            )
        );

        $this->dispatch('bag-it/collections/1/add');
        $this->resetRequest()->resetResponse();
        $this->dispatch('bag-it/collections/1/exportprep');
        $this->assertQueryCount(1, 'input[value="TestCollection"]');

        $this->request->setMethod('POST')
            ->setPost(array(
                'format' => 'zip',
                'name_override' => 'TestCollection',
                'collection_id' => '1'
                )
            );

        $this->dispatch('bag-it/collections/1/export');
        $this->assertQueryContentContains('a', 'Click here to download the Bag');

        $testbag = new BagIt(BAGIT_PLUGIN_DIRECTORY . '/bags/TestCollection.zip');
        $this->assertEquals(0, count($testbag->getBagErrors()));

    }

}
