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
 * Blog comments admin grid block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Blog_Comment_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function __construct(){
        parent::__construct();
        $this->setId('blogCommentGrid');
        $this->setDefaultSort('ct_comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Comment_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareCollection(){
        $collection = Mage::getResourceModel('zozoconcepts_blog/blog_comment_blog_collection');
        $collection->addStoreData();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Comment_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareColumns(){
        $this->addColumn('ct_comment_id', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Id'),
            'index'         => 'ct_comment_id',
            'type'          => 'number',
            'filter_index'  => 'ct.comment_id',
        ));
        $this->addColumn('title', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Title'),
            'index'         => 'title',
            'filter_index'  => 'main_table.title',
        ));
        $this->addColumn('ct_title', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Comment Title'),
            'index'         => 'ct_title',
            'filter_index'  => 'ct.title',
        ));
        $this->addColumn('ct_name', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Poster name'),
            'index'         => 'ct_name',
            'filter_index'  => 'ct.name',
        ));
        $this->addColumn('ct_email', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Poster email'),
            'index'         => 'ct_email',
            'filter_index'  => 'ct.email',
        ));
        $this->addColumn('ct_status', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Status'),
            'index'         => 'ct_status',
            'filter_index'  => 'ct.status',
            'type'          => 'options',
            'options'       => array(
                    Zozoconcepts_Blog_Model_Blog_Comment::STATUS_PENDING  => Mage::helper('zozoconcepts_blog')->__('Pending'),
                    Zozoconcepts_Blog_Model_Blog_Comment::STATUS_APPROVED => Mage::helper('zozoconcepts_blog')->__('Approved'),
                    Zozoconcepts_Blog_Model_Blog_Comment::STATUS_REJECTED => Mage::helper('zozoconcepts_blog')->__('Rejected'),
            )
        ));
        $this->addColumn('ct_created_at', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Created at'),
            'index'         => 'ct_created_at',
            'width'         => '120px',
            'type'          => 'datetime',
            'filter_index'  => 'ct.created_at',
        ));
        $this->addColumn('ct_updated_at', array(
            'header'        => Mage::helper('zozoconcepts_blog')->__('Updated at'),
            'index'         => 'ct_updated_at',
            'width'         => '120px',
            'type'          => 'datetime',
            'filter_index'  => 'ct.updated_at',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('stores', array(
                'header'=> Mage::helper('zozoconcepts_blog')->__('Store Views'),
                'index' => 'stores',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('zozoconcepts_blog')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getCtCommentId',
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
        $this->setMassactionIdField('ct_comment_id');
        $this->setMassactionIdFilter('ct.comment_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('comment');
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
                            Zozoconcepts_Blog_Model_Blog_Comment::STATUS_PENDING  => Mage::helper('zozoconcepts_blog')->__('Pending'),
                            Zozoconcepts_Blog_Model_Blog_Comment::STATUS_APPROVED => Mage::helper('zozoconcepts_blog')->__('Approved'),
                            Zozoconcepts_Blog_Model_Blog_Comment::STATUS_REJECTED => Mage::helper('zozoconcepts_blog')->__('Rejected'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Zozoconcepts_Blog_Model_Blog_Comment
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getCtCommentId()));
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
     * filter store column
     * @access protected
     * @param Zozoconcepts_Blog_Model_Resource_Blog_Comment_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Comment_Grid
     * @author Zozoconcepts Hybrid
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->setStoreFilter($value);
        return $this;
    }
}
