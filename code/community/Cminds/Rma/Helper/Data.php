<?php
class Cminds_Rma_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Retrievies config value
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig('cminds_rma/general/enabled');
    }

    public function getSenderIdentify()
    {
        return Mage::getStoreConfig('cminds_rma/email/identity');
    }

    public function getCustomerUpdateEmailTemplate()
    {
        $template = Mage::getStoreConfig('cminds_rma/email/template_updated');

        if (is_string($template)) {
            return "rma_update";
        } else {
            return $template;
        }
    }

    public function getCustomerCreatedEmailTemplate()
    {
        $template = Mage::getStoreConfig('cminds_rma/email/template');

        if (is_string($template)) {
            return "rma_created";
        } else {
            return $template;
        }
    }

    public function getSenderEmail()
    {
        return Mage::getStoreConfig('trans_email/ident_'.$this->getSenderIdentify().'/email');
    }

    public function getSenderName()
    {
        return Mage::getStoreConfig('trans_email/ident_'.$this->getSenderIdentify().'/name');
    }

    public function getCopyEmails()
    {
        return explode(
            ",",
            Mage::getStoreConfig('cminds_rma/email/copy_to')
        );
    }
    public function getCopyMethod()
    {
        return Mage::getStoreConfig('cminds_rma/email/copy_method');
    }

    public function getVersion()
    {
        return (string) Mage::getConfig()->getNode()->modules->Cminds_Rma->version;
    }

    /**
     * Checking the entered quantity data.
     *
     * @param $dataQty
     *
     * @param $qtyOrdered
     *
     * @return bool
     */
    public function isVerifyNewQty($dataQty, $qtyOrdered)
    {
        if (!is_numeric($dataQty)) {
            return false;
        }

        if (!is_int(0 + $dataQty)) {
            return false;
        }

        if ($qtyOrdered < $dataQty || $dataQty < 0) {
            return false;
        }

        return true;
    }

}
