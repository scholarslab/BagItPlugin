<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Controller class for the BagIt administrative interface.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package omeka
 * @subpackage BagIt
 * @author Scholars' Lab
 * @author David McClure (david.mcclure@virginia.edu)
 * @copyright 2011
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 *
 * PHP version 5
 *
 */
?>

<?php

class BagIt_IndexController extends Omeka_Controller_Action
{

    /**
     * By default, redirect index requests to the browse action.
     *
     * @return void
     */
    public function indexAction()
    {

        $this->_redirect('/bag-it/index/browse?view=list');

    }

    /**
     * Push file data into the browse view.
     *
     * @return void
     */
    public function browseAction()
    {

        $listStyle = $this->_request->getParam('view');
        $this->view->listStyle = $listStyle;
    }

}
