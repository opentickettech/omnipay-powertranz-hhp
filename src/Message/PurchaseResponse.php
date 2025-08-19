<?php

namespace Omnipay\Powertranz\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    public function __construct($request, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        return false; // Purchase always requires redirect
    }

    public function isPending()
    {
        return true; // Purchase always requires redirect
    }

    public function isRedirect()
    {
        return isset($this->data['SpiToken']) && !empty($this->data['SpiToken']);
    }

    // Powertranz does not directly implement hosted payment page redirect, use Spi token together with RedirectData
    public function getRedirectUrl()
    {
        return null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return isset($this->data['RedirectData']) ? $this->data['RedirectData'] : null;
    }

    public function getSpiToken()
    {
        return isset($this->data['SpiToken']) ? $this->data['SpiToken'] : null;
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
        return isset($this->data['ResponseCode']) ? $this->data['ResponseCode'] : null;
    }
}