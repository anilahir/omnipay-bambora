<?php

namespace Omnipay\Bambora\Message;

class PurchaseRequest extends AbstractRequest
{
    protected $complete = true;

    public function getEndpoint()
    {
        return $this->endpoint . '/payments';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $this->validate('amount', 'paymentMethod');

        $data = array(
            'amount' => $this->getAmount(),
            'order_number' => $this->getOrderNumber(),
            'payment_method' => $this->getPaymentMethod(),
        );

        $paymentMethod = $this->getPaymentMethod();
        $card = $this->getCard();

        switch ($paymentMethod)
        {
            case 'card' :
                if ($card) {
                    $card->validate();

                    $data['card'] = array(
                        'number' => $card->getNumber(),
                        'name' => $card->getName(),
                        'expiry_month' => $card->getExpiryDate('m'),
                        'expiry_year' => $card->getExpiryDate('y'),
                        'cvd' => $card->getCvv(),
                        'complete' => $this->complete,
                    );
                }
                break;

            case 'payment_profile' :
                $this->validate('cardReference');

                $data['payment_profile'] = [
                    'customer_code' => $this->getCardReference(),
                    'card_id' => $this->getCardId() > 0 ? $this->getCardId() : 1,
                    'complete' => $this->complete
                ];
                break;

            case 'token' :
                $this->validate('token');
                if ($this->getToken()) {
                    $data['token'] = $this->getTokenData();
                    $data['token']['complete'] = $this->complete;
                }
                break;
            default :
                break;
        }

        // Include optional billing and shipping details if available
        $billingData = $this->getBillingData();
        if ($billingData) $data['billing'] = $billingData;
        $shippingData = $this->getShippingData();
        if ($shippingData) $data['shipping'] = $shippingData;

        return json_encode($data);
    }
}

