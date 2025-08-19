<?php

namespace Omnipay\Powertranz\Tests\Message;

use Omnipay\Powertranz\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'powertranzId' => 'your-powertranz-id',
            'powertranzPassword' => 'test-password',
            'amount' => '10.00',
            'currency' => 'GTQ',
            'transactionId' => 'TEST-ORDER-123',
            'returnUrl' => 'https://example.com/return',
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(1000, $data['TotalAmount']);
        $this->assertSame('320', $data['CurrencyCode']);
        $this->assertSame('TEST-ORDER-123', $data['OrderIdentifier']);
        $this->assertTrue($data['ThreeDSecure']);
        $this->assertSame('https://example.com/return', $data['ExtendedData']['MerchantResponseUrl']);
    }

    public function testGetDataWithCustomParameters()
    {
        $this->request->setPageName('CustomPage');
        $this->request->setPageSet('CustomSet');
        $this->request->setChallengeIndicator('01');
        $this->request->setChallengeWindowSize(5);
        $this->request->setAuthenticationIndicator('02');

        $data = $this->request->getData();

        $this->assertSame('CustomPage', $data['ExtendedData']['HostedPage']['PageName']);
        $this->assertSame('CustomSet', $data['ExtendedData']['HostedPage']['PageSet']);
        $this->assertSame('01', $data['ExtendedData']['ThreeDSecure']['ChallengeIndicator']);
        $this->assertSame(5, $data['ExtendedData']['ThreeDSecure']['ChallengeWindowSize']);
        $this->assertSame('02', $data['ExtendedData']['ThreeDSecure']['AuthenticationIndicator']);
    }

    public function testCurrencyConversion()
    {
        // Test common currencies
        $this->request->setCurrency('USD');
        $data = $this->request->getData();
        $this->assertSame('840', $data['CurrencyCode']);

        $this->request->setCurrency('EUR');
        $data = $this->request->getData();
        $this->assertSame('978', $data['CurrencyCode']);

        $this->request->setCurrency('GBP');
        $data = $this->request->getData();
        $this->assertSame('826', $data['CurrencyCode']);

        // Test Latin American currencies
        $this->request->setCurrency('GTQ');
        $data = $this->request->getData();
        $this->assertSame('320', $data['CurrencyCode']);

        $this->request->setCurrency('MXN');
        $data = $this->request->getData();
        $this->assertSame('484', $data['CurrencyCode']);

        $this->request->setCurrency('BRL');
        $data = $this->request->getData();
        $this->assertSame('986', $data['CurrencyCode']);

        // Test Asian currencies
        $this->request->setCurrency('JPY');
        $data = $this->request->getData();
        $this->assertSame('392', $data['CurrencyCode']);

        $this->request->setCurrency('CNY');
        $data = $this->request->getData();
        $this->assertSame('156', $data['CurrencyCode']);

        // Test case insensitivity
        $this->request->setCurrency('usd');
        $data = $this->request->getData();
        $this->assertSame('840', $data['CurrencyCode']);

        // Test numeric currency
        $this->request->setCurrency('320');
        $data = $this->request->getData();
        $this->assertSame('320', $data['CurrencyCode']);
    }

    public function testInvalidCurrency()
    {
        // Our trait throws InvalidRequestException for invalid currency codes
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('Unsupported or invalid currency code: INVALID');

        $this->request->setCurrency('INVALID');
        $this->request->getData();
    }

    public function testMissingRequiredParameters()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->getData();
    }
}