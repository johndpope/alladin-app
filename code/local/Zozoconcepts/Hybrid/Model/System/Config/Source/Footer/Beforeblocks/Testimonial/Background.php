<?php 
class Zozoconcepts_Hybrid_Model_System_Config_Source_Footer_Beforeblocks_Testimonial_Background
{
	public function toOptionArray()
    {
		return array(
			array('value' => 'testimonial/background_image.phtml',	'label' => Mage::helper('hybrid')->__('Background Image')),
			array('value' => 'testimonial/background_video.phtml',	'label' => Mage::helper('hybrid')->__('Video playback')),
        );
    }
}