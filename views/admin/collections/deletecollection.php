<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Delete Collection')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <h2>Are you sure you want to delete the collection "<?php echo $collection->name; ?>"?</h2>
    <p>The files themselves won't be deleted - just the record of the grouping of the files represented by the collection.</p>

    <form action="<?php echo url('/bag-it/collections/' . $collection->id . '/delete'); ?>" method="post" class="button-form">
      <input type="hidden" name="confirm" value="true" />
      <input type="submit" name="addfiles-collection-<?php echo $collection->id; ?>" id="addfiles-collection-<?php echo $collection->id; ?>" value="Delete" class="bagit-delete">
    </form>

</div>

<?php echo foot();
