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
 * A homebrew colum sorter, implemented so as to keep more control 
 * over how the record loop is handled in the view.
 *
 * @param object $request The incoming request dispatched by the 
 * front controller.
 *
 * @return string $order The sorting parameter for the query.
 */
function bagithelpers_doColumnSortProcessing($request)
{

    $sort_field = $request->getParam('sort_field');
    $sort_dir = $request->getParam('sort_dir');

    if (isset($sort_dir)) {
        $sort_dir = ($sort_dir == 'a') ? 'ASC' : 'DESC';
    }

    return (isset($sort_field)) ? trim(implode(' ', array($sort_field, $sort_dir))) : '';

}

/**
 * Create the bag, generate tar.
 *
 * @param array $file_ids Array of ids, posted from the form.
 * @param string $name The name of the bag.
 *
 * @return boolean $success True if the new bag validates.
 */
function bagithelpers_doBagIt($collection_id, $collection_name, $format)
{

    $db = get_db();

    // Instantiate the bag, get the collection.
    $bag = new BagIt(BAGIT_BAG_DIRECTORY . DIRECTORY_SEPARATOR . $collection_name);
    $collection = $db->getTable('BagitFileCollection')->find($collection_id);

    // Get the files associated with the collection.
    $files = $collection->getFiles();

    // Retrieve the files and add them to the new bag.
    foreach ($files as $file) {
        $bag->addFile(BASE_DIR . DIRECTORY_SEPARATOR . OMEKA_FILES_RELATIVE_DIRECTORY .
            DIRECTORY_SEPARATOR .  $file->archive_filename, $file->original_filename
        );
    }

    // Update the hashes.
    $bag->update();

    // Tar it up.
    $bag->package(BAGIT_BAG_DIRECTORY . DIRECTORY_SEPARATOR . $collection_name, $format);

    // Why are the bags not validating?
    // return true;
    return $bag->isValid() ? true : false;

}

/**
 * Read the Bag, unpack it, drop files into the Dropbox files directory
 *
 * @param string $filename The name of the uploaded bag.
 *
 * @return boolean $success True if the read succeeds.
 */
function bagithelpers_doReadBagIt($filename)
{

    $success = false;

    $bag = new BagIt(BAGIT_TMP_DIRECTORY . DIRECTORY_SEPARATOR . $filename);
    $bag->validate();

    if (count($bag->getBagErrors()) == 0) {

        $bag->fetch->download();

        // Copy each of the files.
        foreach ($bag->getBagContents() as $file) {
            copy($file, BASE_DIR . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR .
                'Dropbox' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . basename($file));
        }

        $success = true;

    }

    return $success;

}
