<?php
class Zozoconcepts_QuickView_Block_TopJs extends Mage_Page_Block_Html
{
    public function _prepareLayout()
    {
        if (!Mage::getStoreConfig('quickview/general/enableview')) return;
        $layout = $this->getLayout();
        $head = $layout->getBlock('head');
        if (is_object($head)) {
            $head->addJs('hybrid/jquery/jquery-1.11.3.min.js');
            $head->addJs('hybrid/jquery/jquery-noconflict.js');
            $head->addJs('hybrid/jquery/plugins/fancybox/js/jquery.fancybox.js');
            $head->addJs('varien/product.js');
            $head->addJs('varien/configurable.js');
            $head->addJs('calendar/calendar.js');
            $head->addJs('calendar/calendar-setup.js');
            $head->addItem('skin_js', 'js/bundle.js');
            $head->addItem('skin_js', 'quickview/js/quickview.js');
            //$head->addItem('skin_css', 'fancybox/css/jquery.fancybox.css');
            $head->addItem('js_css', 'calendar/calendar-win2k-1.css');
            $head->addItem('skin_css', 'quickview/css/quickview.css');            
        }
        $this->setTemplate('zozoconcepts/zozoconcepts_quickview/page/lablequickview.phtml');
    }
}
