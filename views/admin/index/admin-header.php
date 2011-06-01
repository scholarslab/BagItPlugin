<?php

    $header = array('bodyclass' => 'bagit primary', 'title' => html_escape('BagIt | File Browser'), 'content_class' => 'horizontal-nav');
    head($header);

?>


<div id="unpack-bag" class="bagit-topnav-button">
    <a class="<?php if ($topnav == 'unpack') { echo 'active'; } ?>" href="<?php echo html_escape(uri('bag-it/index/unpack')); ?>">Unpack a Bag</a>
</div>

<div id="create-bag" class="bagit-topnav-button">
    <a class="<?php if ($topnav == 'create') { echo 'active'; } ?>" href="<?php echo html_escape(uri('bag-it/index/browse?view=list')); ?>">Create a Bag</a>
</div>


<h1><?php echo $header['title']; ?></h1>

<ul id="section-nav" class="navigation">
    <li class="<?php if ($liststyle == 'list') { echo 'current'; } ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=list')); ?>">List View</a>
    </li>
    <li class="<?php if ($liststyle == 'hierarchy') { echo 'current'; } ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=hierarchy')); ?>">Item Hierarchy View</a>
    </li>
</ul>
