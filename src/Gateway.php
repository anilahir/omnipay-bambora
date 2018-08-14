<?php

namespace Omnipay\Bambora;

use Omnipay\Common\AbstractGateway;

/**
 * Bambora Gateway
 * @link https://dev.na.bambora.com/docs/guides/merchant_quickstart/calling_APIs
 */

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Bambora';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'profilePasscode' => '',
            'transactionPasscode' => ''
        ];
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

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bambora\Message\CreateCardRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bambora\Message\DeleteCardRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bambora\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bambora\Message\RefundRequest', $parameters);
    }
}

