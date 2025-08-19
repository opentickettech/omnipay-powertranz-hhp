<?php

namespace Omnipay\Powertranz\Tests;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Powertranz\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPowertranzId('your-powertranz-id');
        $this->gateway->setPowertranzPassword('your-powertranz-password');
        $this->gateway->setTestMode(true);
    }

    public function testGatewayParameters()
    {
        $this->assertSame('your-powertranz-id', $this->gateway->getPowertranzId());
        $this->assertSame('your-powertranz-password', $this->gateway->getPowertranzPassword());
        $this->assertTrue($this->gateway->getTestMode());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'currency' => 'GTQ',
            'transactionId' => 'TEST-ORDER-123',
            'returnUrl' => 'https://example.com/return',
        ]);

        $this->assertInstanceOf(\Omnipay\Powertranz\Message\PurchaseRequest::class, $request);

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('TEST-SPI-TOKEN-123', $response->getSpiToken());
        $this->assertSame('TXN-123456', $response->getTransactionReference());
        $this->assertNull($response->getRedirectUrl()); // Build custom redirect, so returns null
    }

    public function testCompletePurchaseSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $request = $this->gateway->completePurchase([
            'spiToken' => 'TEST-SPI-TOKEN-123',
        ]);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('00', $response->getCode());
        $this->assertSame('TXN-123456', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $request = $this->gateway->completePurchase([
            'spiToken' => 'INVALID-TOKEN',
        ]);

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('05', $response->getCode());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $request = $this->gateway->refund([
            'transactionReference' => 'TXN-123456',
            'amount' => '5.00',
            'currency' => 'GTQ',
        ]);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('00', $response->getCode());
        $this->assertSame('REFUND-789', $response->getTransactionReference());
    }

    public function testFetchTransactionSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        $request = $this->gateway->fetchTransaction([
            'transactionReference' => 'TXN-123456',
        ]);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isPaid());
        $this->assertSame('00', $response->getCode());
        $this->assertSame('TXN-123456', $response->getTransactionReference());
        $this->assertEquals(10.00, $response->getAmount());
    }

    public function testFetchTransactionNotFound()
    {
        $this->setMockHttpResponse('FetchTransactionNotFound.txt');

        $request = $this->gateway->fetchTransaction([
            'transactionReference' => 'INVALID-TXN',
        ]);

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isPaid());
        $this->assertSame('Transaction not found', $response->getMessage());
    }
}