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
  * @package    Zozothemes_ShopByBrand
  * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
  * @license    http://www.zozothemes.com/LICENSE-1.0.html
  */
namespace Zozothemes\ShopByBrand\Block;
class Sidebar extends \Magento\Framework\View\Element\Template
{

    protected $_brandFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
         \Zozothemes\ShopByBrand\Model\BrandFactory $brandFactory
    ) 
    {
    	 $this->_brandFactory = $brandFactory;
        parent::__construct($context);
    }
    
    
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getBrands(){
		$collection = $this->_brandFactory->create()->getCollection();
		$collection->addFieldToFilter('is_active' , \Zozothemes\ShopByBrand\Model\Status::STATUS_ENABLED);
		$collection->setOrder('name' , 'ASC');
		$charBarndArray = array();
		foreach($collection as $brand)
		{	
			$name = trim($brand->getName());
			$charBarndArray[strtoupper($name[0])][] = $brand;
		}
		
    	return $charBarndArray;
    }
     
    
}