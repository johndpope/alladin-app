<?php

class Boonagel_Cba_Block_Adminhtml_Logs_Advanced extends Mage_Adminhtml_Block_Widget_Grid_Container {

   public function __construct() {
       $this->_controller = 'adminhtml_logs_advanced';
       $this->_blockGroup = 'cba';
       $this->_headerText = 'Cba Logs Filter';
       
       parent::__construct();
       $this->_removeButton('add');
   }
   
   
}
