<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Table class for the BagIt file collection associations.
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

class Table_BagitFileCollectionAssociation extends Omeka_Db_Table
{

    /**
     * Returns the number of files associated with a given collection.
     *
     * @param int $id The id of the collection.
     *
     * @return int The number of files.
     */
    public function getFilesPerId($id)
    {

        return count($this->fetchObjects(
            $this->getSelect()->where('collection_id = ' . $id)
        ));

    }

    /**
     * Test to see if a file is in a collection.
     *
     * @param int $file_id The id of the file.
     * @param int $collection_id The id of the collection.
     *
     * @return boolean True if the file is present.
     */
    public function checkForFileInCollection($file_id, $collection_id)
    {

        $select = $this->getSelect()
            ->where('file_id = ' . $file_id . ' AND collection_id = ' . $collection_id);

        return (count($this->fetchObjects($select)) == 1) ? true : false;

    }

    /**
     * Returns the number of files associated with a given collection.
     *
     * @param int $id The id of the collection.
     *
     * @return int The number of files.
     */
    public function getAssociationByIds($file_id, $collection_id)
    {

        $select = $this->getSelect()
            ->where('file_id = ?', $file_id)
            ->where('collection_id = ?', $collection_id);

        return $this->fetchObject($select);

    }

}
