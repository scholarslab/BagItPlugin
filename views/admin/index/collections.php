<?php echo $this->partial('index/admin-header.php', array('topnav' => 'create', 'subtitle' => 'File Collections')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if (!bagithelpers_testForFiles()): ?>
        <p>There are no files on the site that can be added to a Bag.</p>

    <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <?php browse_headings(array(
                            'Name' => 'name',
                            'Last Updated' => 'updated',
                            'Actions' => null
                        )); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($collections as $collection): ?>
                        <tr>
                            <td><a href="<?php echo uri('bagit/collections/' . $collection->id); ?>"><?php echo $collection->name; ?></a></td>
                            <td><?php echo $collection->getFormattedDate(); ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

    <?php endif; ?>

</div>

<?php foot(); ?>
