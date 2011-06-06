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

class BagitFileCollection extends Omeka_record
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

}
