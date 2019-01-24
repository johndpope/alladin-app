<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zozothemes\Varmo\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_objectManager;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        parent::__construct($context);
    }
    
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getModel($model) {
        return $this->_objectManager->create($model);
    }

    public function getCurrentStore() {
        return $this->_storeManager->getStore();
    }
    
    // get social config 
    
    public function getFacebookUrl(){
        return $this->getConfig('varmo_settings/footer/footer_fb');
    }

    public function getGoogleplusUrl(){
        return $this->getConfig('varmo_settings/footer/footer_gp');
    }

    public function getTwitterUrl(){
        return $this->getConfig('varmo_settings/footer/footer_tw');
    }

    public function getYoutubeUrl(){
        return $this->getConfig('varmo_settings/footer/footer_yt');
    }

    public function getDribbbbleUrl(){
        return $this->getConfig('varmo_settings/footer/footer_dp');
    }
    
    // get twitter details
    
    public function getTwitterTitle(){
        return $this->getConfig('varmo_settings/footer/tw_title');
    }
        
    public function getTwitterUsername(){
        return $this->getConfig('varmo_settings/footer/tw_username');
    }
    
    public function getTwitterConfig(){
        return $this->getConfig('varmo_settings/footer/tw_config');
    }

//    public function getTwitterConsSec(){
//        return $this->getConfig('varmo_settings/footer/tw_cons_sec');
//    }
//
//    public function getTwitterAccessToken(){
//        return $this->getConfig('varmo_settings/footer/tw_access_token');
//    }
//
//    public function getTwitterAccessTokenSec(){
//        return $this->getConfig('varmo_settings/footer/tw_access_token_sec');
//    }
//
//    public function getTwitterNoOfTweets(){
//        return $this->getConfig('varmo_settings/footer/tw_no_of_tweets');
//    }
//
    public function getTwitterTime(){ 
        return $this->getConfig('varmo_settings/footer/tw_time');
    }
    
    
}
