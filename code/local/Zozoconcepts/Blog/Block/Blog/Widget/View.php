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
 * Blog widget block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_Widget_View
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface {
    protected $_htmlTemplate = 'zozoconcepts_blog/blog/widget/view.phtml';
    /**
     * Prepare a for widget
     * @access protected
     * @return Zozoconcepts_Blog_Block_Blog_Widget_View
     * @author Zozoconcepts Hybrid
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $blogId = $this->getData('blog_id');
        if ($blogId) {
            $blog = Mage::getModel('zozoconcepts_blog/blog')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blogId);
            if ($blog->getStatus()) {
                $this->setCurrentBlog($blog);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
