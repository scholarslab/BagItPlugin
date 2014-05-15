<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Create')); ?>

<div id="primary" class="bagit-export-form">

    <?php echo flash(); ?>

    <h2>Create a Bag with the contents of "<?php echo $collection->name; ?>"</h2>

    <?php echo $form; ?>

</div>

<?php echo foot();
