<?php

class Magecomp_Sms_Block_Adminhtml_System_Config_Form_Preview_UserOrderPlace extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
		$this->setTemplate('sms/system/config/User_order_place.phtml');
    }
	/**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }
	public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/checksms/checkPreview');
    }
	/**
     * Generate button html
     *
     * @return string
     */
	 public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'user_order_place_sms_button',
            'label'     => $this->helper('adminhtml')->__('Preview SMS'),
             'onclick'   => "new Ajax.Request('".$this->getAjaxCheckUrl()."?mytab=usertemplate&mysection=orderplace' , {
						method: 'get',
            			onSuccess: function(transport)
						{
 							alert(transport.responseText);
			 			}
        				});"
        ));
 
        return $button->toHtml();
    }

 }