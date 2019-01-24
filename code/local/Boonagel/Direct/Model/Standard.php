<?php
/**
  * Name:		Direct 
  * Type:		Payment Controller
 */
 
class Boonagel_Direct_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'direct';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('directpayonline/payments/pay', array('_secure' => true));
	}
}