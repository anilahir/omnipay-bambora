<?php

namespace Omnipay\Bambora\Message;

use Omnipay\Bambora\Dictionary\CountryCodesDictionary;
use Omnipay\Bambora\Dictionary\ProvinceCodesDictionary;

class CreateCardRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        return $this->endpoint . '/profiles';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $data = [];
        $this->getCard()->validate();

        if($this->getCard()) {

            $data['card'] = [
                'number' => $this->getCard()->getNumber(),
                'name' => $this->getCard()->getName(),
                'expiry_month' => $this->getCard()->getExpiryDate('m'),
                'expiry_year' => $this->getCard()->getExpiryDate('y'),
                'cvd' => $this->getCard()->getCvv()
            ];

            $province = $this->getCard()->getBillingState();
            $provinceCode = array_search(strtolower($province), array_map('strtolower', ProvinceCodesDictionary::$codes));
            $provinceCode = ($provinceCode == false ? "--" : $provinceCode);
            $country = $this->getCard()->getBillingCountry();
            $counryCode = array_search(strtolower($country), array_map('strtolower', CountryCodesDictionary::$codes));

            $data['billing'] = [
                'name' => $this->getCard()->getBillingName(),
                'address_line1' => $this->getCard()->getBillingAddress1(),
                'address_line2' => $this->getCard()->getBillingAddress2(),
                'city' => $this->getCard()->getBillingCity(),
                'province' => $provinceCode,
                'country' => $counryCode,
                'postal_code' => $this->getCard()->getBillingPostcode(),
                'phone_number' => $this->getCard()->getBillingPhone(),
                'email_address' => $this->getCard()->getEmail(),
            ];
        }

        return json_encode($data);
    }
}
