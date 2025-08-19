<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchTransactionResponse extends AbstractResponse
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

    public function isPaid()
    {
        return $this->isSuccessful();
    }

    public function getTransactionReference()
    {
        return isset($this->data['TransactionIdentifier']) ? $this->data['TransactionIdentifier'] : null;
    }

    public function getTransactionId()
    {
        return isset($this->data['OrderIdentifier']) ? $this->data['OrderIdentifier'] : null;
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

    public function getAuthorizationCode()
    {
        return isset($this->data['AuthCode']) ? $this->data['AuthCode'] : null;
    }

    public function getCardType()
    {
        return isset($this->data['CardType']) ? $this->data['CardType'] : null;
    }

    public function getMaskedCard()
    {
        return isset($this->data['MaskedPan']) ? $this->data['MaskedPan'] : null;
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

    public function getTransactionDate()
    {
        return isset($this->data['TransactionDate']) ? $this->data['TransactionDate'] : null;
    }

    public function getTransactionType()
    {
        return isset($this->data['TransactionType']) ? $this->data['TransactionType'] : null;
    }

    public function getTransactionStatus()
    {
        return isset($this->data['TransactionStatus']) ? $this->data['TransactionStatus'] : null;
    }
}