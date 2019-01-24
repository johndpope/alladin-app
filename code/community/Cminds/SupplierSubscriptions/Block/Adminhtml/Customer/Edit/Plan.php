<?php
class Cminds_SupplierSubscriptions_Block_Adminhtml_Customer_Edit_Plan
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface {
    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('suppliersubscriptions/customer/edit/plan.phtml');
    }
    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Subscription Plan');
    }
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view data of supplier subscribtion plans');
    }
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        $customerId = Mage::registry('current_customer')->getId();

        if(Mage::helper('suppliersubscriptions')->isSupplier($customerId))
        {
            return false;
        }
        else
        {
            return true;
        }

    }

    public function getPlans()
    {
        $plans = Mage::getModel('suppliersubscriptions/plan')->getCollection();

        return $plans->getData();
    }

    public function getSelectedPlan($planId)
    {
        $customerId = Mage::registry('current_customer')->getId();
        $html ='';
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $customerPlan = $customer->getCurrentPlan();
//        $currentPlan =
        if ($customerPlan == $planId){
            $html = 'selected="true"';
        }
        return $html;
    }

    public function getPlanFromDateToHtml()
    {
        $customerId = Mage::registry('current_customer')->getId();
        $customer = Mage::getModel('customer/customer')->load($customerId);

        return date("m/d/Y", strtotime($customer->getPlanFromDate()));
    }

    public function getPlanToDateToHtml()
    {
        $customerId = Mage::registry('current_customer')->getId();
        $customer = Mage::getModel('customer/customer')->load($customerId);

        return date("m/d/Y", strtotime($customer->getPlanToDate()));
    }

}