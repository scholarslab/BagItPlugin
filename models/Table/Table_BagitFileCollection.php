<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Table class for the BagIt file collections.
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

class Table_BagitFileCollection extends Omeka_Db_Table
{

    /**
     * Checks to see if a collection exists with a given name.
     *
     * @param string $name The name to check.
     *
     * @return boolean True if a record matches.
     */
    public function confirmUniqueName($name)
    {

        // To be Zend kosher, this would use 
        // Zend_Validate_Db_RecordExists. But how to make it work? 
        // Instead, query-and-count.
        $a = $this->getTableAlias();
        $matches = $this->fetchObjects(
            $this->getSelect()->where("$a.name = \"$name\"")
        );

        return (count($matches) > 0) ? false : true;

    }

    /**
     * Returns collections for the main listing.
     *
     * @param string $order The constructed SQL order clause.
     *
     * @return object The collections.
     */
    public function getCollectionsList($order = '')
    {

        $db = get_db();
        $select = $this->select()
            ->from(array('fc' => $db->prefix . 'bagit_file_collections'))
            ->columns(array('id', 'name', 'updated', 'number_of_files' =>
                "(SELECT COUNT(collection_id) from `$db->BagitFileCollectionAssociation` WHERE collection_id = fc.id)"))
            ->order($order);

        return $this->fetchObjects($select);

    }

}
