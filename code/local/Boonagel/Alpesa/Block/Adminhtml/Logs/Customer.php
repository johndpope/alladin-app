<?php

class Boonagel_Alpesa_Block_Adminhtml_Logs_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container {

   public function __construct() {
       $this->_controller = 'adminhtml_logs_customer';
       $this->_blockGroup = 'alpesa';
       $this->_headerText = 'Alpesa Customers List';
       
       parent::__construct();
       $this->_removeButton('add');
   }
   
   
}
