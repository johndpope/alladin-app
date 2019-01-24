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
 * meta information tab
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Blog
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Blog_Block_Adminhtml_Blog_Edit_Tab_Meta
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Zozoconcepts_Blog_Block_Adminhtml_Blog_Edit_Tab_Meta
     * @author Zozoconcepts Hybrid
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('blog');
        $this->setForm($form);
        $fieldset = $form->addFieldset('blog_meta_form', array('legend'=>Mage::helper('zozoconcepts_blog')->__('Meta information')));
        $fieldset->addField('meta_title', 'text', array(
            'label' => Mage::helper('zozoconcepts_blog')->__('Meta-title'),
            'name'  => 'meta_title',
        ));
        $fieldset->addField('meta_description', 'textarea', array(
            'name'      => 'meta_description',
            'label'     => Mage::helper('zozoconcepts_blog')->__('Meta-description'),
          ));
          $fieldset->addField('meta_keywords', 'textarea', array(
            'name'      => 'meta_keywords',
            'label'     => Mage::helper('zozoconcepts_blog')->__('Meta-keywords'),
          ));
          $form->addValues(Mage::registry('current_blog')->getData());
        return parent::_prepareForm();
    }
}
