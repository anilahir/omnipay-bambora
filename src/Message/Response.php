<?php

namespace Omnipay\Bambora\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        if (
            (isset($this->data['message']) && $this->data['message'] == "Operation Successful") &&
            (isset($this->data['code']) && $this->data['code'] == 1)
        )
            return true;
        else if (
            isset($this->data['approved']) && $this->data['approved'] == "1"
        )
            return true;

        return false;
    }

    public function getCardReference()
    {
        return isset($this->data['customer_code']) ? $this->data['customer_code'] : null;
    }

    public function getCode()
    {
        return isset($this->data['code']) ? $this->data['code'] : null;
    }

    public function getAuthCode()
    {
        return isset($this->data['auth_code']) ? $this->data['auth_code'] : null;
    }

    public function getTransactionId()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? $this->data['message'] : null;
    }

    public function getOrderNumber()
    {
        return isset($this->data['order_number']) ? $this->data['order_number'] : null;
    }

    public function getData()
    {
        return json_encode($this->data);
    }
}
