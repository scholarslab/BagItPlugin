<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Export')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <h3 class="bagit-success">Success! <a href="<?php echo public_url('/plugins/BagIt/bags/') . $bag_name; ?>">Click here to download the Bag</a>.</h3>

</div>

<?php echo foot();
