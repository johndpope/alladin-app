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
 * SMS Helper Data
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Helper_Data extends Mage_Adminhtml_Helper_Data
{
	public function isEnable()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/enable',$storeId);
	}
	public function isMobileVerifcatoinRequired()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/verifyrequired',$storeId);
	}
	public function getLanguage()
	{
		$storeId = Mage::app()->getStore()->getStoreId();

		return Mage::getStoreConfig('sms/general/smslanguage',$storeId);
	}
	public function generateRandomString()
	{
		$length = $this->getOtpStringlenght();
		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		return $randomString;
	}
	public function isAdminReportEnable()
	{
		
		$storeId = Mage::app()->getStore()->getStoreId();
		
		return Mage::getStoreConfig('sms/registeredcustomer/enable',$storeId);
	}
	public function getAdminReportTime()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/registeredcustomer/smssendreporttoadmin',$storeId);
	}
	public function getLastCalledCronTime()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/sendtimelyreport/time',$storeId);
	}
	public function getOtpStringlenght()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/otp',$storeId);
	}
	
	public function getUsername()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/username',$storeId);
	}
	
	public function getPassword()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::helper('core')->decrypt(Mage::getStoreConfig('sms/general/password',$storeId));
	}
	
	public function getAuthkey()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::helper('core')->decrypt(Mage::getStoreConfig('sms/general/authkey',$storeId));
	}
	public function getRouttype()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::helper('core')->decrypt(Mage::getStoreConfig('sms/general/routtype',$storeId));
	}
	
	
	public function getApiUrl()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/apiurl',$storeId);
	}
	public function getSmsCharcterCount()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('sms/general/smstextchar',$storeId);
		}
		return false;
	}
	public function getSenderId()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/msg91sendername',$storeId);
	}
	
	public function iscontactFormForAdmin()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('admintemplate/contactus/enable',$storeId);
		}
		return false;
	}
	
	public function isCustomerRegisterSuccess()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/customer/enable',$storeId);
		}
		return false;
	}
	public function isCustomerRegisterSuccessForAdmin()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('admintemplate/customer/enable',$storeId);
		}
		return false;
	}
	
	public function issalesOrderPlace()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/orderplace/enable',$storeId);
		}
		return false;
	}
	public function issalesOrderPlaceForAdmin()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('admintemplate/orderplace/enable',$storeId);
		}
		return false;
	}
	public function isInvoceRegister()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/invoicegerate/enable',$storeId);
		}
		return false;
	}
	public function isCancelOrder()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/cancelorder/enable',$storeId);
		}
		return false;
	}
	public function isCompleteOrder()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/completeorder/enable',$storeId);
		}
		return false;
	}
	public function isReturnOrder()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/returnorder/enable',$storeId);
		}
		return false;
	}
	public function isReturnOrderForAdmin()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('admintemplate/returnorder/enable',$storeId);
		}
		return false;
	}
	public function getAdminMobileNumber()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/general/adminmobile',$storeId);
	}
	public function isupdateOrderTrackingNumber()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		if($this->isEnable())
		{
			return Mage::getStoreConfig('usertemplate/ordertracking/enable',$storeId);
		}
		return false;
	}
	public function getContactFormMessageForAdmin($name,$email,$CustomerNumber)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		
		$codes = array('{{name}}','{{email}}','{{mobilenumber}}','{{shop_name}}','{{shop_url}}');
		$accurate = array($name,$email,$CustomerNumber,$storename,$storeurl);

		return str_replace($codes,$accurate,Mage::getStoreConfig('admintemplate/contactus/message',$storeId));
	}
	public function getCustomerMessage($name,$fristname,$lastname,$randomcode)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();

		$codes = array('{{name}}','{{first_name}}','{{last_name}}','{{random_code}}','{{shop_name}}','{{shop_url}}');
		$accurate = array($name,$fristname,$lastname,$randomcode,$storename,$storeurl);
		return str_replace($codes,$accurate,Mage::getStoreConfig('usertemplate/customer/message',$storeId));
	}
	public function getCustomerMessageForAdmin($name,$fristname,$lastname,$randomcode)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();

		$codes = array('{{name}}','{{first_name}}','{{last_name}}','{{random_code}}','{{shop_name}}','{{shop_url}}');
		$accurate = array($name,$fristname,$lastname,$randomcode,$storename,$storeurl);
		return str_replace($codes,$accurate,Mage::getStoreConfig('admintemplate/customer/message',$storeId));
	}
	public function getOrderMessage(Mage_Sales_Model_Order $order)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		
		$billingAddress = $order->getBillingAddress();
		$storeId = Mage::app()->getStore()->getStoreId();
		$codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{last_name}}','{{street}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}');
		$accurate = array($storename,
							   $storeurl,
							   $billingAddress->getFirstname(),
                               $billingAddress->getLastname(),
							   $billingAddress->getStreetFull(),
							   $billingAddress->getFax(),
                               $billingAddress->getPostcode(),
                               $billingAddress->getCity(),
                               $billingAddress->getEmail(),
                               $order->getIncrementId()
                                );

		return str_replace($codes,$accurate,Mage::getStoreConfig('usertemplate/orderplace/message',$storeId));
	}
	public function getOrderMessageForAdmin(Mage_Sales_Model_Order $order)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		$billingAddress = $order->getBillingAddress();

		$codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{last_name}}','{{street}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}');
		$accurate = array($storename,
							   $storeurl,
							   $billingAddress->getFirstname(),
                               $billingAddress->getLastname(),
							   $billingAddress->getStreetFull(),
							   $billingAddress->getFax(),
                               $billingAddress->getPostcode(),
                               $billingAddress->getCity(),
                               $billingAddress->getEmail(),
                               $order->getIncrementId()
                                );

		return str_replace($codes,$accurate,Mage::getStoreConfig('admintemplate/orderplace/message',$storeId));
	}
	public function getMessageForShipment(Mage_Sales_Model_Order $order)
	{
		$billingAddress = $order->getBillingAddress();
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();

        $codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{middlename}}','{{last_name}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}');
        $accurate = array($billingAddress->getFirstname(),
            $billingAddress->getMiddlename(),
            $billingAddress->getLastname(),
            $billingAddress->getFax(),
            $billingAddress->getPostcode(),
            $billingAddress->getCity(),
            $billingAddress->getEmail(),
            $order->getIncrementId()
        );
		return str_replace($codes,$accurate,Mage::getStoreConfig('usertemplate/ordertracking/message',$storeId));
	}
	public function insertRandomCode($custoemrId)
	{
		// insert record
		$currunt_Date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$randomnumber = $this->generateRandomString();
		$randmoinsert =  Mage::getModel('sms/smsregisterotp');
		
		$randmoinsert->setRandomCode($randomnumber);
		$randmoinsert->setCreatedTime($currunt_Date);
		$randmoinsert->setCustomerId($custoemrId);
		$randmoinsert->save();
		
		return $randomnumber;
	}
	public function mobileVerified($customerid,$random_code)
	{	
		$random = Mage::getModel('sms/smsregisterotp')->getCollection()
				  ->addFieldToFilter('customer_id', $customerid);
		if(count($random) > 0 )
		{		  
			$firstItem = $random->getFirstItem(); 
			// isVerify = 1 : all ready verified
			// isVerify = 0 : still not veified
			if($firstItem->getIsVerify() == 1){
				echo "true";			
			}
			else if ($firstItem->getIsVerify() == 0 && $firstItem->getRandomCode() == $random_code){
				$firstItem->setIsVerify(1);
				$firstItem->save();
				echo "true";			
			}
			else{
				echo "false";
			}
		}
		else{
			echo "false";
		}
	}
	public function isUserVerified($customerid)
	{
		$random = Mage::getModel('sms/smsregisterotp')->getCollection()
				  ->addFieldToFilter('customer_id', $customerid);
		if(count($random) > 0 )
		{		  
			$firstItem = $random->getFirstItem(); 
			if($firstItem->getIsVerify() == 1){
				return "true";			
			}
			else{
				return "false";
			}
		}
		else{
			return "false";
		}
	}
	public function getTestMessage($message)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
        $codes = array('{{shop_name}}','{{shop_url}}','{{name}}','{{first_name}}','{{middlename}}','{{last_name}}','{{fax}}','{{postal}}','{{city}}','{{email}}','{{order_id}}','{{country_name}}','{{order_total}}','{{product_count}}','{{old_order_status}}','{{new_order_status}}','{{order_created_date}}','{{randm_code}}','{{random_code}}');
		
        $accurate = array($storename,$storeurl,'Name','First Name','Middle Name','Last Name','Fax','12345','City Name','test@gmail.com','10000001','India','$21000','5','Pending','Processing','25/12/2016','Gtvse258','Gtvse258'
        );
		return str_replace($codes,$accurate,$message);
	}
	public function testSmsApi($tab,$section)
	{
				$storeId = Mage::app()->getStore()->getStoreId();
				$message = Mage::getStoreConfig($tab.'/'.$section.'/'.'message',$storeId); 
				$message = $this->getTestMessage($message);
				$mobilenumbers =Mage::getStoreConfig($tab.'/'.$section.'/'.'testmobile',$storeId); 
				$customerName = "Admin Test";
				//curlApicall : this method will return tru or fale.
				$retunValue = $this->curlApiCall($message,$mobilenumbers,$customerName,"Test SMS");
	}
	public function previewTestSmsApi($tab,$section)
	{

				$storeId = Mage::app()->getStore()->getStoreId();
				$message = Mage::getStoreConfig($tab.'/'.$section.'/'.'message',$storeId); 
				$message = $this->getTestMessage($message);
				return $message;
	}
	public function getOrderStatusChagneMessage(Mage_Sales_Model_Order $order,$newStatus,$storeConfigName)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		
		$billingAddress = $order->getBillingAddress();
		$neworder = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
		$countryname = Mage::app()->getLocale()->getCountryTranslation($billingAddress->getCountry());
		
		$codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{last_name}}','{{street}}','{{fax}}','{{postal}}','{{city}}','{{country_name}}','{{email}}','{{order_id}}','{{order_total}}','{{product_count}}','{{old_order_status}}','{{new_order_status}}','{{order_created_date}}');
		$accurate = array(	   $storename,
							   $storeurl,
			   			       $billingAddress->getFirstname(),
                               $billingAddress->getLastname(),
							   $billingAddress->getStreetFull(),
							   $billingAddress->getFax(),
							   $billingAddress->getPostcode(),
                               $billingAddress->getCity(),
							   $countryname,
                               $billingAddress->getEmail(),
							   $order->getIncrementId(),
							   $order->getGrandTotal(),
							   $order->getTotalItemCount(),
							   $order->getStatus(),
							   $newStatus,
							   $order->getCreatedAt(),
						 );

		return str_replace($codes,$accurate,Mage::getStoreConfig('usertemplate/'.$storeConfigName.'/message',$storeId));
	}
	public function getOrderStatusChagneMessageForAdmin(Mage_Sales_Model_Order $order,$newStatus,$storeConfigName)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		
		$billingAddress = $order->getBillingAddress();
		$neworder = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
		$countryname = Mage::app()->getLocale()->getCountryTranslation($billingAddress->getCountry());
		
		$codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{last_name}}','{{street}}','{{fax}}','{{postal}}','{{city}}','{{country_name}}','{{email}}','{{order_id}}','{{order_total}}','{{product_count}}','{{old_order_status}}','{{new_order_status}}','{{order_created_date}}');
		$accurate = array(	   $storename,
							   $storeurl,
			   			       $billingAddress->getFirstname(),
                               $billingAddress->getLastname(),
							   $billingAddress->getStreetFull(),
							   $billingAddress->getFax(),
							   $billingAddress->getPostcode(),
                               $billingAddress->getCity(),
							   $countryname,
                               $billingAddress->getEmail(),
							   $order->getIncrementId(),
							   $order->getGrandTotal(),
							   $order->getTotalItemCount(),
							   $order->getStatus(),
							   $newStatus,
							   $order->getCreatedAt(),
						 );

		return str_replace($codes,$accurate,Mage::getStoreConfig('admintemplate/'.$storeConfigName.'/message',$storeId));
	}
	public function canResendOTP()
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		return Mage::getStoreConfig('sms/resendsms/enable',$storeId);
	}
	public function sendOtp($mobilenumber,$customerId)
	{
		if($customerId == 'guest')
		{
			$storeId = Mage::app()->getStore()->getStoreId();
			$storeurl = Mage::getBaseUrl();
			$storename = Mage::app()->getStore()->getName();
			$customerId="Name";
		
			$rendomString = $this->generateRandomString();
			Mage::getSingleton('core/session')->setRandomCode($rendomString);
			$codes = array('{{shop_name}}','{{shop_url}}','{{random_code}}');
			$accurate = array(	   $storename,
								   $storeurl,
								   $rendomString,
							 );
			$message = str_replace($codes,$accurate,Mage::getStoreConfig('sms/updatemobsms/message',$storeId));
			$retunValue=$this->curlApiCall($message,$mobilenumber,"","");
		}
		else
		{
			$storeId = Mage::app()->getStore()->getStoreId();
			$storeurl = Mage::getBaseUrl();
			$storename = Mage::app()->getStore()->getName();
			$randomcode = $this->addRandomCodeInTable($customerId); // will return new random code		
			$message=$this->getsendOTPMessage($customerId,$storeId);
			$retunValue=$this->curlApiCall($message,$mobilenumber,"","");
		}
		return $message;
	}
	public function resendOTPCode($mobilenumber,$customerId)
	{

		$message = $this->getResendOTPMessage($customerId,'resendsms');
		$mobilenumbers =$mobilenumber;
  	    $customerData = Mage::getModel('customer/customer')->load($customerId);
		$customerName = $customerData->getFirstname() ." " .$customerData->getLastname();
		//curlApicall : this method will return true or false.
		$retunValue = $this->curlApiCall($message,$mobilenumbers,$customerName,"Resend OTP");		
	}
	
	public function getResendOTPMessage($customerId,$storeConfigName)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
		$custoemr = Mage::getModel('customer/address')->load($customerId);
		$randomcode = $this->chagneRandomCode($customerId); // will return new random code
		$codes = array('{{shop_name}}','{{shop_url}}','{{first_name}}','{{last_name}}','{{randm_code}}');
		$accurate = array(	   $storename,
							   $storeurl,
			   			       $custoemr->getFirstname(),
							   $custoemr->getLastname(),
							   $randomcode,
						 );

		return str_replace($codes,$accurate,Mage::getStoreConfig('sms/'.$storeConfigName.'/message',$storeId));
	}
	public function chagneRandomCode($custoemrId)
	{
		// insert record
		$currunt_Date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$randomnumber = $this->generateRandomString();
						
		$random = Mage::getModel('sms/smsregisterotp')->getCollection()
				  ->addFieldToFilter('customer_id', $custoemrId);
		if(count($random) > 0 )
		{		  
			$firstItem = $random->getFirstItem(); 
			$firstItem->setRandomCode($randomnumber);
			$firstItem->setCreatedTime($currunt_Date);
			$firstItem->save();
		}
		return $randomnumber;
	}
	public function insertSmsReport($jobid,$message,$mobilenumbers,$customerName,$messageType)
	{
	 try{
		$messagecount = strlen($message);
		$messagecount = ceil($messagecount/$this->getSmsCharcterCount()); 
		$currunt_Date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$smsreport =  Mage::getModel('sms/smsreport');
		
		$smsreport->setJobno($jobid);
		$smsreport->setMessageSentTime($currunt_Date);
		$smsreport->setMessageText($message);
		$smsreport->setRecipientName($customerName);		
		$smsreport->setRecipientNumber($mobilenumbers);
		$smsreport->setMessageType($messageType);
		$smsreport->setMessageStatus(0);
		$smsreport->setMessageCount($messagecount);
				
		$smsreport->save();
		 }catch(Exception $e) {
			 Mage::log($e->getMessage());
		 }	
	}
	public function sendReportToAdmin()
	{
	 try{
		if($this->isAdminReportEnable())
		{
			$to = date("Y-m-d");
			$from = $this->getLastCalledCronTime();
			$diff = abs(strtotime($from) - strtotime($to)); 
			$days = floor($diff / (60*60*24));
			$needToCallTime = $this->getAdminReportTime();
			if($needToCallTime == $days) // it will call when days = 1,7,30
			{
               		$this->getCustomerRecoreWithinTimeDuration($from,$to,$days);
			}
			Mage::app()->cleanCache(); // will flush the cache. it Required beucase core_config_data called new data : getLastCalledCronTime() this method require flush the data
		}
	 }catch(Exception $e) {
		Mage::log("Call Execption Gerate Cron Of sendReportToAdmin ");
	 }
	}
	public function getCustomerRecoreWithinTimeDuration($from,$to,$messageInDays)
	{
		$collection = Mage::getModel('customer/customer')->getCollection()
	  			    ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
		Mage::getModel('core/config')->saveConfig('sms/sendtimelyreport/time', $to);
					
		$message = $this->getCustoemrCountMessage(count($collection),$messageInDays);
		$mobilenumbers = $this->getAdminMobileNumber();
		$customerName = "Admin";
		$retunValue = $this->curlApiCall($message,$mobilenumbers,$customerName,"Customer Registerd");
	}
	public function getCustoemrCountMessage($count,$messageInDays)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
        $codes = array('{{shop_name}}','{{shop_url}}','{{customer_count}}','{{day_month_week}}');
        $accurate = array($storename,$storename,$count,$messageInDays);

	return str_replace($codes,$accurate,Mage::getStoreConfig('sms/registeredcustomer/message',$storeId));
	}
	
	public function updateMobOTPCode($mobilenumber,$customid)
	{
		$message = $this->getsendOTPMessage($customid,'otpsend');
		if($message == "exist"){
			return $message;
		}
  	    $retunValue = $this->curlApiCall($message,$mobilenumber,"","Send OTP");		
		return $retunValue;
	}
	
	public function getsendOTPMessage($customid,$storeConfigName)
	{
		$storeId = Mage::app()->getStore()->getStoreId();
		$storeurl = Mage::getBaseUrl();
		$storename = Mage::app()->getStore()->getName();
	
		$randomcode = $this->addRandomCodeInTable($customid); // will return new random code
		$codes = array('{{shop_name}}','{{shop_url}}','{{random_code}}');
		$accurate = array(	   $storename,
							   $storeurl,
			   			       $randomcode,
						 );
		return str_replace($codes,$accurate,Mage::getStoreConfig('sms/updatemobsms/message',$storeId));
	}
	public function addRandomCodeInTable($customid)
	{
		$currunt_Date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
		$randomnumber = $this->generateRandomString();

		$random = Mage::getModel('sms/smsregisterotp')->getCollection()
				  ->addFieldToFilter('customer_id', $customid);
		if(count($random) > 0 )
		{
			$random = Mage::getModel('sms/smsregisterotp')->load($customid, 'customer_id');	
			$random->setRandomCode($randomnumber);
			$random->setCreatedTime($currunt_Date);
			$random->setCustomerId($customid);
			$random->save();
		}
		else{
			$random = Mage::getModel('sms/smsregisterotp');
			$random->setRandomCode($randomnumber);
			$random->setCreatedTime($currunt_Date);
			$random->setCustomerId($customid);
			$random->save();
			
		}
		return $randomnumber;
	}
	public function checkOtp($customid,$code){
		$random = Mage::getModel('sms/smsregisterotp')->getCollection()
				  ->addFieldToFilter('customer_id', $customid)
				  ->addFieldToFilter('random_code', $code);
		if(count($random) > 0 )
		{
			$firstItem = $random->getFirstItem(); 
			$firstItem->setIsVerify(1);
			$firstItem->save();
			return "true";
		}
		return "false";
	}
	
	public function curlApiCall($message,$mobilenumbers,$customerName,$messageType)
	{
		if($this->isEnable())
		{
				$language = $this->getLanguage();
//				$custMessag = $message;
				$message = urlencode($message);	
				$storeId = Mage::app()->getStore()->getStoreId();
				
				$senderid = $this->getSenderId(); //Your senderid
//				$messagetype = "N"; //Type Of Your Message
				$messagetype = $language; //Type Of Your Message
				$DReports = "Y"; //Delivery Reports
				$url=$this->getApiUrl()."sendhttp.php";
				$user = $this->getUsername();
				$password = $this->getPassword();
				
				$autykey = $this->getAuthkey();
				$type = $this->getRouttype();
				
				$postData = array(
					'authkey' => $autykey,
					'mobiles' => $mobilenumbers,
					'message' => $message,
					'sender' => $senderid,
					'route' => $type
				);
				
				$ch = curl_init();
				if (!$ch){die("Couldn't initialize a cURL handle");}
				$ret = curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt ($ch, CURLOPT_POSTFIELDS,$postData);
				
				$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$curlresponse = curl_exec($ch); // execute
		    	
				if(curl_errno($ch))
					Mage::log(curl_error($ch));
				
				if (empty($ret)) 
				{
					die(curl_error($ch));
					curl_close($ch); // close cURL handler
					
					return "error";
				}
				else
				{
					$info = curl_getinfo($ch);
					curl_close($ch); // close cURL handler
				}
		return "true";		
		}
		else
		{
			return "false";
		}
	}
	public function getCurruntBal($authkey,$routtype)
	{
		if($this->isEnable())
		{
			
				$user = $this->getUsername();
				$password = $this->getPassword();
			
				$postData = array(
					'user' => $user,
					'pass' => $password
				);
				$url=$this->getApiUrl()."checkbalance.php";
				
				$ch = curl_init();
				curl_setopt_array($ch, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $postData
					//,CURLOPT_FOLLOWLOCATION => true
				));
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				
				$curlresponse = curl_exec($ch);
				
				if( strpos($curlresponse, '{') !== false )
				{
					$splitstring = explode(",", $curlresponse);
					$jobid =  explode(":", $splitstring[1]);;
				
					if(strcmp($jobid[1],"error"))
						$curlresponse = "<b>Some thing wrong . pleaes check Authentication key and Type</b>";
				}
				
				if(curl_errno($ch)){
					Mage::log(curl_error($ch));
				}
				
				curl_close($ch);
				return $curlresponse;
		}
		
	}
}