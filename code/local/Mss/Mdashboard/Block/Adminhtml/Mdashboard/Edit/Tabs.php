<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mdashboard_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mdashboard')->__('Dashboard Manager'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mdashboard')->__('Dashboard Information'),
          'title'     => Mage::helper('mdashboard')->__('Dashboard Information'),
          'content'   => $this->getLayout()->createBlock('mdashboard/adminhtml_mdashboard_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
