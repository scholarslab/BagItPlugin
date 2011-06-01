<table>

    <thead>
        <tr>
            <th>Name</th>
            <th>Parent Item</th>
            <th>Type</th>
            <th>Size</th>
            <th>Add to Bag?</th>
        </tr>
    </thead>

    <tbody>

        <form method="post">

            <?php foreach ($files as $file): ?>

                <tr>
                    <td><a href="<?php echo public_uri('/archive/files/' . $file->archive_filename); ?>", target="_blank"><?php echo $file->original_filename; ?></a></td>
                    <td><a href="<?php echo public_uri('/items/show/' . $file->item_id); ?>"><?php echo bagithelpers_getItemName($file->item_id); ?></a></td>
                    <td><?php echo $file->type_os; ?></td>
                    <td><?php echo bagithelpers_getFileKb($file->id); ?> KB</td>
                    <td><?php echo $this->formCheckBox('file', $file->id) ?></td>
                </tr>

            <?php endforeach; ?>

        </form>

    </tbody>

</table>
