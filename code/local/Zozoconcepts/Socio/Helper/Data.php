<?php
/**
 * Zozoconcepts_Socio extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Zozoconcepts
 * @package        Zozoconcepts_Socio
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Twitter block
 *
 * @category    Zozoconcepts
 * @package     Zozoconcepts_Socio
 * @author      Zozoconcepts Hybrid
 */
class Zozoconcepts_Socio_Helper_Data extends Mage_Core_Helper_Abstract
{
	//Social Icons
	public function enableSocial(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/enable_social');
	}
	public function facebook(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/facebook');
	}
	public function googleplus(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/googleplus');
	}
	public function twitter(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/twitter');
	}
	public function youtube(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/youtube');
	}
	public function dribbble(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/dribbble');
	}
	// Twitter Feeds
	public function isEnabled(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/enable_twitterfeeds');
	}
	public function getTitle(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_title');
	}
	public function isEnabledWidgets(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_widgets');		
	}
	public function getWidgetScript(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_widgets_script');		
	}	
	public function getUsername(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_username');
	}
	public function getConsumerKey(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_ckey');
	}
	public function getConsumerSecrete(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_cskey');
	} 
	public function getAccessToken(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_actoken');
	}
	public function getAccessTokenSecret(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_actokensec');
	}
	public function getLimit(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_notweete');
	}
	public function isDisplayPostedTime(){
		return Mage::getStoreConfig('zozoconcepts_socio/social_blocks/tw_time');
	}
	
}
	 
