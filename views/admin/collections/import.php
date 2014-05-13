<?php echo $this->partial('collections/admin-header.php', array('topnav' => 'unpack', 'subtitle' => 'Import')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if ($dropbox): ?>

    <h2>Upload and Unpack a Bag</h2>
    <p>Once the file is uploaded, the contents of the Bag will be automatically unpacked and made available through the Dropbox interface.</p>

    <?php echo $form; ?>

    <?php endif; ?>

</div>

<?php echo foot();
