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
 * SMS Renderer Status.php file
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Block_Adminhtml_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
	 
	public function render(Varien_Object $row)
	{
	$value =  $row->getData($this->getColumn()->getIndex());
		if($value == 0)
		{
			return 'Message In Queue';
		}
		if($value == 1)
		{
			return 'Submitted To Carrier';
		}
		if($value == 2)
		{
			return 'Un Delivered';
		}
		if($value == 3)
		{
			return 'Delivered';
		}
		if($value == 4)
		{
			return 'Expired';
		}
		if($value == 8)
		{
			return 'Rejected';
		}
		if($value == 9)
		{
			return 'Message Sent';
		}
		if($value == 10)
		{
			return 'Opted Out Mobile Number';
		}
		if($value == 11)
		{
			return 'Invalid Mobile Number';
		}
	 
	}
 
}
