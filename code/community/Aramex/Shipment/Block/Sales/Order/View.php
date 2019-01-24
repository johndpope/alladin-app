<?php
	class Aramex_Shipment_Block_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View
	{
		function __construct()
		{
			$itemscount 	= 0;
			$totalWeight 	= 0;
			$_order = Mage::getModel('sales/order')->load($this->getRequest()->getParam('order_id'));
			
			$has_armex_shipment =  false;
			 $collection = Mage::getModel('sales/order_shipment_track')->getCollection()->addFieldToFilter('carrier_code','aramex')->addFieldToFilter('order_id',$_order->getId());
			 
			 
			 
			 if($collection){
				   if($collection->count()>0){
					 $has_armex_shipment =  true;  
				   }
			 }
				
			 
			 
			 $shipments = Mage::getResourceModel('sales/order_shipment_collection')
				->addAttributeToSelect('*')	
				->addFieldToFilter("order_id",$_order->getId())->join("sales/shipment_comment",'main_table.entity_id=parent_id','comment')->addFieldToFilter('comment', array('like'=>"%{$_order->getIncrementId()}%"))->load();
				
				$aramex_return_button = false;
								
				if($shipments->count()){
					foreach($shipments as $key=>$comment){
						if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
							$awbno=substr($comment->getComment(),0, strpos($comment->getComment(),"- Order No")); 
						}
						else{				
							$awbno=strstr($comment->getComment(),"- Order No",true);
						}
						$awbno=trim($awbno,"AWB No.");					
						break;
					}
					if((int) $awbno)
						$aramex_return_button = true;
				}
			 
			 
			 
			
			if($_order->canShip()){
			  $this->_addButton('create_aramex_shipment', array(
							'label'     => Mage::helper('Sales')->__('Prepare Aramex Shipment'),
							'onclick'   => 'aramexpop(1)',
							'class'     => 'go'
						), 0, 300, 'header', 'header');
			}
			if($has_armex_shipment){
				 $this->_addButton('return_aramex_shipment', array(
							'label'     => Mage::helper('Sales')->__('Return Aramex Shipment'),
							'onclick'   => 'aramexreturnpop()',
							'class'     => 'go'
				  ), 0, 400, 'header', 'header');
			}				
			
				
				if($has_armex_shipment){			
					$this->_addButton('print_aramex_label', array(
							'label'     => Mage::helper('Sales')->__('Aramex Print Label'),
							'onclick'   => "myObj.printLabel()",
							'class'     => 'go'
						), 0, 600, 'header', 'header');
				}
				
				parent::__construct();
		}
	}
?>