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
 * Brand admin edit form
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Brands
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Brands_Block_Adminhtml_Brand_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Zozoconcepts Hybrid
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'zozoconcepts_brands';
        $this->_controller = 'adminhtml_brand';
        $this->_updateButton('save', 'label', Mage::helper('zozoconcepts_brands')->__('Save Brand'));
        $this->_updateButton('delete', 'label', Mage::helper('zozoconcepts_brands')->__('Delete Brand'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('zozoconcepts_brands')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Zozoconcepts Hybrid
     */
    public function getHeaderText(){
        if( Mage::registry('current_brand') && Mage::registry('current_brand')->getId() ) {
            return Mage::helper('zozoconcepts_brands')->__("Edit Brand '%s'", $this->escapeHtml(Mage::registry('current_brand')->getTitle()));
        }
        else {
            return Mage::helper('zozoconcepts_brands')->__('Add Brand');
        }
    }
}
