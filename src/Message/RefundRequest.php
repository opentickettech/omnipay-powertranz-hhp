<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Powertranz\Traits\CurrencyConversionTrait;

class RefundRequest extends AbstractRequest
{
    use CurrencyConversionTrait;
    protected function getEndpointPath()
    {
        return '/Api/refund';
    }

    public function getData()
    {
        $this->validate('transactionReference', 'currency');
        if (!$this->getParameter('amount')) {
            throw new InvalidRequestException('The amount parameter is required');
        }

        $data = [
            'Refund' => true,
            'TransactionIdentifier' => $this->getTransactionReference(),
            'TotalAmount' => (int) (round($this->getParameter('amount') * 100)),
            'CurrencyCode' => $this->getCurrencyNumeric(),
            'Source' => [
                'CardPresent' => false,
                'CardEmvFallback' => false,
                'ManualEntry' => false,
                'Debit' => false,
                'Contactless' => false,
                'CardPan' => '',
                'MaskedPan' => ''
            ],
            'TerminalCode' => '',
            'TerminalSerialNumber' => '',
            'AddressMatch' => false
        ];

        return $data;
    }

    public function getHeaders()
    {
        return [
            'Accept' => 'text/plain',
            'Content-Type' => 'application/json-patch+json',
            'PowerTranz-PowerTranzId' => $this->getPowertranzId(),
            'PowerTranz-PowerTranzPassword' => $this->getPowertranzPassword(),
        ];
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new RefundResponse($this, $data, $statusCode);
    }
}