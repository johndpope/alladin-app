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

namespace Zozothemes\ShopByBrand\Controller\Adminhtml\Items;

use Zozothemes\ShopByBrand\Model\Items;

class NewAction extends \Zozothemes\ShopByBrand\Controller\Adminhtml\Items
{
	/**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $_attrOptionCollectionFactory;
    
    protected  $registry;
    
 //    public function __construct(
//        \Magento\Backend\App\Action\Context $context,
//        \Magento\Framework\Registry $registry,
//        \Magento\Backend\Model\View\Result\ForwardFactory $ForwardFactory , 
//        \Magento\Framework\View\Result\PageFactory $PF,
//     	\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
//         array $data = []
//     ) {
//         parent::__construct($context, $data);
//         $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
//         
//     }

    public function execute()
    {
//         $this->_forward('edit');
		 $model = $this->_objectManager->create(
            'Magento\Catalog\Model\ResourceModel\Eav\Attribute'
        )->setEntityTypeId(
            \Magento\Catalog\Model\Product::ENTITY
        );

       $model->loadByCode(\Magento\Catalog\Model\Product::ENTITY,'manufacturer');
        
//         echo "<pre>";
//         var_dump(get_class_methods($model));
		foreach($model->getOptions() as $option){
			//var_dump($option->debug());
			
			$item = $this->_objectManager->create('Zozothemes\ShopByBrand\Model\Items');
			if(!empty($option->getValue())){			
				$id = (int)$option->getValue();
				if ($id) {
                    $item->load($id);
                    if ($id != $item->getId()) {
//                         throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
				
				$data = array(
								'name' => $option->getLabel(),
								'attribute_id' => $option->getValue(),
								'is_active' => 1,
							);			
				$item->setData($data);
// 				var_dump($item->debug());
		try{
				$item->save();
		}
		catch(\Exception $e){
		
		}
// 				var_dump($item->debug());
// 				die;
				
			}
		}
		$this->messageManager->addSuccess(__('All Brands Re-Synced'));
		$this->_redirect('zozothemes_shopbybrand/*/');
	
    }
}
