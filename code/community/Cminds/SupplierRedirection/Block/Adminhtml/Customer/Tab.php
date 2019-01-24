<?php
class Cminds_SupplierRedirection_Block_Adminhtml_Customer_Tab
    extends Mage_Core_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cminds/supplierredirection/supplier/edit/tab.phtml');
    }
    /**
     * @return string
     */
    public function getTabLabel() {
        return $this->__( 'Supplier Custom URL' );
    }

    /**
     * @return string
     */
    public function getTabTitle() {
        return "";
    }

    /**
     * @return bool
     */
    public function canShowTab() {
        return Mage::helper('supplierfrontendproductuploader')
            ->isSupplier(Mage::registry('current_customer')->getId());
    }

    /**
     * @return bool
     */
    public function isHidden() {
        return false;
    }

    public function getForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'      => 'edit_form_domain',
                'method'  => 'post',
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        $customer = Mage::registry('current_customer');

        $fieldset = $form->addFieldset(
            'domain_fieldset',
            array(
            )
        );
        $fieldset->addField(
            'domain',
            'text',
            array(
                'label'    => Mage::helper('marketplace')->__('URL'),
                'name'     => 'domain[url]',
                'value'     => $customer->getData('domain_url')
            )
        );
        return $fieldset;
    }
}
