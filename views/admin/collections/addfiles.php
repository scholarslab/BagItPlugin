<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Add Files to "' . $collection->name . '"')); ?><div id="primary">

    <?php echo flash(); ?>

    <?php if (!bagithelpers_testForFiles()): ?>
        <p>There are no files on the site that can be added to a Bag.</p>

    <?php else: ?>

    <p>Check the files that you want to add to the "<?php echo $collection->name; ?>" collection. On each page, be sure to click the "Update Bag" button before switching to a different page or going back to the list of collections.</p>

        <form method="post" action="" accept-charset="utf-8">
            <fieldset>

            <table>
                <thead>
                    <tr>
                        <?php browse_headings(array(
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
                            <td><a href="<?php echo public_uri('/archive/files/' . $file->archive_filename); ?>", target="_blank"><?php echo $file->original_filename; ?></a></td>
                            <td><a href="<?php echo public_uri('/items/show/' . $file->item_id); ?>"><?php echo $file->parent_item; ?></a></td>
                            <td><?php echo $file->type_os; ?></td>
                            <td><?php echo bagithelpers_getFileKb($file->size); ?> KB</td>
                            <td class="bagit-checkbox-td">
                              <?php if (!$collection->checkForFileMembership($file->id)): ?>
                                <?php echo $this->formCheckBox('file[' . $file->id . ']', 'add') ?>
                                <p class="bagit-small">[+]</p>
                              <?php else: ?>
                              <?php echo $this->formCheckBox('file[' . $file->id . ']', 'remove') ?>
                                <p class="bagit-small">[-] Check to Remove</p>
                              <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php echo submit(array('name' => 'bagit_submit'), 'Update Bag'); ?>

            <div class="pagination"><?php echo pagination_links(array('page' => $current_page, 'per_page' => $results_per_page, 'total_results' => $total_results)); ?></div>

            </fieldset>

        </form>

    <?php endif; ?>

</div>

<?php foot(); ?>
