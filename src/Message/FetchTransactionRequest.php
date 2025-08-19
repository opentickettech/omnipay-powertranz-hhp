<?php

namespace Omnipay\Powertranz\Message;

class FetchTransactionRequest extends AbstractRequest
{
    protected function getEndpointPath()
    {
        return '/Api/Transactions/' . $this->getTransactionReference();
    }

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getData()
    {
        $this->validate('transactionReference');
        
        return [];
    }

    public function sendData($data)
    {
        $headers = $this->getHeaders();
        
        $httpResponse = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint() . $this->getEndpointPath(),
            $headers
        );

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->createResponse($responseData, $httpResponse->getStatusCode());
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new FetchTransactionResponse($this, $data, $statusCode);
    }

    public function getHeaders()
    {
        return [
            'Accept' => 'text/plain',
            'Content-Type' => 'application/json',
            'PowerTranz-PowerTranzId' => $this->getPowertranzId(),
            'PowerTranz-PowerTranzPassword' => $this->getPowertranzPassword(),
        ];
    }
}