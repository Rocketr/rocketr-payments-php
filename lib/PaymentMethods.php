<?php

namespace RocketrPayments;

class PaymentMethods {
    const PaypalPayment = [
        "id" => 0,
        "name" => "paypal",
        "prettyName" => "Paypal"
    ];
    const BitcoinPayment = [
        "id" => 1,
        "name" => "btc",
        "prettyName" => "Bitcoin"
    ];
    const EtherPayment = [
        "id" => 2,
        "name" => "eth",
        "prettyName" => "Ethereum"
    ];
    
    const PerfectMoneyPayment = [
        "id" => 3,
        "name" => "pm",
        "prettyName" => "Perfect Money"
    ];
    
    const StripePayment = [
        "id" => 4,
        "name" => "stripe",
        "prettyName" => "Stripe (Credit Card)"
    ];
    
    /**
     * Only used internally. In case id 5 needs to be resolved back to paypal
     *
     */
    const PaypalMarketplacePayment = [
        "id" => 5,
        "name" => "paypal",
        "prettyName" => "Paypal"
    ];
    
    const BitcoinCashPayment = [
        "id" => 6,
        "name" => "bcc",
        "prettyName" => "Bitcoin Cash"
    ];
    
    public static function getConstFromId($id){
        switch($id){
            case PaymentMethods::PaypalPayment['id']:
                return PaymentMethods::PaypalPayment;
            break;
            case PaymentMethods::BitcoinPayment['id']:
                return  PaymentMethods::BitcoinPayment;
            break;
            case PaymentMethods::EtherPayment['id']:
                return  PaymentMethods::EtherPayment;
            break;
            case PaymentMethods::PerfectMoneyPayment['id']:
                return  PaymentMethods::PerfectMoneyPayment;
            break;
            case PaymentMethods::StripePayment['id']:
                return  PaymentMethods::StripePayment;
            break;
            case PaymentMethods::BitcoinCashPayment['id']:
                return  PaymentMethods::BitcoinCashPayment;
            case PaymentMethods::PaypalMarketplacePayment['id']:
                return PaymentMethods::PaypalMarketplacePayment;
            break;
            default:
                throw new RocketrPaymentsException("PaymentMethod not found" . $id);
        }
    }
    
    public static function getConstFromName($name){
        switch($name){
            case PaymentMethods::PaypalPayment['name']:
                return PaymentMethods::PaypalPayment;
            break;
            case PaymentMethods::BitcoinPayment['name']:
                return  PaymentMethods::BitcoinPayment;
            break;
            case PaymentMethods::EtherPayment['name']:
                return  PaymentMethods::EtherPayment;
            break;
            case PaymentMethods::PerfectMoneyPayment['name']:
                return  PaymentMethods::PerfectMoneyPayment;
            break;
            case PaymentMethods::StripePayment['name']:
                return  PaymentMethods::StripePayment;
            break;
            case PaymentMethods::PaypalMarketplacePayment['name']:
                return PaymentMethods::PaypalMarketplacePayment;
            case PaymentMethods::BitcoinCashPayment['name']:
                return  PaymentMethods::BitcoinCashPayment;
            break;
            default:
                throw new RocketrPaymentsException("PaymentMethod not found" . $name);
        }
    }
    
}

?>