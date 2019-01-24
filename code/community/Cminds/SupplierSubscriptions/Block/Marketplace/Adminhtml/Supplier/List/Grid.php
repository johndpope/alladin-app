<?php

/**
 * Created by PhpStorm.
 * User: jakub
 * Date: 20.01.16
 * Time: 19:06
 */
class Cminds_SupplierSubscriptions_block_Marketplace_Adminhtml_Supplier_List_Grid
    extends Cminds_Marketplace_Block_Adminhtml_Supplier_List_Grid
{

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('created_at')
            ->addAttributeToSelect('group_id')
            ->addAttributeToSelect('rejected_notfication_seen')
            ->addAttributeToSelect('supplier_approve')
//            ->addAttributeToSelect('supplier_name')
//            ->addAttributeToSelect('supplier_logo')
//            ->addAttributeToSelect('supplier_approve')
//            ->addAttributeToSelect('business_primary')
            ->addAttributeToSelect('current_plan')
            ->addAttributeToSelect('plan_to_date')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
//            ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
        ;

        $collection->addFilter('group_id', Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/supplier_group_id'));

        if(Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/supplier_group_id') != Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/editor_group_id')) {
            $collection->addFilter('group_id', Mage::getStoreConfig('supplierfrontendproductuploader_catalog/supplierfrontendproductuploader_supplier_config/editor_group_id'), 'or');
        }

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  => 'number',
        ));
        $this->addColumn('waiting_for_approval', array(
            'header'    => Mage::helper('customer')->__('Profile waiting for approval'),
            'width'     => '50px',
            'index'     => 'rejected_notfication_seen',
            'type'      => 'number',
            'renderer'  => 'Cminds_Marketplace_Block_Adminhtml_Supplier_List_Renderer_Waiting'
        ));

//        $this->addColumn('name', array(
//            'header'    => Mage::helper('customer')->__('Supplier Name'),
//            'index'     => 'supplier_name'
//        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'name'
        ));

//        $this->addColumn('billing_street', array(
//            'header'    => Mage::helper('customer')->__('Address'),
//            'width'     => '90',
//            'index'     => 'billing_street',
//        ));

        $this->addColumn('billing_city', array(
            'header'    => Mage::helper('customer')->__('City'),
            'width'     => '90',
            'index'     => 'billing_city',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('customer')->__('Email'),
            'width'     => '150',
            'index'     => 'email'
        ));

        $this->addColumn('Telephone', array(
            'header'    => Mage::helper('customer')->__('Telephone'),
            'width'     => '100',
            'index'     => 'billing_telephone'
        ));

//        $businesses = Mage::getModel('supplierregistrationextended/eav_entity_attribute_source_business')->getOptionArray();
//        foreach ($businesses as &$business) {
//            $business = '';
//            break;
//        }

//        $this->addColumn('business_primary', array(
//            'header'    => Mage::helper('customer')->__('Category'),
//            'width'     => '100',
//            'type'      => 'options',
//            'options'   => array_filter($businesses),
//            'index'     => 'business_primary'
//        ));

        $this->addColumn('customer_since', array(
            'header'    => Mage::helper('customer')->__('Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true
        ));

        $plans = Mage::getModel('suppliersubscriptions/config_plans')->getOptionArray();
        foreach ($plans as &$plan) {
            $plan = '';
            break;
        }
        $this->addColumn('current_plan', array(
            'header'    => Mage::helper('customer')->__('Plan'),
            'width'     => '100',
            'type'      => 'options',
            'options'   => $plans,
            'index'     => 'current_plan'
        ));

        $this->addColumn('plan_to_date', array(
            'header'    => Mage::helper('customer')->__('Renewal Date'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'plan_to_date',
            'gmtoffset' => true
        ));

//        $this->addColumn('supplier_logo', array(
//            'header' => Mage::helper('catalog')->__('Logo'),
//            'align' => 'left',
//            'index' => 'supplier_logo',
//            'width'     => '25',
//            'renderer' => 'Cminds_SupplierRegistrationExtended_Block_Adminhtml_Template_Grid_Renderer_Image'
//        ));
//        $this->addColumn('supplier_banner', array(
//            'header' => Mage::helper('catalog')->__('Banner'),
//            'align' => 'left',
//            'index' => 'supplier_logo',
//            'width'     => '25',
//            'renderer' => 'Cminds_SupplierRegistrationExtended_Block_Adminhtml_Template_Grid_Renderer_Banner'
//        ));
//        $this->addColumn('supplier_approve', array(
//            'header' => Mage::helper('catalog')->__('Approve'),
//            'align' => 'left',
//            'index' => 'supplier_approve',
//            'width'     => '90',
//            'renderer' => 'Cminds_SupplierRegistrationExtended_Block_Adminhtml_Template_Grid_Renderer_Approve'
//        ));

        $this->addColumn('supplier_approve', array(
            'header'    => Mage::helper('customer')->__('Is Approved'),
            'align'     => 'center',
            'width'     => '100',
            'index'     => 'supplier_approve',
            'type' => 'options',
            'options' => array('1' => 'Yes', '0' => 'No')
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('customer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('customer')->__('Edit'),
                        'url'       => array('base'=> '*/customer/edit', 'params' => array('supplier' => true)),
                        'field'     => 'id',
                        'supplier'  => true
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('customer')->__('Excel XML'));
        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id')
            ->setErrorText(
                Mage::helper('core')->jsQuoteEscape(
                    Mage::helper('marketplace')->__('Please select supplier')
                )
            );

        $this->getMassactionBlock()->addItem('approve', array(
            'label' => Mage::helper('marketplace')->__('Approve'),
            'url'   => $this->getUrl('*/*/massApprove'),
            'confirm' => Mage::helper('marketplace')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('dissapprove', array(
            'label' => Mage::helper('marketplace')->__('Dissapprove'),
            'url'   => $this->getUrl('*/*/massDissapprove'),
            'confirm' => Mage::helper('marketplace')->__('Are you sure?')
        ));
        return $this;
    }
}