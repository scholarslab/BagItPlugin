<?php echo $this->partial('index/admin-header.php', array('topnav' => 'create', 'subtitle' => 'Bag Preview')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if (count($files) == 0): ?>
        <p>No files are marked to be added to the bag. <a href="<?php url(array('controller' => 'browse')); ?>">Go back to the file browser</a> to pick files.</p>

    <?php else: ?>

          <?php echo $this->partial('index/preview-list.php', array('files' => $files)); ?>

    <?php endif; ?>

</div>

<?php echo foot();
