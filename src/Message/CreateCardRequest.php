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
        $card = $this->getCard();

        if ($this->getToken()) {
            $data['token'] = $this->getTokenData();
        } else {
            $card->validate();
            $data['card'] = [
                'number' => $card->getNumber(),
                'name' => $card->getName(),
                'expiry_month' => $card->getExpiryDate('m'),
                'expiry_year' => $card->getExpiryDate('y'),
                'cvd' => $card->getCvv()
            ];
        }

        $data['billing'] = $this->getBillingData();

        return json_encode($data);
    }
}
