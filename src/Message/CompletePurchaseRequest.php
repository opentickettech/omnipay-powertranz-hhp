<?php

namespace Omnipay\Powertranz\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    protected function getEndpointPath()
    {
        return '/Api/spi/Payment';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getHeaders()
    {
        return [
            'Accept' => 'text/plain',
            'Content-Type' => 'application/json-patch+json',
        ];
    }

    public function getData()
    {
        $spiToken = $this->getSpiToken();
        
        if (empty($spiToken)) {
            $this->validate('spiToken');
        }

        return $spiToken;
    }

    public function sendData($data)
    {
        $headers = $this->getHeaders();
        
        // Send the SPI token as a JSON string
        $body = json_encode($data);
        
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint() . $this->getEndpointPath(),
            $headers,
            $body
        );

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->createResponse($responseData, $httpResponse->getStatusCode());
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CompletePurchaseResponse($this, $data, $statusCode);
    }

    public function getSpiToken()
    {
        // Check if SPI token is in the request parameters
        $spiToken = $this->getParameter('spiToken');
        
        // If not set explicitly, try to get it from the HTTP request
        if (empty($spiToken)) {
            $spiToken = $this->httpRequest->query->get('SpiToken');
            if (empty($spiToken)) {
                $spiToken = $this->httpRequest->request->get('SpiToken');
            }
        }
        
        return $spiToken;
    }

    public function setSpiToken($value)
    {
        return $this->setParameter('spiToken', $value);
    }
}