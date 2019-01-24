<?php

class Cminds_Rma_Block_Adminhtml_System_Config_Form_About
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Prepare object.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cminds/rma/info.phtml');
    }

    /**
     * Return element html.
     *
     * @param Varien_Data_Form_Element_Abstract $element Element object.
     *
     * @return string
     */
    protected function _getElementHtml(
        Varien_Data_Form_Element_Abstract $element
    ) {
        return $this->_toHtml();
    }

    /**
     * Render field html.
     *
     * @param Varien_Data_Form_Element_Abstract $element Element object.
     *
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_getElementHtml($element);
    }
}