<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'unpack', 'subtitle' => 'Import')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <h2>Create a Bag</h2>
    <p>Choose a compression format and a name for the exported file (by default, the Bag will have the same name as the collection).</p>

    <?php echo $form; ?>

</div>

<?php foot(); ?>
