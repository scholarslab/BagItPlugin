<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Miscellaneous helper functions.
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

define('KB_PER_BYTE', 0.0009765625);

/**
 * Test to see if there are any files on the site that could be put into a Bag.
 *
 * @return boolean True if files exist, false if there are no files.
 */
function bagithelpers_testForFiles() {

    $file_count = get_db()->getTable('File')->count();

    return ($file_count > 0) ? true : false;

}

/**
 * Returns the size of the file in kilobytes.
 *
 * @param int $size The size of the file in bytes.
 *
 * @return float The size of the file, rounded to two decimal places.
 */
function bagithelpers_getFileKb($size) {

    return round($size * KB_PER_BYTE, 2);

}

/**
 * Returns the title of the file's parent item.
 *
 * @param int $id The 'id' field of the file.
 *
 * @return string The title of the file's parent item.
 */
function bagithelpers_getItemName($id) {

    $_model = get_db()->getTable('ElementText');

    $element_text = $_model->fetchObject(
        $_model->getSelect()->where('record_id = ' . $id . ' AND element_id = 50')
    );

    return $element_text->text;

}
