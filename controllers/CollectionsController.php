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

        // Process the sorting parameters, get the collections.
        $order = bagithelpers_doColumnSortProcessing($this->_request);
        $collections = $this->getTable('BagitFileCollection')->getCollectionsList();

        $this->view->collections = $collections;

    }

    /**
     * Process new collection submission.
     *
     * @return void
     */
    public function addcollectionAction()
    {

        $collection_name = $this->_request->collection_name;

        if (trim($collection_name) == '') {

            $this->flashError('Enter a name for the collection.');

        } else if ($this->getTable('BagitFileCollection')->confirmUniqueName($collection_name)) {

            $collection = new BagitFileCollection;
            $collection->name = $collection_name;
            $collection->save();

        } else {

            $this->flashError('A collection already exists with that name.');

        }

        $this->_forward('browse', 'collections', 'bag-it');

    }

    /**
     * Show collections and the form to add new collection.
     *
     * @return void
     */
    public function browsecollectionAction()
    {

        $collection_id = $this->_request->id;
        $collection = $this->getTable('BagitFileCollection')->find($collection_id);

        // If the "Create Bag" button was clicked, redirect to the export flow.
        if ($this->_request->browsecollection_submit == 'Create Bag') {
            $this->_redirect('bag-it/collections/' . $collection_id . '/exportprep');
            exit();
        }

        // If the list of associations was updated, check to see if files were
        // checked for deletion and delete them.
        if ($this->_request->isPost()) {
            $this->_addRemoveFilesFromCollection($collection, $this->_request->file);
        }

        // Get paging information for the pagination function in the view,
        // process column sorting.
        $page = $this->_request->page;
        $order = bagithelpers_doColumnSortProcessing($this->_request);
        $files = $collection->getFiles($page, $order);

        $this->view->collection = $collection;
        $this->view->files = $files;
        $this->view->current_page = $page;
        $this->view->total_results = count($files);
        $this->view->results_per_page = get_option('per_page_admin');

    }

    /**
     * Show list of files and controls for adding files to the selected collection.
     *
     * @return void
     */
    public function addfilesAction()
    {

        $collection_id = $this->_request->id;
        $collection = $this->getTable('BagitFileCollection')->find($collection_id);

        // Check for form submission, iterate over files and add/remove.
        if ($this->_request->isPost()) {
            $this->_addRemoveFilesFromCollection($collection, $this->_request->file);
        }

        // Get paging information for the pagination function in the view,
        // process column sorting.
        $page = $this->_request->page;
        $order = bagithelpers_doColumnSortProcessing($this->_request);
        $files = bagithelpers_getFilesForAdd($page, $order);

        $this->view->collection = $collection;
        $this->view->files = $files;
        $this->view->current_page = $page;
        $this->view->total_results = $this->getTable('File')->count();
        $this->view->results_per_page = get_option('per_page_admin');

    }


    /**
     * Show collections and form to add new collection.
     *
     * @return void
     */
    public function deletecollectionAction()
    {

        $collection_id = $this->_request->id;
        $collection = $this->getTable('BagitFileCollection')->find($collection_id);

        // If delete confirmed, do delete.
        if ($this->_request->getParam('confirm') == 'true') {

            $file_associations = $this->getTable('BagitFileCollectionAssociation')->fetchObjects(
                $this->getTable('BagitFileCollectionAssociation')->getSelect()->where('collection_id = ?', $collection_id)
            );

            $collection->delete();

            foreach ($file_associations as $assoc) {
                $assoc->delete();
            }

            $this->flashError('Collection "' . $collection->name . '" deleted.');
            return $this->_forward('browse', 'collections', 'bag-it');

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

        $collection_id = $this->_request->id;
        $collection = $this->getTable('BagitFileCollection')->find($collection_id);
        $form = $this->_doExportForm($collection);

        $this->view->form = $form;
        $this->view->collection = $collection;

    }

    /**
     * Process the final submission.
     *
     * @return void
     */
    public function exportAction()
    {

        if ($this->_request->isPost()) {

            $posted_form = $this->_request->getPost();

            if (!isset($posted_form['name_override']) || trim($posted_form['name_override'] == '')) {

                $this->flashError('Enter a name for the bag.');
                $this->_redirect('bag-it/collections/' . $posted_form['collection_id'] . '/exportprep');
                exit();

            } else {

                if (bagithelpers_doBagIt($posted_form['collection_id'], $posted_form['name_override'])) {

                    $this->view->bag_name = $posted_form['name_override'] . '.tgz';

                } else {

                    $this->flashError('There was an error. The Bag was not created.');
                    $this->_forward('exportprep', 'collections', 'bag-it');

                }

            }

        } else {

            $this->_forward('browse', 'collections', 'bag-it');

        }

    }

    /**
     * Show the upload form.
     *
     * @return void
     */
    public function importAction() {

        // Check to see if Dropbox is installed.
        if (!bagithelpers_checkForDropbox()) {
            $this->flashError('The Dropbox plugin must be installed to import bags.');
            $this->_forward('browse', 'collections', 'bag-it');
        }

        if ($this->_request->isPost()) {

            $form = $this->_doUploadForm();
            $posted_form = $this->_request->getPost();

            // Validate the file.
            if ($form->isValid($posted_form)) {

                $original_filename = pathinfo($form->bag->getFileName());
                $new_filename = $original_filename['basename'];
                $form->bag->addFilter('Rename', $new_filename);
                $form->bag->receive();

                if (bagithelpers_doReadBagIt($new_filename)) {
                    $this->flashSuccess('Bag successfully unpacked. Use the Dropbox plugin to process the files.');
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
     * @param object $collection The collection to be altered
     *
     * @return void.
     */
    protected function _addRemoveFilesFromCollection($collection, $files) {

        foreach ($files as $id => $value) {

            if ($value == 'add' && !$collection->checkForFileMembership($id)) {

                $assoc = new BagitFileCollectionAssociation;
                $assoc->collection_id = $collection->id;
                $assoc->file_id = $id;
                $assoc->save();

            } else if ($value == 'remove') {

                $assoc = $this->getTable('BagitFileCollectionAssociation')->fetchObject(
                    $this->getTable('BagitFileCollectionAssociation')->getSelect()
                    ->where('file_id = ?', $id)
                    ->where('collection_id = ?', $collection->id)
                );

                $assoc->delete();

            }

        }

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
     * @param string $collection The collection to be exported.
     *
     * @return object $form The upload form.
     */
    protected function _doExportForm($collection = null) {

        $form = new Zend_Form();
        $form->setAction('export')
            ->setMethod('post');

        $name_override = new Zend_Form_Element_Text('name_override');
        $name_override->setLabel('Enter a name for the exported file:')
            // ->setValue(str_replace(' ', '', $collection->name))
            ->setRequired(true);

        if ($collection != null) {
            $name_override->setValue(str_replace(' ', '', $collection->name));
        }

        $submit = new Zend_Form_Element_Submit('export_submit');
        $submit->setLabel('Create');

        $id = new Zend_Form_Element_Hidden('collection_id');
        // $id->setValue($collection->id);

        if ($collection != null) {
            $id->setValue($collection->id);
        }

        $form->addElement($name_override);
        $form->addElement($submit);
        $form->addElement($id);

        return $form;

      }

}
