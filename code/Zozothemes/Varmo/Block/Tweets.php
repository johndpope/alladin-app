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

namespace Zozothemes\Varmo\Block;



class Tweets extends \Magento\Framework\View\Element\Template
{

    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    //protected $scopeConfig;
    protected $_twitter;
    
    const USERNAME = 'varmo_settings/footer/tw_username';
    const TWEET_LIMIT = 'varmo_settings/footer/tw_no_of_tweets';
    const WIDGET = 'varmo_settings/footer/tw_widget';

    /**
     * Timeline constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Timeline $timeline
     * @param array $data
     */
    public function __construct(
        //\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Element\Template\Context $context,
        \Zozothemes\Varmo\Model\Tweet\Feed $twitter,
        \Zozothemes\Varmo\Helper\Data $helper,
        array $data = []
    ) {
        //$this->scopeConfig = $scopeConfig;
        
        $this->_twitter = $twitter;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getTweets()
    { 
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $username = $this->_scopeConfig->getValue(self::USERNAME, $storeScope);
        $tweet_limit = $this->_scopeConfig->getValue(self::TWEET_LIMIT, $storeScope);
        
        return $this->_twitter->getTimeline($username, $tweet_limit);
    }
    
    /**
     * 
     */
    function getWidgets(){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue(self::WIDGET, $storeScope);
    }
    /**
     * @return string
     */
    function getTime($time){
		 $tweetdate = $time;
		 //$tweet = isset($tweettag["content"])?isset($tweettag["content"]):'';
		 $timedate = explode(" ",$tweetdate);
		 $date1 = $timedate[2];
		 $date2 = $timedate[1];
		 $date3 = $timedate[5];
		 $time = substr($timedate[3],0, -1);
		 $tweettime = (strtotime($date1." ".$date2." ".$date3." ".$time))+3600; // This is the value of the time difference - UK + 1 hours (3600 seconds)
		 $nowtime = time();
		 $timeago = ($nowtime-$tweettime);
		 $thehours = floor($timeago/3600);
		 $theminutes = floor($timeago/60);
		 $thedays = floor($timeago/86400);
		 /********************* Checking the times and returning correct value */
		 if($theminutes < 60){
		 if($theminutes < 1){
		 $timemessage =  "Less than 1 minute ago";
		 } else if($theminutes == 1) {
		 $timemessage = $theminutes." minute ago.";
		 } else {
		 $timemessage = $theminutes." minutes ago.";
		 }
		 } else if($theminutes > 60 && $thedays < 1){
		 if($thehours == 1){
		 $timemessage = $thehours." hour ago.";
		 } else {
		 $timemessage = $thehours." hours ago.";
		 }
		 } else {
		 if($thedays == 1){
		 $timemessage = $thedays." day ago.";
		 } else {
		 $timemessage = $thedays." days ago.";
		 }
		 }
		 return $timemessage;
	}
}