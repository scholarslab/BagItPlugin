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

class BagIt_CollectionsControllerTest extends Omeka_Test_AppTestCase
{

    public function setUp()
    {

        parent::setUp();
        $this->helper = new BagIt_Test_AppTestCase;
        $this->helper->setUpPlugin();

    }

    public function testDetectNoCollections()
    {

        $this->dispatch('bag-it');
        $this->assertQueryContentContains('p', 'There are no collections. Create one!');

    }

    public function testAddCollection()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => 'Testing Collection'
            )
        );
        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('a', 'Testing Collection');

    }

    public function testRejectBlankCollectionName()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => ''
            )
        );
        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('div.error', 'Enter a name for the collection');

    }

    public function testCollectionNameTrim()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => '    '
            )
        );
        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('div.error', 'Enter a name for the collection');

    }

    public function testDeleteCollection()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => 'Testing Collection'
            )
        );
        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('a', 'Testing Collection');

        // $this->resetRequest()->resetResponse();

        $this->request->setMethod('POST')
            ->setPost(array(
                'confirm' => 'true'
            )
        );
        $this->dispatch('bag-it/collections/1/delete');
        $this->assertQueryContentContains('div.error', 'Collection "Testing Collection" deleted.');
        $this->assertQueryContentContains('p', 'There are no collections. Create one!');

    }

    public function testDetectNoFilesToAdd()
    {

        $this->helper->createFileCollection('Test Collection');
        $this->dispatch('bag-it/collections/1/add');
        $this->assertQueryContentContains('p', 'There are no files on the site that can be added to a Bag.');

    }

    public function testAddItem()
    {

        

    }

}
