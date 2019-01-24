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
 * Category widget block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Category_Widget_View
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface {
    protected $_htmlTemplate = 'zozoconcepts_blog/category/widget/view.phtml';
    /**
     * Prepare a for widget
     * @access protected
     * @return Zozoconcepts_Blog_Block_Category_Widget_View
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $categoryId = $this->getData('category_id');
        if ($categoryId) {
            $category = Mage::getModel('zozoconcepts_blog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);
            if ($category->getStatus()) {
                $this->setCurrentCategory($category);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
