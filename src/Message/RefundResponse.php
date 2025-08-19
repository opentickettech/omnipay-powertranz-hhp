<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Message\AbstractResponse;

class RefundResponse extends AbstractResponse
{
    protected $statusCode;

    public function __construct($request, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        return isset($this->data['IsoResponseCode']) && $this->data['IsoResponseCode'] === '00';
    }

    public function getTransactionReference()
    {
        return isset($this->data['TransactionIdentifier']) ? $this->data['TransactionIdentifier'] : null;
    }

    public function getMessage()
    {
        if (isset($this->data['ResponseMessage'])) {
            return $this->data['ResponseMessage'];
        }

        if (isset($this->data['Message'])) {
            return $this->data['Message'];
        }

        return null;
    }

    public function getCode()
    {
        return isset($this->data['IsoResponseCode']) ? $this->data['IsoResponseCode'] : null;
    }

    public function getAmount()
    {
        if (isset($this->data['TotalAmount'])) {
            return $this->data['TotalAmount'] / 100;
        }
        return null;
    }

    public function getCurrency()
    {
        return isset($this->data['CurrencyCode']) ? $this->data['CurrencyCode'] : null;
    }
}