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
        $this->validate('amount');

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
                if ($this->getCardReference()) {

                    $data['payment_profile'] = [
                        'customer_code' => $this->getCardReference(),
                        'card_id' => 1,
                        'complete' => $this->complete
                    ];
                }
                break;

            case 'token' :
                if ($this->getToken()) {

                    $data['token'] = [
                        'code' => $this->getToken(),
                        'complete' => $this->complete,
                        'name' => $card ? $card->getBillingName() : null,
                    ];
                }
                break;
            default :
                break;
        }

        // Optional parameters
        if ($card) {

            $data['billing'] = array(
                'name' => $card->getBillingName(),
                'address_line1' => $card->getBillingAddress1(),
                'address_line2' => $card->getBillingAddress2(),
                'city' => $card->getBillingCity(),
                'province' => $card->getBillingState(),
                'country' => $card->getBillingCountry(),
                'postal_code' => $card->getBillingPostcode(),
                'phone_number' => $card->getBillingPhone(),
                'email_address' => $card->getEmail(),
            );

            $data['shipping'] = array(
                'name' => $card->getShippingName(),
                'address_line1' => $card->getShippingAddress1(),
                'address_line2' => $card->getShippingAddress2(),
                'city' => $card->getShippingCity(),
                'province' => $card->getShippingState(),
                'country' => $card->getShippingCountry(),
                'postal_code' => $card->getShippingPostcode(),
                'phone_number' => $card->getShippingPhone(),
                'email_address' => $card->getEmail(),
            );
        }

        return json_encode($data);
    }
}

