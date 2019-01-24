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
 * SMS Block Smsgrid
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Block_Adminhtml_Smsgrid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
   public function __construct()
  {
    $this->_controller = 'adminhtml_smsgrid';
    $this->_blockGroup = 'sms';
	$this->_headerText = Mage::helper('sms')->__('Report');
	parent::__construct();
	$this->_removeButton('add');
  }
}