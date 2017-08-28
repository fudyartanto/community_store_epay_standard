<?php
namespace Concrete\Package\CommunityStoreEpayStandard\Src\CommunityStore\Payment\Methods\CommunityStoreEpayStandard;

use Core;
use URL;
use Config;
use Session;
use Log;

use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as StorePaymentMethod;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Cart\Cart as StoreCart;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\Order as StoreOrder;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Order\OrderStatus\OrderStatus as StoreOrderStatus;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;

class CommunityStoreEpayStandardPaymentMethod extends StorePaymentMethod
{
    public function dashboardForm()
    {
        $this->set('epayMerchantNumber',Config::get('community_store_epay_standard.epayMerchantNumber'));
        $this->set('epayCurrency',Config::get('community_store_epay_standard.epayCurrency'));
        $currencies = array(
            'DKK' => "Danish Krone",
            'USD' => "U.S. Dollar"
        );
        $this->set('currencies',$currencies);
        $this->set('form',Core::make("helper/form"));
    }
    
    public function save(array $data = [])
    {
        Config::save('community_store_epay_standard.epayMerchantNumber',$data['epayMerchantNumber']);
        Config::save('community_store_epay_standard.epayCurrency',$data['epayCurrency']);
    }
    public function validate($args,$e)
    {
        $pm = StorePaymentMethod::getByHandle('community_store_epay_standard');
        if($args['paymentMethodEnabled'][$pm->getID()]==1){
            
        }
        return $e;
        
    }
    public function redirectForm()
    {
        $customer = new StoreCustomer();

        $merchantNumber = Config::get('community_store_epay_standard.epayMerchantNumber');
        $order = StoreOrder::getByID(Session::get('orderID'));
        $this->set('total', ($order->getTotal() + 0) * 100);
        $this->set('notifyURL',URL::to('/checkout/epayresponse'));
        $this->set('orderID',$order->getOrderID());
        $this->set('returnURL',URL::to('/checkout/complete'));
        $this->set('cancelReturn',URL::to('/checkout'));
        $currencyCode = Config::get('community_store_epay_standard.epayCurrency');
        if(!$currencyCode){
            $currencyCode = "USD";
        }
        $this->set('currencyCode',$currencyCode);
        $this->set('merchantNumber',$merchantNumber);
    }
    
    public function submitPayment()
    {   
        //nothing to do except return true
        return array('error'=>0, 'transactionReference'=>'');
        
    } 

    public function getAction()
    {
        return 'https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx';
    }    

    public static function validateCompletion()
    {
        $trxID = $_GET['txnid'];
        $merchantNumber = Config::get('community_store_epay_standard.epayMerchantNumber');
        $orderID = $_GET['orderid'];
        $soap = new \SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL');
        $result = $soap->gettransaction([
                'merchantnumber' => $merchantNumber,
                'transactionid' => $trxID,
                'epayresponse' => '-1'
            ]);
    
        $successStatusList = ['PAYMENT_NEW', 'PAYMENT_CAPTURED'];
        $verified = $result->transactionInformation && $result->transactionInformation->status && in_array($result->transactionInformation->status, $successStatusList);
        
        if ($verified) {
            $order = StoreOrder::getByID($orderID);
            $order->completeOrder($orderID);
            $order->updateStatus(StoreOrderStatus::getStartingStatus()->getHandle());
        }
    }


    public function getPaymentMinimum() {
        return 0.03;
    }


    public function getName()
    {
        return 'Epay Standard';
    }

    public function isExternal() {
        return true;
    }
}
