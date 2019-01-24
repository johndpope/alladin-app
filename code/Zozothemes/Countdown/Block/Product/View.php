<?php
/** 
  * Zozothemes.
  * 
  * NOTICE OF LICENSE
  * 
  * This source file is subject to the Zozothemes.com license that is
  * available through the world-wide-web at this URL:
  * http://www.zozothemes.com/license-agreement.html
  * 
  * DISCLAIMER
  * 
  * Do not edit or add to this file if you wish to upgrade this extension to newer
  * version in the future.
  * 
  * @category   Zozothemes
  * @package    Zozothemes_Countdown
  * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
  * @license    http://www.zozothemes.com/LICENSE-1.0.html
  */
namespace Zozothemes\Countdown\Block\Product;
/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Catalog\Block\Product\View
{
    
    public function isCountdownEnabled()
    {
        return $this->getProduct()->getData('countdown_enabled');
    }
    public function getTile()
    {
       return $this->_scopeConfig->getValue('countdown/general/title');
    }

    public function getCountdownStartDate(){
        return $this->getProduct()->getSpecialFromDate();
    }

    public function getCountdownEndDate(){
        return  $this->getProduct()->getSpecialToDate();
    }

    public function getPriceCountDown(){
        if($this->_scopeConfig->getValue('countdown/general/enabled')){
            $currentDate =  date('d-m-Y');
            $todate      =  $this->getProduct()->getSpecialToDate();
            $fromdate    =  $this->getProduct()->getSpecialFromDate();
            if($this->getProduct()->getSpecialPrice() != 0 || $this->getProduct()->getSpecialPrice()) {
                if($this->getProduct()->getSpecialToDate() != null) {
                    if(strtotime($todate) >= strtotime($currentDate) && strtotime($fromdate) <= strtotime($currentDate)){
                        return true;
                    }   
                }
            }
        }
        return false;
    }
}