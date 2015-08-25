<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'File Collections')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if (count($collections) == 0): ?>

        <p>There are no collections. Create one!</p>

    <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <?php browse_sort_links(array(
                            'Name' => 'name',
                            'Number of Files' => 'number_of_files',
                            'Last Updated' => 'updated',
                            'Actions' => null
                        )); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($collections as $collection): ?>
                        <tr>
                            <td><a href="<?php echo url('bag-it/collections/' . $collection->id); ?>"><?php echo $collection->name; ?></a></td>
                            <td><?php echo $collection->getNumberOfAssociatedFiles(); ?></td>
                            <td><?php echo $collection->getFormattedDate(); ?></td>
                            <td width="30%"><?php echo $this->partial('collections/collection-actions.php', array('id' => $collection->id)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

    <?php endif; ?>

    <h2 class="bagit-create-new-collection-header">Create a new collection:</h2>

    <form method="post" action="<?php echo url(array('action' => 'addcollection', 'controller' => 'collections')) ?>" accept-charset="utf-8">

        <div id="bagit-create-collection">
            <?php echo $this->formText('collection_name', '', array('size' => 30)); ?>
        </div>

        <?php echo submit(array('name' => 'bagit_submit', 'class' => 'submit submit-medium bagit-left'), 'Create Collection'); ?>

    </form>

</div>

<?php echo foot();
