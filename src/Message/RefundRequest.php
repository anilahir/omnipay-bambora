<?php

namespace Omnipay\Bambora\Message;

class RefundRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        return $this->endpoint . '/payments/' . $this->getTransactionReference() . '/returns';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $this->validate('amount', 'transactionReference');

        $data = [
            'amount' => $this->getAmount(),
            'order_number' => $this->getTransactionId()
        ];

        return json_encode($data);
    }
}
