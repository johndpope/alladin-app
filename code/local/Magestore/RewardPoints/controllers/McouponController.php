<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_RewardPoints
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * RewardPoints Index Controller
 *
 * @category    Magestore
 * @package     Magestore_RewardPoints
 * @author      Magestore Developer
 */
class Magestore_RewardPoints_McouponController extends Mage_Core_Controller_Front_Action
{

    public function genrateCustCouponAction()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session         = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $email           = (string) $this->getRequest()->getPost('email');

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
            if ($subscriber->getId()) {
			     Mage::getSingleton('core/session')->addError($this->__('Email already subscribed.'));
			     $this->_redirectReferer();
			     return;
			}

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email)
                    ->getId();

                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                $this->__sendEmail($email);

                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                  //  $session->addSuccess($this->__('Confirmation request has been sent.'));
                } else {
                    $session->addSuccess($this->__('Thank you for your subscription.'));
                }
            } catch (Mage_Core_Exception $e) {
               // $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            } catch (Exception $e) {
               // $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
        $this->_redirectReferer();
    }

    protected function __sendEmail($email)
    {
    	$emailTemplate = Mage::getModel('core/email_template')->loadDefault('coupon_code_send_mss');
        //Getting the Store E-Mail Sender Name.
        $senderName = Mage::getStoreConfig('trans_email/ident_general/name');

        //Getting the Store General E-Mail.
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

        $code = $this->__getCode();

        //Variables for Confirmation Mail.
        $emailTemplateVariables          = array();
        $emailTemplateVariables['name']  = 'Guest';
        $emailTemplateVariables['email'] = $email;
        $emailTemplateVariables['code']  = $code;


        $cookie = Mage::getSingleton('core/cookie');
        $cookie->set('customcode', $code ,time()+86400,'/');

        //Appending the Custom Variables to Template.
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

        //Sending E-Mail to Customers.
        $mail = Mage::getModel('core/email')
            ->setToName($senderName)
            ->setToEmail($email)
            ->setBody($processedTemplate)
            ->setSubject('Subject : Coupon Code to get 20% off')
            ->setFromEmail($senderEmail)
            ->setFromName($senderName)
            ->setType('html');
        try {
            //Confimation E-Mail Send
            $mail->send();
            return true;
        } catch (Exception $error) {
            Mage::getSingleton('core/session')->addError($error->getMessage());
            return false;
        }
    }

    protected function __getCode(){
    	// Get the rule in question
		$rule = Mage::getModel('salesrule/rule')->load(2); //21 = ID of coupon in question

		// Define a coupon code generator model instance
		// Look at Mage_SalesRule_Model_Coupon_Massgenerator for options
		$generator = Mage::getModel('salesrule/coupon_massgenerator');

		$parameters = array(
		    'count'=>1,
		    'format'=>'alphanumeric',
		    'dash_every_x_characters'=>0,
		    'prefix'=>'jj2',
		    'suffix'=>'',
		    'length'=>12
		);

		$generator->setFormat( Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC );
		$generator->setDash( !empty($parameters['dash_every_x_characters'])? (int) $parameters['dash_every_x_characters'] : 0);
		$generator->setLength( !empty($parameters['length'])? (int) $parameters['length'] : 6);
		$generator->setPrefix( !empty($parameters['prefix'])? $parameters['prefix'] : '');
		$generator->setSuffix( !empty($parameters['suffix'])? $parameters['suffix'] : '');

		// Set the generator, and coupon type so it's able to generate
		$rule->setCouponCodeGenerator($generator);
		$rule->setCouponType( Mage_SalesRule_Model_Rule::COUPON_TYPE_AUTO );

		// Get as many coupons as you required
		$count = !empty($parameters['count'])? (int) $parameters['count'] : 1;
		$codes = array();
		for( $i = 0; $i < $count; $i++ ){
		  $coupon = $rule->acquireCoupon();
		  $coupon->setType(Mage_SalesRule_Helper_Coupon::COUPON_TYPE_SPECIFIC_AUTOGENERATED)->save();
		  $code = $coupon->getCode();
		  $codes = $code;
		}
		return $codes;
    }
}
