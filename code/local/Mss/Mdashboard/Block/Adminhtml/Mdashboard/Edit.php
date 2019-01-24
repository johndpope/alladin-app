<?php

class Mss_Mdashboard_Block_Adminhtml_Mdashboard_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mdashboard';
        $this->_controller = 'adminhtml_mdashboard';      
    }

    public function getHeaderText()
    {
        if( Mage::registry('mdashboard_data') && Mage::registry('mdashboard_data')->getId() ) {
            return Mage::helper('mdashboard')->__("Edit Dashboard '%s'", $this->htmlEscape(Mage::registry('mdashboard_data')->getId()));
        } else {
            return Mage::helper('mdashboard')->__('Add');
        }
    } 
}
