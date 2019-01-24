<?php
/**
 * dynamic dashboard
 */
class Mss_Mdashboard_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_mdashboard';
    $this->_blockGroup = 'mdashboard';
    $this->_headerText = Mage::helper('mdashboard')->__('Dashboard');
    parent::__construct();
  }
}
