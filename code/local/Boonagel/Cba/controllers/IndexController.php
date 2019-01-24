<?php

class Boonagel_Cba_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {

        $this->loadLayout();
        Mage::helper('Boonagel_Cba')->setTitle($this, "Cba Payments");
        $this->renderLayout();
    }


}
