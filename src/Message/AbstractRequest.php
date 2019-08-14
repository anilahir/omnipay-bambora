<?php

namespace  Omnipay\Bambora\Message;

use Omnipay\Bambora\Dictionary\CountryCodesDictionary;
use Omnipay\Bambora\Dictionary\ProvinceCodesDictionary;

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
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    public function getPaymentProfile()
    {
        return $this->getParameter('paymentProfile');
    }

    public function setPaymentProfile($value)
    {
        return $this->setParameter('paymentProfile', $value);
    }

    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    public function setOrderNumber($value)
    {
        return $this->setParameter('orderNumber', $value);
    }

    /**
     * Get the card id (which stored credit card to use).
     *
     * @return int
     */
    public function getCardId()
    {
        return $this->getParameter('cardId');
    }

    /**
     * Sets the card id.
     *
     * @param int $value
     * @return $this
     */
    public function setCardId($value)
    {
        return $this->setParameter('cardId', (int) $value);
    }

    public function getAddressData($type)
    {
        $card = $this->getCard();
        if (!$card) return null;

        // Sanitise country
        $country = $card->{ 'get' . $type . 'Country' }();
        $countryCode = (array_key_exists($country, CountryCodesDictionary::$codes))
            ? $country
            : array_search(strtolower($country), array_map('strtolower', CountryCodesDictionary::$codes));

        // Sanitise province
        $province = $card->{ 'get' . $type . 'State' }();
        $provinceCode = (array_key_exists($province, ProvinceCodesDictionary::$codes))
            ? $province
            : array_search(strtolower($province), array_map('strtolower', ProvinceCodesDictionary::$codes));
        if ($provinceCode == false || !in_array($country, ['CA', 'US'])) $provinceCode = "--";

        $data = array(
            'name' => $card->{ 'get' . $type . 'Name' }(),
            'address_line1' => $card->{ 'get' . $type . 'Address1' }(),
            'address_line2' => $card->{ 'get' . $type . 'Address2' }(),
            'city' => $card->{ 'get' . $type . 'City' }(),
            'province' => $provinceCode,
            'country' => $countryCode,
            'postal_code' => $card->{ 'get' . $type . 'Postcode' }(),
            'phone_number' => $card->{ 'get' . $type . 'Phone' }(),
            'email_address' => $card->getEmail(),
        );
        // Only return address if it looks valid
        return ($data['name'] && $data['address_line1'] && $data['city'] && $data['country']) ? $data : null;
    }

    public function getBillingData()
    {
        return $this->getAddressData('Billing');
    }

    public function getShippingData()
    {
        return $this->getAddressData('Shipping');
    }

    public function sendData($data)
    {
        $apiPasscode = strpos($this->getEndpoint(), '/profiles') !== false ? $this->getProfilePasscode() : $this->getTransactionPasscode();

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

