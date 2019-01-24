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
 * SMS Adminhtml Index Controller
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Adminhtml_ChecksmsController extends Mage_Adminhtml_Controller_Action
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
    public function checkAction()
    {
		$tab = $this->getRequest()->getParam('mytab');
		$section = $this->getRequest()->getParam('mysection');
		Mage::helper('sms/Data')->testSmsApi($tab,$section);
    }
	public function checkPreviewAction()
    {
		$tab = $this->getRequest()->getParam('mytab');
		$section = $this->getRequest()->getParam('mysection');
		echo Mage::helper('sms/Data')->previewTestSmsApi($tab,$section);
    }
	public function gridAction()
    {
		Mage::helper('sms/Data')->testSmsApi();
        $result = "Test";
        Mage::app()->getResponse()->setBody($result);
    }
}