<?php
/**
 * Zozoconcepts_Blog extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Blog
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Blog admin grid block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function __construct(){
        parent::__construct();
        $this->setId('blogGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('zozoconcepts_blog/blog')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Category'),
            'index'     => 'category_id',
            'type'      => 'options',
            'options'   => Mage::getResourceModel('zozoconcepts_blog/category_collection')->toOptionHash(),
        ));
        $this->addColumn('title', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('zozoconcepts_blog')->__('Enabled'),
                '0' => Mage::helper('zozoconcepts_blog')->__('Disabled'),
            )
        ));
        $this->addColumn('url_key', array(
            'header' => Mage::helper('zozoconcepts_blog')->__('URL key'),
            'index'  => 'url_key',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('zozoconcepts_blog')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
			
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('zozoconcepts_blog')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('zozoconcepts_blog')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('zozoconcepts_blog')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('zozoconcepts_blog')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('zozoconcepts_blog')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('zozoconcepts_blog')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('blog');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('zozoconcepts_blog')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('zozoconcepts_blog')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('zozoconcepts_blog')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('zozoconcepts_blog')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('zozoconcepts_blog')->__('Enabled'),
                                '0' => Mage::helper('zozoconcepts_blog')->__('Disabled'),
                        )
                )
            )
        ));
        $this->getMassactionBlock()->addItem('show_onslide', array(
            'label'=> Mage::helper('zozoconcepts_blog')->__('Change Show on Slider'),
            'url'  => $this->getUrl('*/*/massShowOnslide', array('_current'=>true)),
            'additional' => array(
                'flag_show_onslide' => array(
                        'name' => 'flag_show_onslide',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('zozoconcepts_blog')->__('Show on Slider'),
                        'values' => array(
                                '1' => Mage::helper('zozoconcepts_blog')->__('Yes'),
                                '0' => Mage::helper('zozoconcepts_blog')->__('No'),
                            )

                )
            )
        ));
        $values = Mage::getResourceModel('zozoconcepts_blog/category_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem('category_id', array(
            'label'=> Mage::helper('zozoconcepts_blog')->__('Change Category'),
            'url'  => $this->getUrl('*/*/massCategoryId', array('_current'=>true)),
            'additional' => array(
                'flag_category_id' => array(
                        'name' => 'flag_category_id',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('zozoconcepts_blog')->__('Category'),
                        'values' => $values
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Zozoconcepts_Blog_Model_Blog
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Zozoconcepts_Blog_Model_Resource_Blog_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
