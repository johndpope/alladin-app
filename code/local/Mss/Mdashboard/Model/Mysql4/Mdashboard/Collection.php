<?php
class Mss_Mdashboard_Model_Mysql4_Mdashboard_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct(){
		parent::_construct();
		$this->_init("mdashboard/mdashboard");
	}
}
	 