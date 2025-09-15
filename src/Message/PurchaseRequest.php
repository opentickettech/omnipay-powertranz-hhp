<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Powertranz\Traits\CurrencyConversionTrait;

class PurchaseRequest extends AbstractRequest
{
    use CurrencyConversionTrait;
    protected function getEndpointPath()
    {
        return '/Api/Spi/Sale';
    }

    public function getData()
    {
        // Validate required fields but skip amount validation if currency is numeric
        $this->validate('currency');
        if (!$this->getParameter('amount')) {
            throw new InvalidRequestException('The amount parameter is required');
        }

        $data = [
            'AddressMatch' => false,
            'ExtendedData' => [
                'HostedPage' => [
                    'PageName' => $this->getPageName() ?: 'PageName',
                    'PageSet' => $this->getPageSet() ?: 'PTZ/PageSet'
                ],
                'ThreeDSecure' => [
                    'ChallengeIndicator' => $this->getChallengeIndicator() ?: '03',
                    'ChallengeWindowSize' => $this->getChallengeWindowSize() ?: 4,
                    'AuthenticationIndicator' => $this->getAuthenticationIndicator() ?: '04'
                ],
                'MerchantResponseUrl' => $this->getReturnUrl()
            ],
            'ThreeDSecure' => true,
            'TotalAmount' => (int) $this->getParameter('amount'),
            'CurrencyCode' => $this->getCurrencyNumeric(),
            'OrderIdentifier' => $this->getTransactionId()
        ];

        return $data;
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new PurchaseResponse($this, $data, $statusCode);
    }

    public function getPageName()
    {
        return $this->getParameter('pageName');
    }

    public function setPageName($value)
    {
        return $this->setParameter('pageName', $value);
    }

    public function getPageSet()
    {
        return $this->getParameter('pageSet');
    }

    public function setPageSet($value)
    {
        return $this->setParameter('pageSet', $value);
    }

    public function getChallengeIndicator()
    {
        return $this->getParameter('challengeIndicator');
    }

    public function setChallengeIndicator($value)
    {
        return $this->setParameter('challengeIndicator', $value);
    }

    public function getChallengeWindowSize()
    {
        return $this->getParameter('challengeWindowSize');
    }

    public function setChallengeWindowSize($value)
    {
        return $this->setParameter('challengeWindowSize', $value);
    }

    public function getAuthenticationIndicator()
    {
        return $this->getParameter('authenticationIndicator');
    }

    public function setAuthenticationIndicator($value)
    {
        return $this->setParameter('authenticationIndicator', $value);
    }
}