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
 * Blog comments resource model
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Model_Resource_Blog_Comment
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * constructor
     * @access public
     * @author Zozoconcepts Hybrid
     */
    public function _construct(){
        $this->_init('zozoconcepts_blog/blog_comment', 'comment_id');
    }

    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $blogId
     * @return array
     * @author Zozoconcepts Hybrid
     */
    public function lookupStoreIds($commentId){
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('zozoconcepts_blog/blog_comment_store'), 'store_id')
            ->where('comment_id = ?',(int)$commentId);
        return $adapter->fetchCol($select);
    }
    /**
     * Perform operations after object load
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Zozoconcepts_Blog_Model_Resource_Blog_Comment
     * @author Zozoconcepts Hybrid
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object){
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Zozoconcepts_Blog_Model_Blog_Comment $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('blog_blog_comment_store' => $this->getTable('zozoconcepts_blog/blog_comment_store')),
                $this->getMainTable() . '.comment_id = blog_blog_comment_store.comment_id',
                array()
            )
            ->where('blog_blog_comment_store.store_id IN (?)', $storeIds)
            ->order('blog_blog_comment_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }
    /**
     * Assign blog comments to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Zozoconcepts_Blog_Model_Resource_Blog_Comment
     * @author Zozoconcepts Hybrid
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object){
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('zozoconcepts_blog/blog_comment_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'comment_id = ?'  => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'comment_id'  => (int) $object->getId(),
                    'store_id'    => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
