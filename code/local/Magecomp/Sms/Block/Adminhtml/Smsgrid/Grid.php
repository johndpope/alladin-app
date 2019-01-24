<?php
/**
 * MageComp
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the magecomp.com license that is
 * available through the world-wide-web at this URL:
 * http://magecomp.com/license-agreement/
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @copyright   Copyright (c) 2016 Magegiant (http://magecomp.com/)
 * @license     http://magecomp.com/license-agreement/
 */

/**
 * SMS Block Smsgrid Grid.php file
 *
 * @category    MageComp
 * @package     Magecomp_Smscountry
 * @author      MageComp Developer
 */
class Magecomp_Sms_Block_Adminhtml_Smsgrid_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
	  parent::__construct();
      $this->setId('testGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('sms/smsreport')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
          'header'    => Mage::helper('sms')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));
      $this->addColumn('jobno', array(
          'header'    => Mage::helper('sms')->__('Job No.'),
          'align'     =>'left',
          'index'     => 'jobno',
      ));
	  $this->addColumn('message_text', array(
          'header'    => Mage::helper('sms')->__('SMS Text'),
          'align'     =>'left',
          'index'     => 'message_text',
      ));
	   $this->addColumn('message_sent_time', array(
          'header'    => Mage::helper('sms')->__('SMS Sent time'),
          'align'     =>'left',
          'index'     => 'message_sent_time',
      ));
	   $this->addColumn('message_count', array(
          'header'    => Mage::helper('sms')->__('SMS Count'),
          'align'     =>'left',
          'index'     => 'message_count',
      ));
	   $this->addColumn('recipient_name', array(
          'header'    => Mage::helper('sms')->__('Recipient'),
          'align'     =>'left',
          'index'     => 'recipient_name',
      ));
	   $this->addColumn('recipient_number', array(
          'header'    => Mage::helper('sms')->__('Recipient Mobile No.'),
          'align'     =>'left',
          'index'     => 'recipient_number',
      ));
	   $this->addColumn('message_type', array(
          'header'    => Mage::helper('sms')->__('SMS Type'),
          'align'     =>'left',
          'index'     => 'message_type',
      ));
	   $this->addColumn('message_status', array(
          'header'    => Mage::helper('sms')->__('SMS Status'),
          'align'     =>'left',
          'index'     => 'message_status',
		  'renderer'  => 'Magecomp_Sms_Block_Adminhtml_Renderer_Status',
      ));

      return parent::_prepareColumns();
  }
 // protected function _prepareMassaction()
//  {
//        $this->setMassactionIdField('id');
//        $this->getMassactionBlock()->setFormFieldName('sms');
//        $this->getMassactionBlock()->addItem('delete', array(
//             'label'    => Mage::helper('sms')->__('Delete'),
//             'url'      => $this->getUrl('*/*/massDelete'),
//             'confirm'  => Mage::helper('sms')->__('Are you sure?')
//        ));
//
//        return $this;
//    }
}