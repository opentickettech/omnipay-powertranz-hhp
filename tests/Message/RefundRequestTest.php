<?php

namespace Omnipay\Powertranz\Tests\Message;

use Omnipay\Powertranz\Message\RefundRequest;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'powertranzId' => 'your-powertranz-id',
            'powertranzPassword' => 'test-password',
            'transactionReference' => 'TXN-123456',
            'amount' => '5.00',
            'currency' => 'GTQ',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertTrue($data['Refund']);
        $this->assertSame('TXN-123456', $data['TransactionIdentifier']);
        $this->assertSame(500, $data['TotalAmount']);
        $this->assertSame('320', $data['CurrencyCode']);
        $this->assertFalse($data['AddressMatch']);
        $this->assertIsArray($data['Source']);
        $this->assertFalse($data['Source']['CardPresent']);
    }

    public function testHeaders()
    {
        $headers = $this->request->getHeaders();

        $this->assertSame('text/plain', $headers['Accept']);
        $this->assertSame('application/json-patch+json', $headers['Content-Type']);
        $this->assertSame('your-powertranz-id', $headers['PowerTranz-PowerTranzId']);
        $this->assertSame('test-password', $headers['PowerTranz-PowerTranzPassword']);
    }

    public function testMissingTransactionReference()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setAmount('5.00');
        $request->setCurrency('GTQ');
        $request->getData();
    }
}