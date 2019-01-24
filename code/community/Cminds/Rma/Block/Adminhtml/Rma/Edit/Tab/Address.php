<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Address extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $customerId = Mage::registry('rma_data')->getCustomerId();
        
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $customerAddressId = $customer->getDefaultBilling();
        $address = Mage::getModel('customer/address')->load($customerAddressId);

        $fieldset = $form->addFieldset('address_fieldset', array(
                'legend'    => $this->__("Contact Info"))
        );

        $fieldset->addField('firstname', 'text', array(
            'name' => 'firstname',
            'label' => Mage::helper('cminds_rma')->__('First Name'),
            'required' => true,
        ));

        $fieldset->addField('lastname', 'text', array(
            'name' => 'lastname',
            'label' => Mage::helper('cminds_rma')->__('Last Name'),
            'required' => true,
        ));

        $fieldset->addField('company', 'text', array(
            'name' => 'company',
            'label' => Mage::helper('cminds_rma')->__('Company'),
            'required' => false,
        ));

        $fieldset->addField('telephone', 'text', array(
            'name' => 'telephone',
            'label' => Mage::helper('cminds_rma')->__('Telephone'),
            'required' => true,
        ));

        $fieldset->addField('fax', 'text', array(
            'name' => 'fax',
            'label' => Mage::helper('cminds_rma')->__('Fax'),
            'required' => false,
        ));
        $fieldset = $form->addFieldset('return_address', array(
                'legend'    => $this->__("Return Address"))
        );

        $fieldset->addField('street', 'text', array(
            'name' => 'street',
            'label' => Mage::helper('cminds_rma')->__('Street Address'),
            'required' => true,
        ));

        $fieldset->addField('street_1', 'text', array(
            'name' => 'street_1',
            'required' => false,
        ));

        $fieldset->addField('city', 'text', array(
            'name' => 'city',
            'label' => Mage::helper('cminds_rma')->__('City'),
            'required' => true,
        ));

        $fieldset->addField('country_id', 'select', array(
        'name'  => 'country_id',
        'label'     => Mage::helper('cminds_rma')->__('Country'),
        'values'    => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(), 
    ));

        $fieldset->addField('postcode', 'text', array(
            'name' => 'postcode',
            'label' => Mage::helper('cminds_rma')->__('Zip Code'),
            'required' => true,
        ));

        $form->setValues($address->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

}