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

class BagIt_CollectionsController extends Omeka_Controller_Action
{

    /**
     * Set shortcut for the models.
     *
     * @return void
     */
    public function init()
    {

        $this->_modelFile = $this->getTable('File');
        $this->_modelBagitFileCollection = $this->getTable('BagitFileCollection');
        $this->_modelBagitFileCollectionAssociation = $this->getTable('BagitFileCollectionAssociation');

    }

    /**
     * By default, redirect index requests to the browse action.
     *
     * @return void
     */
    public function indexAction()
    {

        $this->_forward('browse', 'collections', 'bag-it');

    }

    /**
     * Show collections and form to add new collection.
     *
     * @return void
     */
    public function browseAction()
    {

        // If form is posted, add new collection.
        if ($this->getRequest()->isPost()) {

            $collection_name = $this->getRequest()->getParam('collection_name');

            if (!$this->_modelBagitFileCollection->confirmUniqueName($collection_name)) {

                $collection = new BagitFileCollection;
                $collection->name = $collection_name;
                $collection->save();

            } else {

                $this->flashError('A collection already exists with that name.');

            }

        }

        // Process the sorting parameters.
        $order = $this->_doColumnSortProcessing($this->getRequest());

        // Query for collections, tacking on extra column with the number of associated files.
        $db = get_db();
        $collections = $this->_modelBagitFileCollection->fetchObjects(
            $this->_modelBagitFileCollection->select()
            ->from(array('fc' => $db->prefix . 'bagit_file_collections'))
            ->columns(array('id', 'name', 'updated', 'number_of_files' =>
                "(SELECT COUNT(collection_id) from `$db->BagitFileCollectionAssociation` WHERE collection_id = fc.id)"))
            ->order($order)
        );

        $this->view->form = $form;
        $this->view->collections = $collections;

    }

    /**
     * Show collections and the form to add new collection.
     *
     * @return void
     */
    public function browsecollectionAction()
    {

        $collection_id = $this->getRequest()->getParam('id');
        $collection = $this->_modelBagitFileCollection->fetchObject(
            $this->_modelBagitFileCollection->getSelect()->where('id = ?', $collection_id)
        );

        if ($this->_request->getPost('browsecollection_submit') == 'Create Bag') {
            $this->_redirect('bag-it/collections/' . $collection_id . '/export');
            exit();
        }

        // If the list of associations was updated, check to see if files were
        // checked for deletion and delete them.
        if ($this->getRequest()->isPost()) {

            $files = $this->getRequest()->getParam('file');

            foreach ($files as $id => $value) {

                if ($value == 'remove') {

                    $assoc = $this->_modelBagitFileCollectionAssociation->fetchObject(
                        $this->_modelBagitFileCollectionAssociation->getSelect()
                            ->where('file_id = ' . $id . ' AND collection_id = ' . $collection_id)
                    );

                    $assoc->delete();

                }

            }

        }

        // Get paging information for the pagination function in the view,
        // process column sorting.
        $page = $this->getRequest()->getParam('page');
        $order = $this->_doColumnSortProcessing($this->getRequest());

        // Get files, left joining on the file-collection association table and
        // adding a column with the name of the parent item from the _element_texts table.
        $db = get_db();
        $files = $this->_modelFile->fetchObjects(
            $this->_modelFile->select()
            ->from(array('f' => $db->prefix . 'files'))
            ->joinLeft(array('a' => $db->prefix . 'bagit_file_collection_associations'), 'f.id = a.file_id')
            ->columns(array('size', 'type' => 'type_os', 'id' => 'f.id', 'name' => 'original_filename', 'parent_item' =>
                "(SELECT text from `$db->ElementText` WHERE record_id = f.item_id AND element_id = 50)"))
            ->where('a.collection_id = ' . $collection_id)
            ->limitPage($page, 10)
            ->order($order)
        );

        $this->view->collection = $collection;
        $this->view->files = $files;
        $this->view->current_page = $page;
        $this->view->total_results = count($files);
        $this->view->results_per_page = 10;

    }

    /**
     * Show list of files and controls for adding files to the selected collection.
     *
     * @return void
     */
    public function addfilesAction()
    {

        $collection_id = $this->getRequest()->getParam('id');
        $collection = $this->_modelBagitFileCollection->fetchObject(
            $this->_modelBagitFileCollection->getSelect()->where('id = ?', $collection_id)
        );

        // Check for form submission, iterate over files and add/remove.
        if ($this->getRequest()->isPost()) {

            $files = $this->getRequest()->getParam('file');

            foreach ($files as $id => $value) {

                if ($value == 'add' && !$collection->checkForFileMembership($id)) {

                    $assoc = new BagitFileCollectionAssociation;
                    $assoc->collection_id = $collection_id;
                    $assoc->file_id = $id;
                    $assoc->save();

                }

                if ($value == 'remove') {

                    $assoc = $this->_modelBagitFileCollectionAssociation->fetchObject(
                        $this->_modelBagitFileCollectionAssociation->getSelect()->where('file_id = ' . $id . ' AND collection_id = ' . $collection_id)
                    );

                    $assoc->delete();

                }

            }

        }

        // Get paging information for the pagination function in the view,
        // process column sorting.
        $page = $this->getRequest()->getParam('page');
        $order = $this->_doColumnSortProcessing($this->getRequest());

        // Get files with parent item name.
        $db = get_db();
        $files = $this->_modelFile->fetchObjects(
            $this->_modelFile->select()
            ->from(array('f' => $db->prefix . 'files'))
            ->columns(array('size', 'type' => 'type_os', 'name' => 'original_filename', 'parent_item' =>
                "(SELECT text from `$db->ElementText` WHERE record_id = f.item_id AND element_id = 50)"))
            ->limitPage($page, 10)
            ->order($order)
        );

        // Get total files for pagination rendering (not constrained by page length limit).
        // Is there a good way to do this without running two queries here?
        $total_files = count($this->_modelFile->fetchObjects(
            $this->_modelFile->getSelect()
        ));

        $this->view->collection = $collection;
        $this->view->files = $files;
        $this->view->current_page = $page;
        $this->view->total_results = $total_files;
        $this->view->results_per_page = 10;

    }


    /**
     * Show collections and form to add new collection.
     *
     * @return void
     */
    public function deletecollectionAction()
    {

        $collection_id = $this->getRequest()->getParam('id');
        $collection = $this->_modelBagitFileCollection->fetchObject(
            $this->_modelBagitFileCollection->getSelect()->where('id = ?', $collection_id)
        );

        // If delete confirmed, go delete.
        if ($this->getRequest()->getParam('confirm') == 'true') {

            $file_associations = $this->_modelBagitFileCollectionAssociation->fetchObjects(
                $this->_modelBagitFileCollectionAssociation->getSelect()->where('collection_id = ?', $collection_id)
            );

            $collection->delete();
            foreach ($file_associations as $assoc) {
                $assoc->delete();
            }

            $this->flashError('Collection "' . $collection->name . '" deleted.');
            return $this->redirect->goto('browse');

        } else {

            $this->view->collection = $collection;

        }

    }

    /**
     * Prepare the export.
     *
     * @return void
     */
    public function exportprepAction()
    {

        $form = $this->_doExportForm();

        // Getters.
        $collection_id = $this->getRequest()->getParam('id');
        $collection = $this->_modelBagitFileCollection->fetchObject(
            $this->_modelBagitFileCollection->getSelect()->where('id = ?', $collection_id)
        );

        

    }

    /**
     * Process the final submission.
     *
     * @return void
     */
    public function exportAction()
    {

        // Getters.
        $collection_id = $this->getRequest()->getParam('id');
        $collection = $this->_modelBagitFileCollection->fetchObject(
            $this->_modelBagitFileCollection->getSelect()->where('id = ?', $collection_id)
        );

        // Run the bagit function.
        $this->view->success = $this->_doBagIt($collection_id, $collection->name);
        $this->view->bag_name = $collection->name;

    }

    /**
     * Show the upload form.
     *
     * @return void
     */
    public function importAction() {

        if ($this->getRequest()->isPost()) {

            $form = $this->_doUploadForm();
            $posted_form = $this->_request->getPost();

            // Validate the file.
            if ($form->isValid($posted_form)) {

                $original_filename = pathinfo($form->bag->getFileName());
                $new_filename = $original_filename['basename'];
                $form->bag->addFilter('Rename', $new_filename);

                $form->bag->receive();

                if ($this->_doReadBagIt($new_filename)) {
                    $this->_redirect('dropbox');
                    exit();
                } else {
                    $this->flashError('Error unpacking the files.');
                }

            } else {

                $this->flashError('Validation failed or no file selected. Make sure the file is a .tgz.');

            }

        }

        $form = $this->_doUploadForm();
        $this->view->form = $form;


    }

    /**
     * Build the upload form.
     *
     * @param string $tmp The location of the temporary directory
     * where the tar files should be stored.
     *
     * @return object $form The upload form.
     */
    protected function _doUploadForm($tmp = BAGIT_TMP_DIRECTORY) {

        $form = new Zend_Form();
        $form->setAction('import')
            ->setMethod('post');

        $uploader = new Zend_Form_Element_File('bag');
        $uploader->setLabel('Select the Bag file:')
            ->setDestination($tmp)
            ->addValidator('count', false, 1)
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('bag_submit');
        $submit->setLabel('Upload');

        $form->addElement($uploader);
        $form->addElement($submit);

        return $form;

      }

    /**
     * Build the export form.
     *
     * @param string $tmp The location of the temporary directory
     * where the tar files should be stored.
     *
     * @return object $form The upload form.
     */
    protected function _doExportForm() {

        $form = new Zend_Form();
        $form->setAction('export')
            ->setMethod('post');

        $format = new Zend_Form_Element_Radio('format');

        $name_override = new Zend_Form_Element_Text('name_override');
        $name_override->setLabel('Name:');

        $submit = new Zend_Form_Element_Submit('export_submit');
        $submit->setLabel('Upload');

        $form->addElement($name_override);
        $form->addElement($submit);

        return $form;

      }

    /**
     * Create the bag, generate tar.
     *
     * @param array $file_ids Array of ids, posted from the form.
     * @param string $name The name of the bag.
     *
     * @return boolean $success True if the new bag validates.
     */
    protected function _doBagIt($collection_id, $collection_name)
    {

        // Instantiate the bag.
        $bag = new BagIt(BAGIT_BAG_DIRECTORY . DIRECTORY_SEPARATOR . $collection_name);

        // Get the files associated with the collection.
        $db = get_db();
        $files = $this->_modelFile->fetchObjects(
            $this->_modelFile->select()
            ->from(array('f' => $db->prefix . 'files'))
            ->joinLeft(array('a' => $db->prefix . 'bagit_file_collection_associations'), 'f.id = a.file_id')
            ->columns(array('size', 'type' => 'type_os', 'id' => 'f.id', 'archive_filename', 'original_filename', 'parent_item' =>
                "(SELECT text from `$db->ElementText` WHERE record_id = f.item_id AND element_id = 50)"))
                ->where('a.collection_id = ' . $collection_id)
        );

        // Retrieve the files and add them to the new bag.
        foreach ($files as $file) {

            $bag->addFile('..' . DIRECTORY_SEPARATOR . OMEKA_FILES_RELATIVE_DIRECTORY .
                DIRECTORY_SEPARATOR .  $file->archive_filename, $file->original_filename
            );

        }

        // Update the hashes.
        $bag->update();

        // Tar it up.
        $bag->package(BAGIT_BAG_DIRECTORY . DIRECTORY_SEPARATOR . $collection_name, 'zip');

        // Why are the bags not validating?
        return true;
        // return $bag->isValid() ? true : false;

    }

    /**
     * Read the Bag, unpack it, drop files into the Dropbox files directory
     *
     * @param string $filename The name of the uploaded bag.
     *
     * @return boolean $success True if the read succeeds.
     */
    protected function _doReadBagIt($filename)
    {

        $success = false;

        $bag = new BagIt(BAGIT_TMP_DIRECTORY . DIRECTORY_SEPARATOR . $filename);
        $bag->validate();

        if (count($bag->getBagErrors()) == 0) {

            $bag->fetch->download();

            // Copy each of the files.
            foreach ($bag->getBagContents() as $file) {
                copy($file, '..' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR .
                    'Dropbox' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . basename($file));
            }

            $success = true;
        }

        return $success;

    }

    /**
     * A homebrew colum sorter, implemented so as to keep more control 
     * over how the record loop is handled in the view.
     *
     * @param object $request The incoming request dispatched by the 
     * front controller.
     *
     * @return string $order The sorting parameter for the query.
     */
    protected function _doColumnSortProcessing($request)
    {

        $sort_field = $request->getParam('sort_field');
        $sort_dir = $request->getParam('sort_dir');

        if (isset($sort_dir)) {
            $sort_dir = ($sort_dir == 'a') ? 'ASC' : 'DESC';
        }

        return (isset($sort_field)) ? trim(implode(' ', array($sort_field, $sort_dir))) : '';

    }

}
