<?php

class Magecomp_Sms_Model_Language 
{
    /*
     * Set template
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'N','label' => 'English'),
            array('value' => 'LNG','label' => 'Other Language'),

        );
    }
 }