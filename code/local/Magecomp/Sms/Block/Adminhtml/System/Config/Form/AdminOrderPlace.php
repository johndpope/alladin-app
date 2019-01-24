<?php

class Magecomp_Sms_Block_Adminhtml_System_Config_Form_AdminOrderPlace extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
		$this->setTemplate('sms/system/config/Admin_order_place.phtml');
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
        return Mage::helper('adminhtml')->getUrl('adminhtml/checksms/check');
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
            'id'        => 'admin_order_sms_button',
            'label'     => $this->helper('adminhtml')->__('Send Test SMS'),
            'onclick'   => "new Ajax.Request('".$this->getAjaxCheckUrl()."?mytab=admintemplate&mysection=orderplace' , {
						method: 'get',
            			onSuccess: function(transport)
						{
 							alert('Congratulation, Your text message has been sent, you will get Sms in short time');
			 			}
        				});"
        ));
 
        return $button->toHtml();
    }

 }