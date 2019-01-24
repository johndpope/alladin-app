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
 * Brand admin widget chooser
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */

class Zozoconcepts_Brands_Block_Adminhtml_Brand_Widget_Chooser
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * Block construction, prepare grid params
     * @access public
     * @param array $arguments Object data
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function __construct($arguments=array()){
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }
    /**
     * Prepare chooser element HTML
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author Zozoconcepts Hybrid
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element){
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('zozoconcepts_brands/adminhtml_brands_brand_widget/chooser', array('uniq_id' => $uniqId));
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
                ->setElement($element)
                ->setTranslationHelper($this->getTranslationHelper())
                ->setConfig($this->getConfig())
                ->setFieldsetId($this->getFieldsetId())
                ->setSourceUrl($sourceUrl)
                ->setUniqId($uniqId);
        if ($element->getValue()) {
            $brand = Mage::getModel('zozoconcepts_brands/brand')->load($element->getValue());
            if ($brand->getId()) {
                $chooser->setLabel($brand->getTitle());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }
    /**
     * Grid Row JS Callback
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getRowClickCallback(){
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var brandId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var brandTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(brandId);
                '.$chooserJsObject.'.setElementLabel(brandTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }
    /**
     * Prepare a static blocks collection
     * @access protected
     * @return Zozoconcepts_Brands_Block_Adminhtml_Brand_Widget_Chooser
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('zozoconcepts_brands/brand')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * Prepare columns for the a grid
     * @access protected
     * @return Zozoconcepts_Brands_Block_Adminhtml_Brand_Widget_Chooser
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareColumns(){
        $this->addColumn('chooser_id', array(
            'header'    => Mage::helper('zozoconcepts_brands')->__('Id'),
            'align'     => 'right',
            'index'     => 'entity_id',
            'type'        => 'number',
            'width'     => 50
        ));

        $this->addColumn('chooser_title', array(
            'header'=> Mage::helper('zozoconcepts_brands')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('zozoconcepts_brands')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
            ));
        }
        $this->addColumn('chooser_status', array(
            'header'=> Mage::helper('zozoconcepts_brands')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'options'   => array(
                0 => Mage::helper('zozoconcepts_brands')->__('Disabled'),
                1 => Mage::helper('zozoconcepts_brands')->__('Enabled')
            ),
        ));
        return parent::_prepareColumns();
    }
    /**
     * get url for grid
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getGridUrl(){
        return $this->getUrl('adminhtml/brands_brand_widget/chooser', array('_current' => true));
    }
    /**
     * after collection load
     * @access protected
     * @return Zozoconcepts_Brands_Block_Adminhtml_Brand_Widget_Chooser
     * @author Zozoconcepts Hybrid
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
