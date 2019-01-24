<?php
/**
 * MageComp
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the magecomp.com license that is
 * available through the world-wide-web at this URL:
 * http://magecomp.com/license-agreement/
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @copyright   Copyright (c) 2016 Magegiant (http://magecomp.com/)
 * @license     http://magecomp.com/license-agreement/
 */

/**
 * SMS Index Controller
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_IndexController extends Mage_Core_Controller_Front_Action
{
	public function updateMobAction()
	{
		$newmobile = $this->getRequest()->get('newmobile');
		$oldmobile = $this->getRequest()->get('oldmobile');
		$customid = $this->getRequest()->get('customid');				
        $returnval = Mage::helper('sms/Data')->updateMobOTPCode($newmobile,$customid);		
		echo $returnval;
	}
	public function checkupdateotpAction(){
		$newmobile = $this->getRequest()->get('newmobile');
		$oldmobile = $this->getRequest()->get('oldmobile');
		$customid = $this->getRequest()->get('customid');
		$otp = $this->getRequest()->get('otp');		
						
		$returnval = Mage::helper('sms/Data')->checkOtp($customid,$otp);
		if($returnval == 'true')
		{
			if(count(Mage::getSingleton('customer/session')->getCustomer()->getAddresses()) == 0)
			{
				$address = Mage::getModel("customer/address");
				$address->setCustomerId($customid);
				$address->setTelephone($newmobile);
				$address->setIsDefaultBilling('1');
				$address->save();
	        }
			else
			{
			  $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getAddresses();
			  $customerAddress = array();
			  foreach($customerAddressId as $address){
				  $customerAddress[] = $address->getId();
			  }
			  $address = Mage::getModel('customer/address')->load($customerAddress[0]);
			  $address->setTelephone($newmobile);
			  $address->save();
			}
			Mage::getSingleton('core/session')->addSuccess('Mobile Number Updated Successfully.'); 
		}
		echo $returnval;
	}
	public function mobileUpdateAction()
	{
		$this->_title($this->__('Mobile Number Update'));
        $this->loadLayout();
        $this->renderLayout();
	}
	public function sendOtpAction()
	{
		$mobile = $this->getRequest()->getPost('mobile');
		if($mobile == 'logged')
		{
			$customer = Mage::getModel('customer/customer')->load(Mage::getSingleton('customer/session')->getId());
			$mobile = $customer->getPrimaryBillingAddress()->getTelephone();
			$responce = Mage::helper('sms/Data')->sendOtp($mobile,Mage::getSingleton('customer/session')->getId());
		}
		else
		{
			Mage::getSingleton('core/session')->setMobileOtp($mobile);
			$responce = Mage::helper('sms/Data')->sendOtp($mobile,'guest');	
		}
		return $responce;
	}
	public function indexAction()
	{
		$this->loadLayout();
        $this->renderLayout();
	}
	public function checkMobileVerificationCodeAction()
	{
		$random_code = $this->getRequest()->getPost('code');
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($customer->getId())
		{
			$return = Mage::helper('sms/Data')->mobileVerified($customer->getId(),$random_code);
			return $return;
		}
		else if(Mage::getSingleton('core/session')->getRandomCode() == $random_code)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
		
	}
	public function resendMobileCodeAction()
	{
		try
		{
		  $customer = Mage::getSingleton('customer/session')->getCustomer();	
		  $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
		  $address = Mage::getModel('customer/address')->load($customerAddressId);
		
		  Mage::helper('sms/Data')->resendOTPCode($address->getTelephone(),$customer->getId());
		  echo $this->__("We have Re-Send OTP in your mobile, please check and verify your account");
		  
		}catch(Exception $e)
		{
			Mage::log($e->getMessage());
			echo $this->__("Something went wrong");
		}
	}
	public function isUserMobileVerifiedAction()
	{ 
	try{
		if(Mage::helper('sms/Data')->isMobileVerifcatoinRequired()){
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			echo Mage::helper('sms/Data')->isUserVerified($customer->getId());
		}
		else
		{
			echo "true";
		}
	}catch(Exception $e)
		{
			Mage::log($e->getMessage());
			echo $this->__("Something went wrong");
		}	
	}
}