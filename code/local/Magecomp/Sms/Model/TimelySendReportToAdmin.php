<?php

class Magecomp_Sms_Model_TimelySendReportToAdmin extends Mage_Core_Model_Config_Data
{
    /*
     * Set template
     */
    protected function _afterSave(){
		// Mage::getModel('core/config')->saveConfig('sms/sendtimelyreport/time', date("Y-m-d"));
		$groupId = $this->getGroupId();
		$cronStringPath = 'crontab/jobs/sms_' . $groupId . '/schedule/cron_expr';
        $cronModelPath = 'crontab/jobs/sms_' . $groupId . '/run/model';


        $value = $this->getData('groups/' . $groupId . '/fields/smssendreporttoadmin/value');
		

        Mage::getModel('core/config_data')
        ->load($cronStringPath, 'path')
            ->setValue($value)
            ->setPath($cronStringPath)
            ->save();
        Mage::getModel('core/config_data')
            ->load($cronModelPath, 'path')
            ->setValue((string) Mage::getConfig()->getNode($cronModelPath))
            ->setPath($cronModelPath)
            ->save();           

    }
	protected function _beforeSave()
    { 
		
		$Dbvalue = Mage::getStoreConfig('sms/registeredcustomer/smssendreporttoadmin');
        $NewValue = $this->getValue(); //get the value sent through post
		 if($Dbvalue != $NewValue)
		 {
			 Mage::getModel('core/config')->saveConfig('sms/sendtimelyreport/time', date("Y-m-d"));
		 }

		
		 
    }
    public function toOptionArray()
    {
        return array(
            array('value' => '1','label' => 'Daily'),
            array('value' => '7','label' => 'Weekly'),
            array('value' => '30','label' => 'Monthly'),			

        );
    }
 }