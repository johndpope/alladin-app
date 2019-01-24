<?php

class Boonagel_Alpesa_Block_Adminhtml_Logs_Voucher extends Mage_Adminhtml_Block_Widget_Grid_Container {

   public function __construct() {
       $this->_controller = 'adminhtml_logs_voucher';
       $this->_blockGroup = 'alpesa';
       $this->_headerText = 'Alpesa Voucher Payment Logs(Clicking automatically validates a payment)';
       
       parent::__construct();
       $this->_removeButton('add');
   }
   
   
}
