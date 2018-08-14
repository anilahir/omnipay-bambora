<?php

namespace  Omnipay\Bambora\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://api.na.bambora.com/v1';

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getProfilePasscode()
    {
        return $this->getParameter('profilePasscode');
    }

    public function setProfilePasscode($value)
    {
        return $this->setParameter('profilePasscode', $value);
    }

    public function getTransactionPasscode()
    {
        return $this->getParameter('transactionPasscode');
    }

    public function setTransactionPasscode($value)
    {
        return $this->setParameter('transactionPasscode', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('payment_method');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('payment_method', $value);
    }

    public function getPaymentProfile()
    {
        return $this->getParameter('payment_profile');
    }

    public function setPaymentProfile($value)
    {
        return $this->setParameter('payment_profile', $value);
    }

    public function getOrderNumber()
    {
        return $this->getParameter('order_number');
    }

    public function setOrderNumber($value)
    {
        return $this->setParameter('order_number', $value);
    }

    public function sendData($data)
    {
        $apiPasscode = str_contains($this->getEndpoint(), '/profiles') ? $this->getProfilePasscode() : $this->getTransactionPasscode();
    
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Passcode ' . base64_encode($this->getMerchantId() . ':' . $apiPasscode)
        ];

        if(!empty($data)) {
            $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $data);
        }
        else {
            $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers);
        }
    
        try {
            $jsonRes = json_decode($httpResponse->getBody()->getContents(), true);
        }
        catch (\Exception $e){
            info('Guzzle response : ', [$httpResponse]);
            $res = [];
            $res['resptext'] = 'Oops! something went wrong, Try again after sometime.';
            return $this->response = new Response($this, $res);
        }

        return $this->response = new Response($this, $jsonRes);
    }
}

