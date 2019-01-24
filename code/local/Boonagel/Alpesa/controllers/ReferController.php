<?php

class Boonagel_Alpesa_ReferController extends Mage_Core_Controller_Front_Action {
//http://127.0.0.1:8012/OneDrive/alladin/index.php/alpesa/refer/refer/?code=c2deecc4748c5049850c6639f9df1143
    
    /**      
* set cookie      
* name and value are mandatory; other parameters are optional and can be set as null      
* $period = cookie expire date in seconds      
*/    
//Mage::getModel('core/cookie')--->set($name, $value, $period, $path, $domain, $secure, $httponly);
///**
//* get cookie with a specific name
//* $name = name of the cookie
//*/
//Mage::getModel('core/cookie')->get($name);
///**
//* get all cookies as an array
//*/
//Mage::getModel('core/cookie')->get();
///**
//* delete/remove cookie
//* $name is mandatory; other parameters are optional and can be set to null
//*/
//Mage::getModel('core/cookie')->delete($name, $path, $domain, $secure, $httponly);
    
    
    /**
     * Check customer authentication
     */
//    public function preDispatch() {
//        parent::preDispatch();
//
//        $loginUrl = Mage::helper('customer')->getLoginUrl();
//
//        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
//            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
//        }
//    }


    public function referAction() {
        
        //confirm code is not null
        $code = null !== $this->getRequest()->getParam('code') ? $this->getRequest()->getParam('code') : null;
        if(strlen(trim($code)) < 1){$this->_redirect("/");return;}
        
        //confirm if cookie already exists if it does then redirect to homepage
        $refActCookie = Mage::getModel('core/cookie')->get('alpesarefcookie');
        if(strlen(trim($refActCookie)) > 0){$this->_redirect("/");return;}
       
        //confirm if code exists
        $alpesarefcodes = Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefcodes', array('code,eq,' . $code),1);
        if($alpesarefcodes->count() != 1){$this->_redirect("/");return;}
        
        //60s * 60m * 24h * 30d * 12m (1 year lifetime cookie)
        Mage::getModel('core/cookie')->set('alpesarefcookie',$code,(60*60*24*30*12));
        //redirect to homepage
        $this->_redirect("/");return;
    }

    public function getAction() {

        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }


        $this->loadLayout();
        Mage::helper('Boonagel_Alpesa')->setTitle($this, "Alpesa referral program");
        $this->renderLayout();
    }

    public function generateAction() {

        //confirm its an ajax request
//        if ($this->getRequest()->isXmlHttpRequest() == false) {
//           echo json_encode(array('status'=>401,'message'=>'Uauthorized'));
//          return; 
//        }
        //confirm user is logged in
        $loginUrl = Mage::helper('customer')->getLoginUrl();
        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            echo json_encode(array('status' => 401, 'message' => 'Kindly log in'));
            return;
        }

        $code = md5(uniqid());

        //determine if the generated code already exists
        
        while (Mage::helper('Boonagel_Alpesa')->dynoData('alpesa/alpesarefcodes', array('code,eq,' . $code))->count() > 0) {
            $code = md5(uniqid());
        }
//save the newly generated code
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $alpesarefcode = Mage::getModel('alpesa/alpesarefcodes');
        $alpesarefcode->getData();
        $alpesarefcode->setCustomerId($customerId);
        $alpesarefcode->setCode($code);
        $alpesarefcode->setUpdatedAt(now());
        $alpesarefcode->setcreatedAt(now());
        $dbdata = $alpesarefcode->save();

        echo json_encode(array('status' => 200, 'message' => Mage::getBaseUrl() . 'alpesa/refer/refer/?code=' . $code));
        return;
    }

}
