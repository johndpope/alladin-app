<?php

class Cminds_Marketplace_SettingsController extends Cminds_Marketplace_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        $hasAccess = $this->_getHelper()->hasAccess();

        if(!$hasAccess) {
            $this->getResponse()->setRedirect($this->_getHelper('supplierfrontendproductuploader')->getSupplierLoginPage());
        }
    }

    public function shippingAction()
    {
        if(!Mage::getStoreConfig('marketplace_configuration/presentation/change_shipping_costs')) {
            $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
            $this->getResponse()->setHeader('Status','404 File not found');
            $this->_forward('defaultNoRoute');
        }

        $this->_renderBlocks();
    }

    public function shippingSaveAction()
    {
        $postData = $this->getRequest()->getPost();

        if(!Mage::getStoreConfig('marketplace_configuration/presentation/change_shipping_costs')) {
            $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
            $this->getResponse()->setHeader('Status','404 File not found');
            $this->_forward('defaultNoRoute');
        }

        try {
            $transaction = Mage::getModel('core/resource_transaction');
            $removedItems = explode(',', $postData['removedItems']);

            foreach($removedItems AS $item) {
                if(!$item) continue;
                $shipping = Mage::getModel('marketplace/methods')->load($item);

                if($shipping->getId()) {
                    $shipping->delete();
                }
            }
            if(!isset($postData['id'])) $postData['id'] = array();

            foreach($postData['id'] AS $i => $k) {
                $shipping = Mage::getModel('marketplace/methods')->load($k);

                $shipping->setSupplierId(Mage::helper('marketplace')->getSupplierId());
                $shipping->setName($postData['shipping_name'][$i]);
                $shipping->setFlatRateFee(0);
                $shipping->setFlatRateAvailable(0);
                $shipping->setTableRateAvailable(0);
                $shipping->setTableRateCondition(0);
                $shipping->setTableRateFee(0);
                $shipping->setFreeShipping(0);

                if(!isset($postData['shipping_method'][$i])) {
                    $shippingMethod = false;
                }
                if(isset($postData['shipping_method'][$i]) && isset($postData['shipping_method'][$i][$k])) {
                    $shippingMethod = $postData['shipping_method'][$i][$k];
                } else {
                    $shippingMethod = false;
                }

                if(!$shippingMethod && isset($postData['shipping_method']) && isset($postData['shipping_method'][$i]) && is_array($postData['shipping_method'][$i])) {
                    $shippingMethod = reset($postData['shipping_method'][$i]);
                }

                if ($shippingMethod && $shippingMethod == "flat_rate") {
                    $shipping->setFlatRateAvailable(1);
                    $shipping->setFlatRateFee($postData['flat_rate_fee'][$i]);
                } else {
                    $shipping->setFlatRateFee(0);
                    $shipping->setFlatRateAvailable(0);
                }
                if ($shippingMethod && $shippingMethod == "table_rate") {
                    $shipping->setTableRateAvailable(1);
                    if($k != '') {
                        $shipping->setTableRateFee($postData['table_rate_fee'][$i][$k]);
                    } else {
                        $shipping->setTableRateFee($postData['table_rate_fee'][$i]);
                    }

                    if(isset($postData['table_rate_condition'][$i])) {
                        $shipping->setTableRateCondition($postData['table_rate_condition'][$i]);
                    } else {
                        $shipping->setTableRateCondition(1);
                    }
                    $shipping->save();
                    $this->_parseUploadedCsv($i,$shipping->getId());
                } else {
                    $shipping->setTableRateFee(0);
                    $shipping->setTableRateAvailable(0);
                }
                if ($shippingMethod && $shippingMethod == "free_shipping") {
                    $shipping->setFreeShipping(1);
                } else {
                    $shipping->setFreeShipping(0);
                }

                Mage::dispatchEvent(
                    "marketplace_before_shipping_method_save",
                    array(
                        "method" => $shipping,
                        "post"  => $postData
                    )
                );

                $transaction->addObject($shipping);
            }

            $transaction->save();

            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl() . 'marketplace/settings/shipping/');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl() . 'marketplace/settings/shipping/');
            Mage::log($e->getMessage());
        }
    }

    public function profileAction()
    {
        if(!Mage::getStoreConfig('marketplace_configuration/general/supplier_page_enabled')) {
            $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
            $this->getResponse()->setHeader('Status','404 File not found');
            $this->_forward('defaultNoRoute');
        }

        $this->_renderBlocks(true, true, false, true);
    }

    public function profileSaveAction()
    {
        $postData = $this->getRequest()->getPost();

        if(!Mage::getStoreConfig('marketplace_configuration/general/supplier_page_enabled')) {
            $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
            $this->getResponse()->setHeader('Status','404 File not found');
            $this->_forward('defaultNoRoute');
        }

        try {
            $transaction = Mage::getModel('core/resource_transaction');
            $customerData = false;

            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getModel('customer/customer')->load(Mage::getSingleton('customer/session')->getId());
            }

            if(!$customerData) {
                throw new ErrorException('Supplier does not exists');
            }

            $waitingForApproval = false;
            $changed = false;

            if(isset($postData['submit'])) {
                // NAME
                $name = '';
                if (isset($postData['name'])) {
                    $name = htmlentities(strip_tags(trim($postData['name'])), ENT_QUOTES, "UTF-8");
                    if (($name != '') && ($name != $customerData->getSupplierName())) {
                        $customerData->setSupplierNameNew($name);
                        $changed = true;
                    }
                }
                if (($name == '') && !$customerData->getSupplierName()) {
                    throw new Exception("Field 'Name' can not be empty");
                }

                // DESCRIPTION
                $description = '';
                if (isset($postData['description'])) {
                    $description = $postData['description'];
                    $description = Mage::helper('marketplace')->removeSciptTags($description);
                    $description = strip_tags($description, '<ol><li><b><span><a><i><u><p><br><h1><h2><h3><h4><h5><div>');
                    if (($description != '') && ($description != $customerData->getSupplierDescription())) {
                        $customerData->setSupplierDescriptionNew($description);
                        $changed = true;
                    }
                }
                if (($description == '') && !$customerData->getSupplierDescription()) {
                    throw new Exception("Field 'Description' can not be empty");
                }

                // LOGO
                $path = Mage::helper('marketplace')->getLogosPath();
                if(isset($postData['remove_logo'])) {
                    $s = $customerData->getSupplierLogoNew();

                    if(file_exists($path . $s)) {
                        unlink($path . $s);
                    }

                    $customerData->setSupplierLogoNew('');
                }

                if(isset($_FILES['logo']['name']) and (file_exists($_FILES['logo']['tmp_name']))) {
                    $uploader = new Varien_File_Uploader('logo');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $nameSplit = explode('.', $_FILES['logo']['name']);
                    $ext = $nameSplit[count($nameSplit)-1];
                    $newName = md5($_FILES['logo']['name'] . time()) . '.' . $ext;
                    $uploader->save($path, $newName);

                    $customerData->setSupplierLogoNew($newName);
                    $changed = true;
                    $waitingForApproval = true;
                }

                if($changed) {
                    $customerData->setData('rejected_notfication_seen', 2);
                    $waitingForApproval = true;
                }

                // CUSTOM FIELDS
                $customFieldsCollection = Mage::getModel('marketplace/fields')->getCollection();
                $customFieldsValuesNew = array();
                $customFieldsValues = unserialize($customerData->getCustomFieldsValues());

                $hasCustomFieldChangedValues = false;
                foreach($customFieldsCollection AS $field) {
                    $newValue = $postData[$field->getName()];
                    if (isset($newValue) && $newValue != '') {
                        $hasCustomFieldChangedValues = true;
                    }
                }

                if ($hasCustomFieldChangedValues) {
                    foreach($customFieldsCollection AS $field) {
                        $oldValue = $this->_findValue($field->getName(), $customFieldsValues);
                        if (!isset($oldValue) || $oldValue == null) {
                            $oldValue = '';
                        }
                        $newValue = $postData[$field->getName()];
                        if ($field->getType() == 'textarea') {
                            $newValue = Mage::helper('marketplace')->removeSciptTags($newValue);
                            $newValue = strip_tags($newValue, '<ol><li><b><span><a><i><u><p><br><h1><h2><h3><h4><h5><div>');
                        } else {
                            $newValue = strip_tags($newValue);
                        }

                        if (!isset($newValue) || $newValue == null) {
                            $newValue = '';
                        }
                        if ($field->getIsRequired() && $oldValue == '' && $newValue == '') {
                            throw new Exception("Field ".$field->getName()." is required");
                        }
                        if ($newValue != '') {
                            if ($oldValue != $newValue) {
                                $changed = true;
                                if ($field->getMustBeApproved()) {
                                    $customerData->setData('rejected_notfication_seen', 2);
                                    $waitingForApproval = true;
                                }
                                $customFieldsValuesNew[] = array('name' => $field->getName(), 'value' => $newValue);
                            }
                        } else {
                            $customFieldsValuesNew[] = array('name' => $field->getName(), 'value' => $oldValue);
                        }
                    }
                    if ($changed) {
                        if ($waitingForApproval) {
                            $customerData->setNewCustomFieldsValues(serialize($customFieldsValuesNew));
                        } else {
                            $customerData->setCustomFieldsValues(serialize($customFieldsValuesNew));
                        }
                    }
                }

                // PROFILE PAGE ENABLED
                if(isset($postData['profile_enabled'])) {
                    $customerData->setSupplierProfileVisible(1);
                } else {
                    $customerData->setSupplierProfileVisible(0);
                }

            } elseif(isset($postData['clear'])) {
                $customerData->setSupplierNameNew('');
                $customerData->setSupplierDescriptionNew('');
                $customerData->setSupplierLogoNew('');
                $customerData->setNewCustomFieldsValues(array());
            }

            $transaction->addObject($customerData);
            $transaction->save();

            if($waitingForApproval) {
                Mage::helper('marketplace/email')->notifyAdminOnProfileChange($customerData);
                Mage::getSingleton('core/session')->addSuccess($this->_getHelper()->__('Profile was changed and waiting for admin approval'));
            } elseif ($changed) {
                Mage::getSingleton('core/session')->addSuccess($this->_getHelper()->__('Your profile was changed'));
            }

            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl() . 'marketplace/settings/profile/');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl() . 'marketplace/settings/profile/');
            Mage::log($e->getMessage());
        }
    }

    protected function _parseUploadedCsv($index, $methodId)
    {
        $changed = false;
        $parsedData = array();
        if (isset($_FILES["table_rate_file"])) {
            $changed = true;
                if(file_exists($_FILES["table_rate_file"]["tmp_name"][$index])) {
                if (($handle = fopen($_FILES["table_rate_file"]["tmp_name"][$index], "r")) !== FALSE) {
                    while (($row = fgetcsv($handle)) !== FALSE)
                    {
                        $parsedData[] = $row;
                    }
                    fclose($handle);
                } else {
                    throw new ErrorException('Cannot handle uploaded CSV');
                }
            }
        }
        if(!$parsedData) {
            return false;
        }

        if($parsedData[0][0] == 'Country') {
            unset($parsedData[0]);
        }

        if($changed) {
            $supplierRate = Mage::getModel("marketplace/rates")
                ->load(Mage::helper('marketplace')->getSupplierId(), 'supplier_id');

            if(!$supplierRate->getId()) {
                $supplierRate->setSupplierId(Mage::helper('marketplace')->getSupplierId());
            }

            $supplierRate->setRateData(serialize($parsedData));
            $supplierRate->setMethodId($methodId);
            $supplierRate->save();
        }
    }

    protected function _findValue($name, $data)
    {
        if(!is_array($data)) return false;
        
        foreach($data AS $value) {
            if($value['name'] == $name) {
                return $value['value'];
            }
        }

        return false;
    }
}
