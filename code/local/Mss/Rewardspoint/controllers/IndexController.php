<?php

class Mss_Rewardspoint_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_REDEEMABLE_POINTS = 'rewardpoints/spending/redeemable_points';
    // verify the token.
    public function _construct()
    {
        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        // Mage::helper('connector')->loadParent(Mage::app()->getFrontController()->getRequest()->getHeader('token'));
        parent::_construct();
    }

    /**
     * get spending calculation
     * 
     * @return Magestore_RewardPoints_Helper_Calculation_Spending
     */
    public function getCalculation()
    {
        return Mage::helper('rewardpoints/calculation_spending');
    }

   /**
     * get current working with quote
     * 
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }
        return Mage::getSingleton('checkout/session')->getQuote();
    }
    
    /**
     * check reward points is enable to use or not
     * 
     * @return boolean
     */
    public function enableRewardAction()
    {
        if (!Mage::helper('rewardpoints')->isEnable(Mage::helper('rewardpoints/customer')->getStoreId())) {
            $result['status'] = "success";
            $result['code'] = 100;
            $result['message'] = "rewards point is disable.";
            echo Mage::helper('core')->jsonEncode($result);
            exit();
        }
        if ($this->getQuote()->getBaseGrandTotal() < 0.0001
            && !$this->getCalculation()->getTotalRulePoint()
        ) {
            $result['status'] = "success";
            $result['code'] = 100;
            $result['message'] = "rewards point is disable.";
            echo Mage::helper('core')->jsonEncode($result);
            exit();
        }
        if (!Mage::helper('rewardpoints/customer')->isAllowSpend($this->getQuote()->getStoreId())) {
            $result['status'] = "success";
            $result['code'] = 100;
            $result['message'] = "rewards point is disable.";
            echo Mage::helper('core')->jsonEncode($result);
            exit();
        }
          $result['status'] = "success";
          $result['code'] = 200;
          $result['message'] = "rewards point is enable.";

        echo Mage::helper('core')->jsonEncode($result);
    }


   /**
     * Get the points of customers
     * @return object
     */
    public function getPointsAction()
    {ini_set('display_errors', 'On');
error_reporting(E_ALL);
        $result = array();
        if (Mage::getSingleton('customer/session')->isLoggedIn()){
            $arrayRules = Mage::helper('rewardpoints/block_spend')->getRulesArray();

            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $websiteId = Mage::app()->getWebsite()->getId();

            $rate = Mage::getSingleton('rewardpoints/rate')->getRate(
                    Magestore_RewardPoints_Model_Rate::POINT_TO_MONEY, $customerGroupId, $websiteId
            );
            // print_r($rate->getPoints()); die;
            // if ($rate && $rate->getId()) {
            //     $rates = array();
            //     $totalrun  = ceil($arrayRules['rate']['sliderOption']['maxPoints'] / $rate->getPoints());
            //     for ($i=1; $i < $totalrun + 1; $i++) { 
            //         $rates[$rate->getPoints() * $i] = $rate->getMoney() * $i; 
            //     }
            // }
            $arrayRules['rate']['sliderOption']['points'] = $rate->getPoints(); 
            $arrayRules['rate']['sliderOption']['money'] =  Mage::helper('core')->currency($rate->getMoney(),true,false); 
            echo Mage::helper('core')->jsonEncode($arrayRules); 
            exit();
        } else {
            $result['message']  = "User is not Logged in";
            $result['status'] = 'error';
            $result['code'] = 1001;
        }
        echo Mage::helper('core')->jsonEncode($result);
    }

    /**
     * Update Total for shopping cart Page
     * @param reward_sales_rule  = rate
     * @param reward_sales_point = 400 (Points To be deducted.)
     * return Object
     */
    public function updateTotalAction()
    {
        if(!is_numeric($this->getRequest()->getParam('reward_sales_point'))){
          $result['status'] = "error";
          $result['error'] = "Points should be in numeric.";
          echo json_encode($result);
          exit();
        }
        $session = Mage::getSingleton('checkout/session');
        $session->setData('use_point', true);
        $session->setRewardSalesRules(array(
            'rule_id'   => $this->getRequest()->getParam('reward_sales_rule'),
            'use_point' => $this->getRequest()->getParam('reward_sales_point'),
        ));
        $cart   = Mage::getSingleton('checkout/cart');
        $final_result = array();
        $result = array();
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();
            $this->checkUseDefault();
            $result['total_points_spend'] = Mage::helper('rewardpoints/calculation_spending')->getTotalPointSpent();
            if (Mage::helper('rewardpoints/calculation_spending')->getTotalPointSpent() && !Mage::getStoreConfigFlag('rewardpoints/earning/earn_when_spend',Mage::app()->getStore()->getId())) {
              $result['total_points_earn'] = 0;
            }
            $result['status'] = 'success';
            $result['total_points_earn'] =  Mage::helper('rewardpoints/calculation_earning')->getTotalPointsEarning();
            $quote = $session->getQuote()->getdata();
            $result['grand_total'] = $quote['grand_total'];
            $result['disccount_by_rewards'] = $quote['rewardpoints_discount'];
            $result['subtotal'] = $quote['subtotal'];
            $result['customer_id'] = $quote['customer_id'];
        } else {
            $result['refresh'] = true;
        }
        $final_result['totals'] = $result;

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($final_result));
    }

    public function checkUseDefault()
    {
        $session          = Mage::getSingleton('checkout/session');
        $rewardSalesRules = $session->getRewardSalesRules();
        $arrayRules       = Mage::helper('rewardpoints/block_spend')->getRulesArray();
        if (Mage::helper('rewardpoints/calculation_spending')->isUseMaxPointsDefault()) {
            if (isset($rewardSalesRules['use_point']) && isset($rewardSalesRules['rule_id']) && isset($arrayRules[$rewardSalesRules['rule_id']]) && isset($arrayRules[$rewardSalesRules['rule_id']]['sliderOption']) && isset($arrayRules[$rewardSalesRules['rule_id']]['sliderOption']['maxPoints']) && ($rewardSalesRules['use_point'] < $arrayRules[$rewardSalesRules['rule_id']]['sliderOption']['maxPoints'])) {
                $session->setData('use_max', 0);
            } else {
                $session->setData('use_max', 1);
            }
        }
    }

    /**
     * get the rewards points of particular product.
     * @param product_id
     */
    public function getproductrewardAction()
    {
      if (Mage::getSingleton('customer/session')->isLoggedIn()) {
      $result = array();
      $final_result = array(); 
        if($this->getRequest()->getParam('product_id')){
          $product =  Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id'));
          if ($product && $point=Mage::helper('rewardpoints/calculation_earning')->getRateEarningPoints($product->getFinalPrice())) {
           $product->setData('earning_points', $point);
           $result['status'] = 'success';
           $result['points'] = $product->getData('earning_points');
          }
        } else {
          $result['status'] = "error";
          $result['error'] = 'Please provide product id';
        }
      } else {
            $result['error']  = "User is not Logged in";
            $result['status'] = 1001;
      }
      $final_result['earning'] = $result;

      $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($final_result));
    }

    public function searchAction(){
        // {"data":[{"name":"Afghanistan"}]}
      $result = array();
      $result['name'] = 'Sahil';
      $result['email'] = 'sthukral82@yahoo.com';
      $result['company'] =  'master';
      $result['address']  = '404 master';
      $result['lastname'] = 'Thukral';
      $array_new = json_encode($result);
      
       echo '{"data":['.$array_new.']}';
    }

    /**
     * get the account section for rewards
     * url Base/rewardspoint/index/account
     * @param 
     * @return JsonArray
     */
    public function accountAction()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
      if($customerId):

          $collection = Mage::getResourceModel('rewardpoints/transaction_collection')
              ->addFieldToFilter('customer_id', $customerId);
          $collection->getSelect()->limit(5)
              ->order('created_time DESC');
          $i = 0;
          $array = array();
          $result = array();
          foreach ($collection as $_transaction):
            $array[$i]['Title'] =  $_transaction->getTitleHtml();
            $array[$i]['Points'] =  $_transaction->getPointAmount();
            $array[$i]['creating_date'] = Mage::helper('core')->formatTime($_transaction->getCreatedTime(), 'medium', true);
            if ($_transaction->getExpirationDate()):
              $array[$i]['expiration_date'] = Mage::helper('core')->formatTime($_transaction->getCreatedTime(), 'medium', true);
            else: 
              $array[$i]['expiration_date'] = $this->__('N/A');
            endif;
              $array[$i]['Status'] = $_transaction->getStatusLabel();
              $i++;
          endforeach;
          $result['info_rewards'] = $array;

        $block_earn = $this->getLayout()->createBlock('rewardpoints/account_dashboard_earn')->setTemplate('rewardpoints/account/dashboard/earn.phtml');
        $block_spend = $this->getLayout()->createBlock('rewardpoints/account_dashboard_spend')->setTemplate('rewardpoints/account/dashboard/spend.phtml');
        $result['account_page_html_spend'] = $block_spend->toHtml();
        $result['account_page_html_earn'] = $block_earn->toHtml();
        $result['status'] = 'success';
        $result['points'] = Mage::helper('rewardpoints/customer')->getBalance();
 
      else:

          $result['message']  = "User is not Logged in";
          $result['status'] = 'error';

      endif;

      echo Mage::helper('core')->jsonEncode($result);
      
    }

    /**
     * get the Listing of Transaction
     * url Base/rewardspoint/index/getrewardlist
     * @param 
     * @return Json
     */
    public function getRewardListAction()
    {
      $customerId = Mage::getSingleton('customer/session')->getCustomerId();
      if($customerId):

          $collection = Mage::getResourceModel('rewardpoints/transaction_collection')
              ->addFieldToFilter('customer_id', $customerId);
          $collection->getSelect()->limit()
              ->order('created_time DESC');
          $array = array();
          $i = 0;
          foreach ($collection as $_transaction):
            $array[$i]['Title'] =  $_transaction->getTitleHtml();
            $array[$i]['Points'] =  $_transaction->getPointAmount();
            $array[$i]['creating_date'] = Mage::helper('core')->formatTime($_transaction->getCreatedTime(), 'medium', true);
            if ($_transaction->getExpirationDate()):
              $array[$i]['expiration_date'] = Mage::helper('core')->formatTime($_transaction->getCreatedTime(), 'medium', true);
            else: 
              $array[$i]['expiration_date'] = $this->__('N/A');
            endif;
              $array[$i]['Status'] = $_transaction->getStatusLabel();
              $i++;
          endforeach;
        $result['list_rewards'] = $array;
        $result['status'] = "success"; 

      else:

          $result['message']  = "User is not Logged in";
          $result['status'] = 'error';
        
      endif;
      echo Mage::helper('core')->jsonEncode($result);
    }
}
