<?php echo $this->partial('index/admin-header.php', array('topnav' => 'unpack', 'subtitle' => 'Reader')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <h2>Upload and Unpack a Bag</h2>
    <p>Once the file is uploaded, the contents of the Bag will be automatically unpacked and made available through the Dropbox interface.</p>

    <?php echo $form; ?>

</div>

<?php echo foot();
