<?php

class FME_Banners_Block_Adminhtml_Banners_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('banners_form', array('legend'=>Mage::helper('banners')->__('Item information')));
     
	  $object = Mage::getModel('banners/banners')->load( $this->getRequest()->getParam('id') );
	  $imgPath = Mage::getBaseUrl('media')."thumb/".$object['bannerimage'];
	 
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('banners')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('bannerimage', 'image', array(
          'label'     => Mage::helper('banners')->__('Banner Image'),
          'required'  => false,
          'name'      => 'bannerimage',
	  ));
	  
	 
	  
	  $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('banners')->__('Link'),
          'required'  => false,
          'name'      => 'link',
	  ));
	  
	 $fieldset->addField('target', 'select', array(
          'label'     => Mage::helper('banners')->__('Target'),
          'name'      => 'target',
          'values'    => array(
              array(
                  'value'     => '_blank',
                  'label'     => Mage::helper('banners')->__('Open in new window'),
              ),

              array(
                  'value'     => '_self',
                  'label'     => Mage::helper('banners')->__('Open in same window'),
              ),
          ),
      ));
	 
	  $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('banners')->__('Sort Order'),
          'required'  => false,
          'name'      => 'sort_order',
      ));
 $fieldset->addField('url_type', 'select',
                         array(
                            'label' => Mage::helper('bannersliderapp')->__('Link To'),
                            'after_element_html' => '<small>Add catagory link section</small>',
                            'values'    => array(

                              array(
                                  'value'     => 'Category',
                                  'label'     => Mage::helper('core')->__('Category'),
                              ),
                              array(
                                  'value'     => 'Product',
                                  'label'     => Mage::helper('core')->__('Product'),
                              ),
                          ),
                          
                          'name' => 'url_type',
                      ));
/*custom field*/
           $fieldset->addField('check_type', 'select',
                         array(
                          'label' => Mage::helper('bannersliderapp')->__('Display on page'),
                           'values'    => array(
                              array(
                                  'value'     => 'home_view',
                                  'label'     => Mage::helper('core')->__('Home View'),
                              ),
                              array(
                                  'value'     => 'category_view',
                                  'label'     => Mage::helper('core')->__('Category View'),
                              ),
                          ),
                          
                          'name' => 'check_type',
                      ));
/*custom field*/

        $fieldset->addField('product_id', 'text',
                  array(
                      'label' => Mage::helper('bannersliderapp')->__('Product Id to Display'),
                     
                      
                      'name' => 'product_id',
               ));
       $fieldset->addField('category_id', 'text',
                array(
                    'label' => Mage::helper('bannersliderapp')->__('Category Id to Display'),
                    
                    
                    'name' => 'category_id',
             ));
    
	 
	 $fieldset->addField('textblend', 'select', array(
          'label'     => Mage::helper('banners')->__('Text Blend ?'),
          'name'      => 'textblend',
          'values'    => array(
              array(
                  'value'     => 'yes',
                  'label'     => Mage::helper('banners')->__('Yes'),
              ),

              array(
                  'value'     => 'no',
                  'label'     => Mage::helper('banners')->__('No'),
              ),
          ),
      ));
	
	
	
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('banners')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('banners')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('banners')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('banners')->__('Content'),
          'title'     => Mage::helper('banners')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getBannersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBannersData());
          Mage::getSingleton('adminhtml/session')->setBannersData(null);
      } elseif ( Mage::registry('banners_data') ) {
          $form->setValues(Mage::registry('banners_data')->getData());
      }
      return parent::_prepareForm();
  }
}