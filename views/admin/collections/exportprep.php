<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Create')); ?>

<div id="primary" class="bagit-export-form">

    <?php echo flash(); ?>

    <h2>Create a Bag with the contents of "<?php echo $collection->name; ?>"</h2>
    <p>Choose a compression format and a name for the exported file.</p>

    <?php echo $form; ?>

</div>

<?php foot(); ?>