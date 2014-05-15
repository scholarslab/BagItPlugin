<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Add Files to "' . $collection->name . '"')); ?><div id="primary">

    <?php echo flash(); ?>

    <?php if (!bagithelpers_testForFiles()): ?>
        <p>There are no files on the site that can be added to a Bag.</p>

    <?php else: ?>

    <p>Check the files that you want to add to the "<?php echo $collection->name; ?>" collection. On each page, be sure to click the "Update Bag" button before switching to a different page or going back to the list of collections.</p>

        <form method="post" accept-charset="utf-8" id="bagit-addfiles-form">
            <fieldset>

            <table>
                <thead>
                    <tr>
                        <?php browse_sort_links(array(
                            'Name' => 'name',
                            'Parent' => 'parent_item',
                            'Type' => 'type',
                            'Size' => 'size',
                            'Add/Remove?' => null
                        )); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                        <tr <?php if ($collection->checkForFileMembership($file->id)) { echo 'class="bagit-file-already-added"'; } ?>>
                            <td><a href="<?php echo public_url('/archive/files/' . $file->archive_filename); ?>", target="_blank"><?php echo $file->original_filename; ?></a></td>
                            <td><a href="<?php echo public_url('/items/show/' . $file->item_id); ?>"><?php echo $file->parent_item; ?></a></td>
                            <td><?php echo $file->type_os; ?></td>
                            <td><?php echo bagithelpers_getFileKb($file->size); ?> KB</td>
                            <td class="bagit-checkbox-td">
                              <?php if (!$collection->checkForFileMembership($file->id)): ?>
                                <input type="checkbox" name="file[<?php echo $file->id; ?>]" id="file-<?php echo $file->id; ?>" value="add">
                                <p class="bagit-small">[+]</p>
                              <?php else: ?>
                                <input type="checkbox" name="file[<?php echo $file->id; ?>]" id="file-<?php echo $file->id; ?>" value="remove">
                                <p class="bagit-small">[-] Check to Remove</p>
                              <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php echo pagination_links(array('scrolling_style' => 'All', 
                'page_range' => '5',
                'partial_file' => 'common' . DIRECTORY_SEPARATOR . 'pagination_control.php',
                'page' => $current_page,
                'per_page' => $results_per_page,
                'total_results' => $total_results)); ?>
            </div>

            <?php echo submit(array('name' => 'export', 'class' => 'bagit-create-bag'), 'Export'); ?>
            <?php echo submit(array('name' => 'update_collection', 'class' => 'bagit-left-submit'), 'Update Bag'); ?>
            <?php echo submit(array('name' => 'add_all_files', 'class' => 'bagit-left-submit'), 'Add All Files'); ?>
            <?php echo submit(array('name' => 'remove_all_files', 'class' => 'bagit-delete bagit-left-submit'), 'Remove All Files'); ?>

            </fieldset>

        </form>

    <?php endif; ?>

</div>

<?php echo foot();
