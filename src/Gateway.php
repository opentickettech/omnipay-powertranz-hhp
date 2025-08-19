<?php

namespace Omnipay\Powertranz;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Powertranz';
    }

    public function getDefaultParameters()
    {
        return [
            'powertranzId' => '',
            'powertranzPassword' => '',
            'testMode' => false,
        ];
    }

    public function getPowertranzId()
    {
        return $this->getParameter('powertranzId');
    }

    public function setPowertranzId($value)
    {
        return $this->setParameter('powertranzId', $value);
    }

    public function getPowertranzPassword()
    {
        return $this->getParameter('powertranzPassword');
    }

    public function setPowertranzPassword($value)
    {
        return $this->setParameter('powertranzPassword', $value);
    }

    /**
     * Start a purchase request (creates SPI token for hosted payment page)
     *
     * @param array $parameters
     * @return RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(Message\PurchaseRequest::class, $parameters);
    }

    /**
     * Complete a purchase (process SPI token after redirect)
     *
     * @param array $parameters
     * @return RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(Message\CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Refund a transaction
     *
     * @param array $parameters
     * @return RequestInterface
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(Message\RefundRequest::class, $parameters);
    }

    /**
     * Fetch details of a transaction
     *
     * @param array $parameters
     * @return RequestInterface
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(Message\FetchTransactionRequest::class, $parameters);
    }

    /**
     * Check if gateway supports refunds
     *
     * @return bool
     */
    public function supportsRefund()
    {
        return true;
    }

    /**
     * Check if gateway supports fetching transaction details
     *
     * @return bool
     */
    public function supportsFetchTransaction()
    {
        return true;
    }
}