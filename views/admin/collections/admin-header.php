<?php

    $header = array('bodyclass' => 'bagit primary', 'title' => html_escape('BagIt | ' . $subtitle), 'content_class' => 'horizontal-nav');
    echo head($header);

?>

<h1><?php echo $header['title']; ?></h1>
<ul id="section-nav" class="navigation">
<?php echo nav(array(
    array( 'label' => 'Assemble Bags', 'uri' => url('bag-it/collections') ),
    array( 'label' => 'Import a Bag',  'uri'  => url('bag-it/import') )
))?>
</ul>
