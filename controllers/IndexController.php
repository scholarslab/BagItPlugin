<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Controller class for the BagIt administrative interface.
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

class BagIt_IndexController extends Omeka_Controller_Action
{

    /**
     * Set shortcut for the model.
     *
     * @return void
     */
    public function init()
    {

        $this->_model = $this->getTable('File');

    }


    /**
     * By default, redirect index requests to the browse action.
     *
     * @return void
     */
    public function indexAction()
    {

        $this->redirect->goto('browse');

    }

    /**
     * Push file data into the browse view.
     *
     * @return void
     */
    public function browseAction()
    {

        // Get files, ordered by the original name of the file,
        // (not the serialized name that Omeka assigns).
        $files = $this->_model->fetchObjects(
            $this->_model->getSelect()->order('original_filename')
        );

        $this->view->files = $files;

    }

    /**
     * Display preview of files added from the browse interface.
     *
     * @return void
     */
    public function previewAction()
    {

        if ($this->getRequest()->isPost()) {

            $files = $this->getRequest()->getParam('file');

            // Step through the posted form and figure out which
            // files should be added.
            foreach ($files as $id => $value) {
                if ($value == 'add') {
                    $files_to_add[] = $id;
                }
            }

            if (count($files_to_add) > 0) {

                // Construct the where clause of the SQL.
                $where = implode($files_to_add, ',');

                // Get the files and push them into the view.
                $preview_files = $this->_model->fetchObjects(
                    $this->_model->getSelect()->
                    where('f.id IN (' . $where . ')')
                );

            }

            $this->view->files = $preview_files;

        } else {

            $this->redirect->goto('browse');

        }

    }

    /**
     * Process the final submission.
     *
     * @return void
     */
    public function createAction()
    {

        if ($this->getRequest()->isPost()) {

            $files = $this->getRequest()->getParam('file');
            $bag_name = $this->getRequest()->getParam('bag_name');

            if ($bag_name == '') {
                $this->flashError('Enter a name for the bag.');
                return $this->_forward('preview', 'index', 'bag-it');
            }

            $this->_doBagIt($files);

        } else {

            $this->redirect->goto('browse');

        }

    }

    /**
     * Create the bag, generate tar.
     *
     * @param array $file_ids Array of ids, posted from the form
     *
     * @return void
     */
    protected function _doBagIt($file_ids)
    {

        // Instantiate the bag.
        $bag = new BagIt(BAGIT_BAG_DIRECTORY);

        // Retrieve the files and add them to the new bag.
        foreach ($files as $id) {

            $file = $this->_model->fetchObject(
                $this->_model->getSelect()->
                where('f.id = ' . $id)
            );
            $bag->addFile($this->getRequest()->getBaseUrl() .
                DIRECTORY_SEPARATOR .
                OMEKA_FILES_RELATIVE_DIRECTORY .
                $file->archive_filename,
                $file->original_filename
            );

        }

        // Tar it up.
        $bag->package($bag_name);

    }

}
