<?php

namespace Buckaroo\BuckarooWrapper;
use Buckaroo\PaymentMethods\PaymentMethod;
use Illuminate\Support\Facades\Validator;

trait ValidateData
{
    protected  function validateMethod(string $payementType,string $method,array $data){
        $paymentMethod = $payementType . $method;

        switch($paymentMethod) {
            case ('creditcardpay') :
                return  $this->creditcardPay($data);
            default:
                'You are not choosing any payment method.';
        }
    }



    protected function creditcardPay(array $data){

        $validator = Validator::make($data, [
            'amountDebit'   =>  'required|decimal:2',
            'invoice'       => 'string',
            'name'          =>  'required|string'
        ]);

        return $validator;
    }
}
