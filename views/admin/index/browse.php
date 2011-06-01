<?php echo $this->partial('index/admin-header.php', array('topnav' => 'create', 'subtitle' => 'File Browser')); ?>

<ul id="section-nav" class="navigation">
    <li class="<?php if ($listStyle == 'list') { echo 'current'; } ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=list')); ?>">List View</a>
    </li>
    <li class="<?php if ($listStyle == 'hierarchy') { echo 'current'; } ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=hierarchy')); ?>">Item Hierarchy View</a>
    </li>
</ul>

<div id="primary">

    <?php echo flash(); ?>

    <?php foot(); ?>

</div>
