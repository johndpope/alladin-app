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
 * SMS Smsgrid Controller
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Adminhtml_SmsgridController extends Mage_Adminhtml_Controller_Action
{
/**
     * Return some checking result
     *
     * @return void
     */
	  protected function _isAllowed()
    {
        return true;
    } 
   	protected function _initAction()
    {
        $this->loadLayout()
            // Make the active menu match the menu config nodes (without 'children' inbetween)
            ->_setActiveMenu('sms/sms_smsgrid')
            ->_title($this->__('SMS Reports'))
            ->_addBreadcrumb($this->__('sms'), $this->__('SMS'))
            ->_addBreadcrumb($this->__('sms'), $this->__('Sms'));
        return $this;
    }
	public function indexAction()
    {
         $this->_initAction()->renderLayout();
    }
}