<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Record class for the BagIt file collections.
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

class BagitFileCollection extends Omeka_Record_AbstractRecord
{

    public $name;
    public $updated;

    /**
     * Returns timestamp in month-day-year format.
     *
     * @return string $date The formatted date.
     */
    public function getFormattedDate()
    {

        $date = new DateTime($this->updated);

        return '<strong>' . $date->format('F j, Y') . '</strong> at ' .
           $date->format('g:i a');

    }

    /**
     * Returns the number of files in the collection.
     *
     * @return int The number of files.
     */
    public function getNumberOfAssociatedFiles()
    {

        return $this->getTable('BagitFileCollectionAssociation')->getFilesPerId($this->id);

    }

    /**
     * Returns the number of files in the collection.
     *
     * @return int The number of files.
     */
    public function checkForFileMembership($file_id)
    {

        return $this->getTable('BagitFileCollectionAssociation')->checkForFileInCollection($file_id, $this->id);

    }

    /**
     * Returns the files contained in the collection.
     *
     * @param string $page The page to fetch.
     * @param string $order The constructed SQL order clause.
     *
     * @return array $files The files.
     */
    public function getFiles($page = null, $order = null)
    {

        $db = get_db();
        $fileTable = $this->getTable('File');

        $select = $fileTable->select()
            ->from(array('f' => $db->prefix . 'files'))
            ->joinLeft(array('a' => $db->prefix . 'bagit_file_collection_associations'), 'f.id = a.file_id')
            ->columns(array('size', 'type' => 'type_os', 'id' => 'f.id', 'name' => 'original_filename', 'parent_item' =>
                "(SELECT text from `$db->ElementText` WHERE record_id = f.item_id AND element_id = 50)"))
            ->where('a.collection_id = ' . $this->id);

        if (isset($page)) {
            $select->limitPage($page, get_option('per_page_admin'));
        }

        if (isset($order)) {
            $select->order($order);
        }

        return $fileTable->fetchObjects($select);

    }

    /**
     * Adds all files to the collection.
     *
     * @return array $files The files.
     */
    public function addAllFiles()
    {

        $fileTable = $this->getTable('File');
        $files = $fileTable->fetchObjects($fileTable->getSelect());

        foreach ($files as $file) {

            $test_for_assoc = $this->getTable('BagitFileCollectionAssociation')
                    ->getAssociationByIds($file->id, $this->id);

            if (count($test_for_assoc) == 0) {
                $assoc = new BagitFileCollectionAssociation;
                $assoc->file_id = $file->id;
                $assoc->collection_id = $this->id;
                $assoc->save();
            }

        }

    }

    /**
     * Adds all files to the collection.
     *
     * @return array $files The files.
     */
    public function removeAllFiles()
    {

        $associationsTable = $this->getTable('BagitFileCollectionAssociation');
        $select = $associationsTable->getSelect()->where('collection_id = ' . $this->id);
        $associations = $associationsTable->fetchObjects($select);

        foreach ($associations as $association) {
            $association->delete();
        }

    }

}
