<?php
/**
 * Zozoconcepts_Blog extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Blog
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Blog comment form block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_Comment_Form
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function __construct() {
        $customerSession = Mage::getSingleton('customer/session');
        parent::__construct();
        $data =  Mage::getSingleton('customer/session')->getBlogCommentFormData(true);
        $data = new Varien_Object($data);
        // add logged in customer name as nickname
        if (!$data->getName()) {
            $customer = $customerSession->getCustomer();
            if ($customer && $customer->getId()) {
                $data->setName($customer->getFirstname());
                $data->setEmail($customer->getEmail());
            }
        }
        $this->setAllowWriteCommentFlag($customerSession->isLoggedIn() || Mage::getStoreConfigFlag('zozoconcepts_blog/blog/allow_guest_comment'));
        if (!$this->getAllowWriteCommentFlag()) {
            $this->setLoginLink(
                Mage::getUrl('customer/account/login/', array(
                    Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME => Mage::helper('core')->urlEncode(
                        Mage::getUrl('*/*/*', array('_current' => true)) .
                        '#comment-form')
                    )
                )
            );
        }
        $this->setCommentData($data);
    }
    /**
     * get current Blog
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog
     * @author Zozoconcepts Hybrid
     */
    public function getBlog() {
        return Mage::registry('current_blog');
    }
    /**
     * get form action
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getAction() {
        return Mage::getUrl('zozoconcepts_blog/blog/commentpost', array('id' => $this->getBlog()->getId()));
    }
}
