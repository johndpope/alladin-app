<?php

class Boonagel_Alpesa_Block_Adminhtml_Logs_Wallet extends Mage_Adminhtml_Block_Widget_Grid_Container {

   public function __construct() {
       $this->_controller = 'adminhtml_logs_wallet';
       $this->_blockGroup = 'alpesa';
       $this->_headerText = 'Alpesa Wallet Payment Logs(Clicking automatically validates a payment)';
       
       parent::__construct();
       $this->_removeButton('add');
   }
   
   
}
