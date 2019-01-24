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
 * Blog customer comments list
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Blog_Customer_Comment_View
    extends Mage_Customer_Block_Account_Dashboard {
    /**
     * get current comment
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog_Comment
     * @author Zozoconcepts Hybrid
     */
    public function getComment() {
        return Mage::registry('current_comment');
    }
    /**
     * get current blog
     * @access public
     * @return Zozoconcepts_Blog_Model_Blog
     * @author Zozoconcepts Hybrid
     */
    public function getBlog() {
        return Mage::registry('current_blog');
    }
}
