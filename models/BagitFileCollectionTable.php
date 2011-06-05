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

class BagitFileCollectionTable extends Omeka_Db_Table
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

        $matches = $this->fetchObjects(
            $this->getSelect()->where('b.name = "' . $name . '"')
        );

        return (count($matches) > 0) ? true : false;

    }

}
