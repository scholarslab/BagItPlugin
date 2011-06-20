# BagIt Omeka Plugin

This plugin implements the [BagIt 0.96 specification] for storing collecitons
of files. The plugin adds an administrative interface that allows users
to (a) generate and export Bags containing files on the site and (b)
import already existing bags and make their content available through
the [Dropbox] interface.

[BagIt 0.96 specification]: https://wiki.ucop.edu/display/Curation/BagIt 
[Dropbox]: http://omeka.org/codex/Plugins/Dropbox

## Installation

1. Upload the BagIt plugin directory to the plugins directory of your
   Omeka installation. See the [Installing a Plugin] page for details.

[Installing a Plugin]: http://omeka.org/codex/Installing_a_Plugin

2. Go to the plugins management interface by clicking on the "Settings"
   tab at the top right of the administrative interface and selecting
the "Plugins" tab.

3. Click the green "Install" button in the listing for the BagIt plugin.

## Create Bags

The BagIt plugin lets you create and edit an indefinite
number of "File Collections," arbitrary groupings of files that are native to the BagIt plugin and won't affect the presentation of association of the files elsewhere in Omeka. This makes it possible to assemble  different
combinations of files and export them as bags at any point.

To create a bag:

1. Click on the "BagIt" link in the main adminstrative menu along the
   top of the screen.

2. Before you can export a bag, you have to create at least one File
   Collection. Enter a name for the collection in the text box and click
"Create Collection." By default, the exported bag will have the same
name as the collection, but you can override this convention and enter a
different name for the exported bag during the export workflow.

3. Click the "Add Files" button to associate files with the collection.
   This takes you to a listing of all of the files on the site. Select
files that you want to add to the bag and click the "Update Bag" to save
the changes. If you select files and then navigate to another page in
the listings, the selections will automatically be saved. When you are
finished adding files, go back to the main file collection browser.

4. To delete a File Collection, click the red "Delete" button and
   confirm that you want to delete the collection.

5. To export the collection as a BagIt bag, you can either click
   directly on the "Create Bag" button in the file collections browser
or click on the name of the collection, which takes you to a summary
page that displays the files in the collection and allows you to remove
unwanted files. Click on the "Create Bag" button to continue.

5. Enter a name for the exported bag file. By default, this is the name
   of the collection.

6. Click on the link to download the bag.
