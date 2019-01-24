<?php

class Boonagel_Direct_Model_Observer {

    
    //admin logs in sales view page
    public function directAdminLogs(Varien_Event_Observer $observer){
        $block = $observer->getBlock();
        if(($block->getNameInLayout() == 'order_info') && ($child = $block->getChild('directorderlog'))){
            $transport = $observer->getTransport();
            if($transport){
                $html = $transport->getHtml();
                $html .= $child->toHtml();
                $transport->setHtml($html);
            }
        }
        
    }

}
