<h2><?php echo count($files); ?> file<?php if (count($files) > 1) { echo 's'; } ?> selected</h2>

<form method="post" action="<?php echo url(array('action' => 'create')); ?>" accept-charset="utf-8">
    <fieldset>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent Item</th>
                <th>Type</th>
                <th>Size</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><a href="<?php echo public_url('/archive/files/' . $file->archive_filename); ?>", target="_blank"><?php echo $file->original_filename; ?></a></td>
                    <td><a href="<?php echo public_url('/items/show/' . $file->item_id); ?>"><?php echo bagithelpers_getItemName($file->item_id); ?></a></td>
                    <td><?php echo $file->type_os; ?></td>
                    <td><?php echo bagithelpers_getFileKb($file->id); ?> KB</td>
                    <?php echo $this->formHidden('file[' . $file->id . ']', 'add'); ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </fieldset>

    <div id="bagit-bag-name">
        <?php echo $this->formLabel('bag_name', 'Enter a name for the new bag:', array('class' => 'bagit-label')); ?>
        <?php echo $this->formText('bag_name', '', array('size' => 30)); ?>
    </div>

    <?php echo submit(array('name' => 'bagit_submit', 'class' => 'submit submit-medium'), 'Create Bag'); ?>
</form>
