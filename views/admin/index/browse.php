<?php echo $this->partial('index/admin-header.php', array('topnav' => 'create', 'subtitle' => 'File Browser')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if (!bagithelpers_testForFiles()): ?>
        <p>There are no files on the site that can be added to a Bag.</p>

    <?php else: ?>

          <?php echo $this->partial('index/browse-list.php', array('files' => $files)); ?>

    <?php endif; ?>

</div>

<?php foot(); ?>
