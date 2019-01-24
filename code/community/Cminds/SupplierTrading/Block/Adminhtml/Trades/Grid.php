<?php
class Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('trades_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {

        $this->setCollection($this->getCustomCollection());

        return parent::_prepareCollection();
    }

    public function getCustomCollection() {
        $eavAttribute   = new Mage_Eav_Model_Mysql4_Entity_Attribute();
        $code           = $eavAttribute->getIdByCode('catalog_product', 'name');
        $codePrice      = $eavAttribute->getIdByCode('catalog_product', 'price');
        $codeFirstName  = $eavAttribute->getIdByCode('customer', 'firstname');
        $codeLastName   = $eavAttribute->getIdByCode('customer', 'lastname');

        $collection = Mage::getModel('suppliertrading/trades')
            ->getCollection();

        $collection->getSelect()
            ->joinInner(array('p' => Mage::getSingleton('core/resource')
                ->getTableName('catalog_product_entity_varchar')),
                'p.entity_id = main_table.product_id AND p.attribute_id = ' . $code, array('value as name')
            )
            ->joinInner(array('pp' => Mage::getSingleton('core/resource')
                ->getTableName('catalog_product_entity')), 'pp.entity_id = main_table.product_id', array('sku','entity_id as product_id') )
            ->joinInner(array('ppp' => Mage::getSingleton('core/resource')
                ->getTableName('catalog_product_entity_decimal')),
                'ppp.entity_id = main_table.product_id AND ppp.attribute_id = ' . $codePrice, array('value as product_price')
            )
            ->joinInner(array('c' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity')), 'c.entity_id = main_table.customer_id', array('entity_id as customer_id')
            )
            ->joinInner(array('cc' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity_varchar')),
                'cc.entity_id = main_table.customer_id AND cc.attribute_id = ' . $codeFirstName, array('value as customer_first_name')
            )
            ->joinInner(array('ccc' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity_varchar')),
                'ccc.entity_id = main_table.customer_id AND ccc.attribute_id = ' . $codeLastName, array('value as customer_last_name')
            )
            ->joinInner(array('s' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity')), 's.entity_id = main_table.supplier_id', array('entity_id as supplier_id')
            )
            ->joinInner(array('ss' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity_varchar')),
                'ss.entity_id = main_table.supplier_id AND ss.attribute_id = ' . $codeFirstName, array('value as supplier_first_name')
            )
            ->joinInner(array('sss' => Mage::getSingleton('core/resource')
                ->getTableName('customer_entity_varchar')),
                'sss.entity_id = main_table.supplier_id AND sss.attribute_id = ' . $codeLastName, array('value as supplier_last_name')
            )
            ->group('main_table.id')
        ;


        return $collection;
    }

    protected function _prepareColumns()
    {
        $currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();

        // Add the columns that should appear in the grid
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'width' => '50px',
                'index' => 'id'
            )
        );

        $this->addColumn('name',
            array(
                'header'=> $this->__('Product Name (SKU)'),
                'index' => 'name',
                'renderer'  => 'Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Product',
                'filter_condition_callback' => array($this, 'filterNameCallback'),
            )
        );

        $this->addColumn('customer',
            array(
                'header'=> $this->__('Customer'),
                'index' => 'email',
                'renderer'  => 'Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Customer',
                'filter_condition_callback' => array($this, 'filterCustomerCallback'),
            )
        );
        $this->addColumn('supplier',
            array(
                'header'=> $this->__('Supplier'),
                'index' => 'email',
                'renderer'  => 'Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Supplier',
                'filter_condition_callback' => array($this, 'filterSupplierCallback'),
            )
        );

        $this->addColumn('product_price',
            array(
                'header'=> $this->__('Original Product Price'),
                'index' => 'product_price',
                'type'      => 'price',
                'currency_code' => $currency_code
            )
        );

        $this->addColumn('price',
            array(
                'header'=> $this->__('Suggested Price'),
                'index' => 'price',
                'type'      => 'price',
                'currency_code' => $currency_code
            )
        );

        $this->addColumn('status',
            array(
                'header'=> $this->__('Status'),
                'index' => 'status',
                'type'  => 'options',
                'renderer'  => 'Cminds_SupplierTrading_Block_Adminhtml_Trades_Grid_Renderer_Status',
                'options' => array(
                    '0' => $this->__('Waiting for action'),
                    '1' => $this->__('Rejected'),
                    '2' => $this->__('Accepted'),
                )
            )
        );

        return parent::_prepareColumns();
    }

    public function filterNameCallback($collection, $column)
    {
        if(!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $productsIds = $this->getProductIdsBySearch($value);

        if(count($productsIds)>0)
            $collection->getSelect()->where('main_table.product_id in ('.implode(',',$productsIds).')');
        else
            $collection->getSelect()->where('1=0');


        return $this;
    }

    function getProductIdsBySearch($searchstring, $storeId = '') {
        $ids = array();

        $product_collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('name', array('like' => '%'.$searchstring.'%'))
            ->load();

        foreach ($product_collection as $product) {
            $ids[] = $product->getId();
        }

        return $ids;
    }

    public function filterCustomerCallback($collection, $column)
    {
        if(!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $customersIds = $this->getCustomersIdsBySearch($value);

        if(count($customersIds)>0)
            $collection->getSelect()->where('main_table.customer_id in ('.implode(',',$customersIds).')');
        else
            $collection->getSelect()->where('1=0');


        return $this;
    }

    public function filterSupplierCallback($collection, $column)
    {
        if(!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $customersIds = $this->getCustomersIdsBySearch($value);

        if(count($customersIds)>0)
            $collection->getSelect()->where('main_table.supplier_id in ('.implode(',',$customersIds).')');
        else
            $collection->getSelect()->where('1=0');


        return $this;
    }

    function getCustomersIdsBySearch($searchstring, $storeId = '') {
        $ids = array();

        $explode = explode(' ',$searchstring);

        if(count($explode) >= 2) {

            $customer_collection = Mage::getResourceModel('customer/customer_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('firstname', array('like' => '%'.$explode[0].'%'))
                ->addAttributeToFilter('lastname',  array('like' => '%'.$explode[1].'%'))
                ->load();
        } else {
            $customer_collection = Mage::getResourceModel('customer/customer_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter(
                    array(
                        array('attribute'=> 'firstname','like' => '%'.$explode[0].'%'),
                        array('attribute'=> 'lastname','like'  => '%'.$explode[1].'%')
                    )
                )
                ->load();
        }


        foreach ($customer_collection as $customer) {
            $ids[] = $customer->getId();
        }

        return $ids;
    }

}