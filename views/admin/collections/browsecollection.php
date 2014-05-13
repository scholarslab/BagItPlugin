<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Browse Collection "' . $collection->name . '"')); ?><div id="primary">

    <?php echo flash(); ?>

    <?php if ($total_results == 0): ?>
        <p>There are no files in the bag.</p>

    <?php else: ?>

    <h2>"<?php echo $collection->name; ?>" contains <?php echo $total_results; ?> files:</h2>

        <form method="post" action="" accept-charset="utf-8">
            <fieldset>

            <table>
                <thead>
                    <tr>
                        <?php browse_sort_links(array(
                            'Name' => 'name',
                            'Parent' => 'parent_item',
                            'Type' => 'type',
                            'Size' => 'size',
                            'Remove?' => null
                        )); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td><a href="<?php echo public_url('/archive/files/' . $file->archive_filename); ?>", target="_blank"><?php echo $file->original_filename; ?></a></td>
                            <td><a href="<?php echo public_url('/items/show/' . $file->item_id); ?>"><?php echo $file->parent_item; ?></a></td>
                            <td><?php echo $file->type_os; ?></td>
                            <td><?php echo bagithelpers_getFileKb($file->size); ?> KB</td>
                            <td class="bagit-checkbox-td">
                                <input type="checkbox" name="file[<?php echo $file->id; ?>]" id="file-<?php echo $file->id; ?>" value="remove">
                                <p class="bagit-small">[-] Check to Remove</p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <input type="submit" name="browsecollection_submit" value="Create Bag" class="bagit-create-bag bagit-side-by-side">
            <input type="submit" name="browsecollection_submit" value="Update Bag" class="bagit-side-by-side submit">

              <div class="pagination">

                  <?php echo pagination_links(array('scrolling_style' => 'All', 
                  'page_range' => '5',
                  'partial_file' => 'common' . DIRECTORY_SEPARATOR . 'pagination_control.php',
                  'page' => $current_page,
                  'per_page' => $results_per_page,
                  'total_results' => $total_results)); ?>

              </div>

            </fieldset>

        </form>

    <?php endif; ?>

</div>

<?php echo foot();
