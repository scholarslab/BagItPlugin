<?php

    $header = array('bodyclass' => 'bagit primary', 'title' => html_escape('BagIt | ' . $subtitle), 'content_class' => 'horizontal-nav');
    echo head($header);

?>

<h1><?php echo $header['title']; ?></h1>
<ul id="section-nav" class="navigation">
<?php echo nav(array(
    'Assemble Bags' => uri('bag-it/collections'),
    'Import a Bag'  => uri('bag-it/import')
))?>
</ul>
