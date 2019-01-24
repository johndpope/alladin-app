<?php
class Cminds_SupplierTrading_Helper_Email extends Mage_Core_Helper_Abstract {

    public function _sendEmail($receiverName, $receiverEmail, $title, $message)
    {
        if ($message == '') {
            return false;
        }

        $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

        $this->sendZendMail($senderName, $receiverEmail, $senderEmail, $title, $message);
    }

    public function sendZendMail($name, $email, $from, $subject, $content)
    {
        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyHtml($content);
        $mail->setFrom($from, $name);
        $mail->addTo($email, 'No reply');
        $mail->setSubject($subject);
        try {
            $mail->send();

        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }


    public function sendTradeMail($product, $trade, $supplier)
    {
        $storeId = Mage::app()->getStore()->getId();

        $emailTemplateVariables['trade'] = $trade;
        $emailTemplateVariables['customer'] = Mage::getModel('customer/customer')->load($trade->getCustomerId());
        $emailTemplateVariables['product'] = $product;

        $sender  = array(
            'name' => Mage::getStoreConfig('trans_email/ident_support/name', $storeId),
            'email' => Mage::getStoreConfig('trans_email/ident_support/email', $storeId)
        );
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->setTemplateSubject(
            Mage::helper('suppliertrading')->__(
                'Price proposal for your product'
            )
        );

        $emailTemplate->sendTransactional(
            'price_trade_sent',
            $sender,
            array($supplier->getEmail()),
            array($supplier->getName()),
            $emailTemplateVariables,
            $storeId
        );
    }

    public function sendAcceptTradeMail($product,$trade,$customer)
    {
        $message = 'Your suggest price for product '.$product->getName().' was accepted.  <br/>
        Please log in and check your cart <a href="'.Mage::getBaseUrl().'customer/account/login/">Click</a>
        ';

        $this->_sendEmail($customer->getFirstname().' '.$customer->getLastname(), $customer->getEmail(), 'Your price suggest was accepted', $message);
    }

    public function sendRejectTradeMail($product,$trade,$customer,$note)
    {

        $message = 'Your suggest price for product '.$product->getName().' was rejected.  <br/><br/>';
        if($note != '')
            $message.= 'Note from supplier: '.$note;


        $this->_sendEmail($customer->getFirstname().' '.$customer->getLastname(), $customer->getEmail(), 'Your price suggest was rejected', $message);
    }


}
