<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $liveEndpoint = 'https://gateway.ptranz.com';
    protected $testEndpoint = 'https://staging.ptranz.com';

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

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'PowerTranz-PowerTranzId' => $this->getPowertranzId(),
            'PowerTranz-PowerTranzPassword' => $this->getPowertranzPassword(),
        ];
    }

    public function sendData($data)
    {
        $headers = $this->getHeaders();

        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint() . $this->getEndpointPath(),
            $headers,
            json_encode($data)
        );

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->createResponse($responseData, $httpResponse->getStatusCode());
    }

    abstract protected function getEndpointPath();

    abstract protected function createResponse($data, $statusCode);
}