<?php

class Cminds_Rma_Block_Adminhtml_Rma_Edit_Tab_Note extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $fieldset = $form->addFieldset('add_comment', array(
            'legend' => $this->__('Add Note'),
        ));

        $fieldset->addField('note', 'textarea', array(
            'name' => 'note',
            'label' => 'Note',
            'required' => false,
        ));

        $fieldset->addField('notify_customer', 'checkbox', array(
            'name' => 'notify_customer',
            'label' => 'Notify customer',
            'required' => false,
        ));

        $comments = Mage::registry('rma_data')->getAllStatusHistory()->setOrder('entity_id', 'desc');
        
        foreach($comments AS $i => $comment) {
            $fieldset = $form->addFieldset('note_fieldset_' . $i, array(
            ));
            
            $fieldset->addField('note_' . $i, 'note', array(
                'label' => $this->__('Comment'),
                'text' => $comment->getCommentBody(),
            ));
            
            if($comment->getStatusId() != $comment->getOldStatusId()) {
                $fieldset->addField('note_status' . $i, 'note', array(
                    'text' => $this->_renderStatus($comment->getStatusId()),
                ));
            }
            
            $fieldset->addField('note_notified' . $i, 'note', array(
                'label' => $this->__('Customer Notified'),
                'text' => ($comment->getIsCustomerNotified() == 1) ? $this->__('Yes') : $this->__('No'),
            ));
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    private function _renderStatus($status_id) {
        $status = Mage::getModel('cminds_rma/rma_status')->load($status_id);
        
        return $status->getName();
    }
    
    

}