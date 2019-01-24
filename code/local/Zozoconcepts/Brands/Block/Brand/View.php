<?php
/**
 * Zozoconcepts_Brands extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Brands
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Brand view block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Brand_View
    extends Mage_Core_Block_Template {
    /**
     * get the current brand
     * @access public
     * @return mixed (Zozoconcepts_Brands_Model_Brand|null)
     * @author Zozoconcepts Hybrid
     */
    public function getCurrentBrand(){
        return Mage::registry('current_brand');
    }
}
