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

            $data['billing'] = $this->getBillingData();
        }

        return json_encode($data);
    }
}
