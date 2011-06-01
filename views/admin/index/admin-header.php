<?php

    $header = array('bodyclass' => 'bagit primary', 'title' => html_escape('BagIt | ' . $subtitle), 'content_class' => 'horizontal-nav');
    head($header);

?>


<div id="unpack-bag" class="bagit-topnav-button">
    <a class="<?php if ($topnav == 'unpack') { echo 'active'; } ?>" href="<?php echo html_escape(uri('bag-it/index/unpack')); ?>">Unpack a Bag</a>
</div>

<div id="create-bag" class="bagit-topnav-button">
    <a class="<?php if ($topnav == 'create') { echo 'active'; } ?>" href="<?php echo html_escape(uri('bag-it/index/browse?view=list')); ?>">Create a Bag</a>
</div>


<h1><?php echo $header['title']; ?></h1>
