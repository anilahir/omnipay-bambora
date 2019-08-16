<?php

namespace Omnipay\Bambora\Message;

class GetCardRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        $this->validate('cardReference');
        return $this->endpoint . '/profiles/' . $this->getCardReference() . '/' . $this->getCardId();
    }

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getData()
    {
        return json_encode([]);
    }
}
