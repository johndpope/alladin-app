<?php
class Magecomp_Sms_Model_Observer
{
	public function sendReportToAdmin()
	{
	 try{
		Mage::log("Call Cron Of sendReportToAdmin 111");
		
	 }catch(Exception $e) {
		Mage::log("Call Execption Gerate Cron Of sendReportToAdmin ");
	 }
	}
	public function generateRandomString()
	{
	try{
		$length = $this->getHelper()->getOtpStringlenght();
		$randomString = 
				substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		return $randomString;
	   
	   }catch(Exception $e) {
		  Mage::log($e->getMessage());
	  }
	}
	public function customerRegisterSuccess(Varien_Event_Observer $observer)
	{
	  try
	  {	
	  
		
			$event = $observer->getEvent();
        	$customer = $event->getCustomer();
			$country_code = $_POST['country_code'];
 			$mobilenumbers = $country_code.$_POST['mobile_number'];
			$address = Mage::getModel("customer/address");
			// you need a customer object here, or simply the ID as a string.
			$address->setCustomerId($customer->getId());
			$address->setFirstname($customer->getFirstname());
			$address->setLastname($customer->getLastname());
			$address->setTelephone($mobilenumbers);
			$address->setIsDefaultBilling('1');
			$address->save();
			
			if($mobilenumbers)
			{
				$randomnumber =  $this->getHelper()->insertRandomCode($customer->getId());
				//************** for user **********************
				if($this->getHelper()->isCustomerRegisterSuccess())
				{
				  $message = $this->getHelper()->getCustomerMessage(
							  $customer->getName(),
							  $customer->getFirstname(),
							  $customer->getLastname(),
							  $randomnumber); //enter Your Message
						  
				  $customerName = $customer->getFirstname(). " " .$customer->getLastname();
				  //curlApicall : this method will return tru or fale.
				  $retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Customer Registration");
				}
				//******************* For Amdin ***************************
				if($this->getHelper()->isCustomerRegisterSuccessForAdmin())
				{
				  $message =  $this->getHelper()->getCustomerMessageForAdmin(
							  $customer->getName(),
							  $customer->getFirstname(),
							  $customer->getLastname(),
							  $randomnumber);
				  $customerName = $customer->getFirstname(). " " .$customer->getLastname();			
				  $mobilenumbers = $this->getHelper()->getAdminMobileNumber();			
				  $retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Customer Registration");
				}			
				
			}
		
	  }catch(Exception $e) {
		  Mage::log($e->getMessage());
	  }
	}
	
	public function salesOrderPlace(Varien_Event_Observer $observer)
	{
	    try
		{
            $order_id = $observer->getData('order_ids');
			$order = Mage::getModel('sales/order')->load($order_id);
			if ($order instanceof Mage_Sales_Model_Order)
			{
			  if($this->getHelper()->issalesOrderPlace())
			  {	
				$mobilenumbers = $order->getBillingAddress()->getTelephone(); 
				$message = $this->getHelper()->getOrderMessage($order); //enter Your Message
				$customerName = $order->getCustomerName();
				//curlApicall : this method will return tru or fale.
				$retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"New Order");	  
			  }
			  if($this->getHelper()->issalesOrderPlaceForAdmin())
			  {	
				$mobilenumbers = $this->getHelper()->getAdminMobileNumber();		 
				$message = $this->getHelper()->getOrderMessageForAdmin($order); //enter Your Message
				$customerName = $order->getCustomerName();
				//curlApicall : this method will return tru or fale.
				$retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"New Order");	  
			  }	
			}
		//return false;
	  }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }	
	}
	 
	public function updateOrderTrackingNumber(Varien_Event_Observer $observer)
	{
	try{	

		  if($this->getHelper()->isupdateOrderTrackingNumber())
		  {
			
			  $shipment = $observer->getEvent()->getShipment();
			  if(!array_key_exists('shipping_label',$shipment->getData())){ // New added on 14-04-2017
			 
				  $order = $shipment->getOrder();
				  if ($order instanceof Mage_Sales_Model_Order)
				  {
					  $billingAddress = $order->getBillingAddress();
						$countryname = Mage::app()->getLocale()->getCountryTranslation($billingAddress->getCountry());		  
								  $streetFull =  $billingAddress->getStreetFull();
								  $fax =  $billingAddress->getFax();
								  $postcode =  $billingAddress->getPostcode();
								  $city =  $billingAddress->getCity();
								  $email =  $billingAddress->getEmail();
								  $incrementid =  $order->getIncrementId();
								  
					  //enter Mobile numbers comma seperated
					  $mobilenumbers = $order->getBillingAddress()->getTelephone(); 
					  $templateid = $this->getHelper()->getMessageForShipment($order); //enter Your Message
					  $customerName = $order->getCustomerName();
					  //curlApicall : this method will return tru or fale.
					  $retunValue = $this->getHelper()->curlApiCall($templateid,$mobilenumbers,$customerName,$streetFull,$fax,$postcode,$city,$email,$incrementid,$countryname);
				  }
  			  }
		  }
	  }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }		
	}
	public function contactForm(Varien_Event_Observer $observer)
	{
	try{

			if($this->getHelper()->iscontactFormForAdmin())
			{
				$data = $observer->getData();
				$post = $data['controller_action']->getRequest()->getPost();
				if(sizeof($post))
				{
				
				//$mobilenumbers = $post['telephone']; 
				$mobilenumbers = $this->getHelper()->getAdminMobileNumber();
				$message = $this->getHelper()->getContactFormMessageForAdmin($post['name'],$post['email'],$post['telephone']); 				$customerName = $post['name'];	
				//curlApicall : this method will return tru or fale.
				$retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Contact Form");
				}
			}
	   }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }		
	}
	public function invoceRegister(Varien_Event_Observer $observer)
	{
	try{	
		if($this->getHelper()->isInvoceRegister())
		{	
			$order = $observer->getEvent()->getInvoice()->getOrder();
			$order = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
		  
		   //enter Mobile numbers comma seperated
		  $mobilenumbers = $order->getBillingAddress()->getTelephone();
		  $message = $this->getHelper()->getOrderStatusChagneMessage($order,'Processing','invoicegerate'); 
	  	  $customerName = $order->getCustomerName();		
		  //curlApicall : this method will return tru or fale.
		  $retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Order Invoice");
		}
	   }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }		
	}
	public function cancelOrder(Varien_Event_Observer $observer)
	{
	  try{	
			if($this->getHelper()->isCancelOrder())
			{	
			$order = $observer->getEvent()->getOrder();
			$order = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
			
				//enter Mobile numbers comma seperated
				$mobilenumbers = $order->getBillingAddress()->getTelephone(); 
				//enter Your Message
				$message = $this->getHelper()->getOrderStatusChagneMessage($order,'Canceled','cancelorder'); 
				
				$customerName = $order->getCustomerName();	
				//curlApicall : this method will return tru or fale.
				$retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Cancel Order");
					
					
			}
	  }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }		
	}
	public function completeOrder(Varien_Event_Observer $observer)
	{
	  try{	
		
		if($this->getHelper()->isCompleteOrder())
		{	
		
		$order = Mage::getModel('sales/order')->loadByIncrementId($observer->getPayment()->getOrder()->getIncrementId());
		
			//enter Mobile numbers comma seperated
			$mobilenumbers = $order->getBillingAddress()->getTelephone(); 
			//enter Your Message
		   	$message = $this->getHelper()->getOrderStatusChagneMessage($order,'Complete','completeorder');
			
			$customerName = $order->getCustomerName();	
			//curlApicall : this method will return tru or fale.
			$retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Order Complete");
		}
	  }catch(Exception $e) {
			 Mage::log($e->getMessage());
	  }	
	}
	
	public function creditMemoRefund(Varien_Event_Observer $observer)
	{
		$creditMemo = $observer->getEvent()->getCreditmemo();
		$order = $observer->getEvent()->getOrder();
		$order = Mage::getModel('sales/order')->load($creditMemo->getOrderId());
		if($this->getHelper()->isReturnOrder())
		{ 
		   $mobilenumbers = $order->getBillingAddress()->getTelephone(); 
		   $message = $this->getHelper()->getOrderStatusChagneMessage($order,'Return Order','returnorder');
		   $customerName = $order->getCustomerName();	
			//curlApicall : this method will return tru or fale.

		   $retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Return Order Request");
		}
		if($this->getHelper()->isReturnOrderForAdmin())
		{
			$mobilenumbers = $this->getHelper()->getAdminMobileNumber();	
		   $message = $this->getHelper()->getOrderStatusChagneMessageForAdmin($order,'Return Order','returnorder');
		   $customerName = $order->getCustomerName();	
			//curlApicall : this method will return tru or fale.

		   $retunValue = $this->getHelper()->curlApiCall($message,$mobilenumbers,$customerName,"Return Order");
		}
	}
	public function getHelper()
	{
        return Mage::helper('sms/Data');
    }
}