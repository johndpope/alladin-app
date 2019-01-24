<?php

/* 
 * Zozothemes.
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Zozothemes.com license that is
 * available through the world-wide-web at this URL:
 * http://www.zozothemes.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Zozothemes
 * @package    Zozothemes_Varmo
 * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
 * @license    http://www.zozothemes.com/LICENSE-1.0.html
 */

namespace Zozothemes\Varmo\Model\Tweet;
 
use Abraham\TwitterOAuth\TwitterOAuth;

class Feed {
    
    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    const CONSUMER_KEY = 'varmo_settings/footer/tw_cons_key';
    const CONSUMER_SECRET ='varmo_settings/footer/tw_cons_sec';
    const ACCESS_TOKEN ='varmo_settings/footer/tw_access_token';
    const ACCESS_TOKEN_SECRET ='varmo_settings/footer/tw_access_token_sec';
    
    protected $_connection;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
        
        $this->scopeConfig = $scopeConfig;
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $consumer_key = $this->scopeConfig->getValue(self::CONSUMER_KEY, $storeScope);
        $consumer_secret = $this->scopeConfig->getValue(self::CONSUMER_SECRET,$storeScope);
        $access_token = $this->scopeConfig->getValue(self::ACCESS_TOKEN,$storeScope);
        $access_token_secret = $this->scopeConfig->getValue(self::ACCESS_TOKEN_SECRET,$storeScope);
        $this->_connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
    }

    public function getTimeline($username, $limit)
    {
        
        return $this->_connection->get("statuses/user_timeline", [
            "count" => $limit,
            "exclude_replies" => true,
            "screen_name" => $username
        ]);
    }
}