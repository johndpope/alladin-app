<?php

class Boonagel_Direct_Block_Adminhtml_Logs_Advanced extends Mage_Adminhtml_Block_Widget_Grid_Container {

   public function __construct() {
       $this->_controller = 'adminhtml_logs_advanced';
       $this->_blockGroup = 'direct';
       $this->_headerText = 'DirectPayOnline Logs Filter';
       
       parent::__construct();
       $this->_removeButton('add');
   }
   
   
}
