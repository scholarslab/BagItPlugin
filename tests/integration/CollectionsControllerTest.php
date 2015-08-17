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

class BagIt_CollectionsControllerTest extends BagIt_Test_AppTestCase
{

    public function testDetectNoCollections()
    {

        $this->dispatch('bag-it');
        $this->assertQueryContentContains('p', 'There are no collections. Create one!');
        $this->assertEquals(0, $this->db->getTable('BagitFileCollection')->count());

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
        $this->assertEquals(1, $this->db->getTable('BagitFileCollection')->count());

    }

    public function testRejectBlankCollectionName()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => ''
            )
        );

        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('li.error', 'Enter a name for the collection');

    }

    public function testCollectionNameTrim()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
                'collection_name' => '    '
            )
        );

        $this->dispatch('bag-it/collections/addcollection');
        $this->assertQueryContentContains('li.error', 'Enter a name for the collection');

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
        $collections = $this->db->getTable('BagitFileCollection')->findAll();
        $this->assertCount(1, $collections);

        $this->request->setMethod('POST')
            ->setPost(array(
                'confirm' => 'true'
            )
        );

        $this->dispatch("bag-it/collections/{$collections[0]->id}/delete");
        $this->assertEquals(0, $this->db->getTable('BagitFileCollection')->count());

    }

    public function testDetectNoFilesToAdd()
    {

        $collection = $this->_createFileCollection('Test Collection');
        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertQueryContentContains('p', 'There are no files on the site that can be added to a Bag.');

    }

    public function testAddAndRemoveFiles()
    {

        $this->_createItem('Testing Item');
        $this->_createFiles();
        $collection = $this->_createFileCollection('Test Collection');

        $this->request->setMethod('POST')
            ->setPost(array(
                'file' => array(
                    '3' => 'add',
                    '4' => 'add',
                    '5' => 'add'
                )
            )
        );

        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertQueryCount(3, 'input[value="remove"]');

        $this->dispatch("bag-it/collections/{$collection->id}");
        $this->assertQueryContentContains('h2', '"Test Collection" contains 3 files:');

        $this->request->setMethod('POST')
            ->setPost(array(
                'file' => array(
                    '3' => 'remove',
                    '4' => 'remove'
                )
            )
        );

        $this->dispatch("bag-it/collections/{$collection->id}");
        $this->assertQueryContentContains('h2', '"Test Collection" contains 1 files:');

        $this->resetRequest()->resetResponse();

        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertQueryCount(1, 'input[value="remove"]');

        $this->request->setMethod('POST')
            ->setPost(array(
                'file' => array(
                    '5' => 'remove'
                )
            )
        );

        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertQueryCount(0, 'input[value="remove"]');

    }

    public function testAddAndRemoveAllFiles()
    {

        $this->_createItem('Testing Item');
        $this->_createFiles();
        $collection = $this->_createFileCollection('Test Collection');

        $this->request->setMethod('POST')
            ->setPost(array(
                'add_all_files' => 'Add All Files'
                )
            );

        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertEquals(13, count($this->db->getTable('BagitFileCollectionAssociation')
            ->findBySql('collection_id = ?', array($collection->id))));

        $this->resetRequest()->resetResponse();

        $this->request->setMethod('POST')
            ->setPost(array(
                'remove_all_files' => 'Remove All Files'
                )
            );

        $this->dispatch("bag-it/collections/{$collection->id}/add");
        $this->assertEquals(0, count($this->db->getTable('BagitFileCollectionAssociation')
            ->findBySql('collection_id = ?', array($collection->id))));

    }

}
