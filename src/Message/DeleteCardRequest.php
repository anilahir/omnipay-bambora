<?php

namespace Omnipay\Bambora\Message;

class DeleteCardRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        $this->validate('cardReference');
        
        return $this->endpoint . '/profiles/' . $this->getCardReference();
    }

    public function getHttpMethod()
    {
        return 'DELETE';
    }

    public function getData()
    {
        return json_encode([]);
    }
}
