<?php

    $header = array('bodyclass' => 'bagit primary', 'title' => html_escape('BagIt | File Browse'), 'content_class' => 'horizontal-nav');
    head($header);

?>

<h1><?php echo $header['title']; ?></h1>

<ul id="section-nav" class="navigation">
    <li class="<?php if (isset($_GET['view']) &&  $_GET['view'] == 'list') {echo 'current';} ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=list')); ?>">List View</a>
    </li>
    <li class="<?php if (isset($_GET['view']) && $_GET['view'] == 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(uri('bag-it/index/browse?view=hierarchy')); ?>">Item Hierarchy View</a>
    </li>
</ul>
