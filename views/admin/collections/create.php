<?php echo $this->partial('index/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Bag Preview')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if ($success): ?>

        <h3 class="bagit-success">Success! <a href="<?php echo public_url('/plugins/BagIt/bags/') . $bag_name . '.tgz'; ?>">Click here to download the Bag</a>.</h3>

    <?php else: ?>

        <h2>There was an error. The Bag was not created.</h2>

    <?php endif; ?>

</div>

<?php echo foot();
