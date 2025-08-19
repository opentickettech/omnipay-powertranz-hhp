# Omnipay: Powertranz

**Powertranz gateway driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Powertranz support for Omnipay.

## Installation

Via Composer:

```bash
composer require omnipay/powertranz
```

## Usage

### Gateway Initialization

```php
use Omnipay\Omnipay;

// Create the gateway
$gateway = Omnipay::create('Powertranz');

// Configure with your credentials
$gateway->setPowertranzId('your-powertranz-id');
$gateway->setPowertranzPassword('your-powertranz-password');
$gateway->setTestMode(true); // Use false for production
```

### Purchase (Payment Initiation)

This creates a payment request and returns an SPI token for the hosted payment page:

```php
$response = $gateway->purchase([
    'amount' => '10.00',
    'currency' => 'GTQ', // Supports GTQ, USD, EUR, GBP
    'transactionId' => 'ORDER-123456', // Your unique order reference
    'returnUrl' => 'https://yoursite.com/payment/return',
    'description' => 'Order #123456',
])->send();

if ($response->isRedirect()) {
    // Get the SPI token for the payment
    $spiToken = $response->getSpiToken();
    
    // Get the transaction reference for future operations
    $transactionRef = $response->getTransactionReference();
    
    // Redirect to the payment page
    $response->redirect();
} else {
    // Payment initiation failed
    echo $response->getMessage();
}
```

### Complete Purchase (Process Return)

After the customer completes payment and returns to your site:

```php
// The SPI token will be in the return URL parameters
$response = $gateway->completePurchase([
    'spiToken' => $_GET['SpiToken'], // Automatically captured from request if not provided
])->send();

if ($response->isSuccessful()) {
    // Payment was successful
    $transactionRef = $response->getTransactionReference();
    $authCode = $response->getAuthorizationCode();
    $maskedCard = $response->getMaskedCard();
    
    echo "Payment successful! Reference: " . $transactionRef;
} else {
    // Payment failed
    echo "Payment failed: " . $response->getMessage();
}
```

### Fetch Transaction Details

Query the status of a transaction:

```php
$response = $gateway->fetchTransaction([
    'transactionReference' => 'TXN-123456',
])->send();

if ($response->isSuccessful()) {
    $isPaid = $response->isPaid();
    $amount = $response->getAmount();
    $currency = $response->getCurrency();
    $status = $response->getTransactionStatus();
    $date = $response->getTransactionDate();
    
    echo "Transaction " . ($isPaid ? "is paid" : "is not paid");
} else {
    echo "Transaction not found: " . $response->getMessage();
}
```

### Refund Transaction

Process a full or partial refund:

```php
$response = $gateway->refund([
    'transactionReference' => 'TXN-123456',
    'amount' => '5.00', // Partial refund of 5.00
    'currency' => 'GTQ',
])->send();

if ($response->isSuccessful()) {
    $refundRef = $response->getTransactionReference();
    echo "Refund successful! Reference: " . $refundRef;
} else {
    echo "Refund failed: " . $response->getMessage();
}
```

## Advanced Configuration

### Custom 3D Secure Settings

```php
$response = $gateway->purchase([
    'amount' => '10.00',
    'currency' => 'GTQ',
    'transactionId' => 'ORDER-123456',
    'returnUrl' => 'https://yoursite.com/return',
    
    // Custom 3D Secure parameters
    'challengeIndicator' => '03', // Challenge preference
    'challengeWindowSize' => 4,    // Window size for challenge
    'authenticationIndicator' => '04', // Authentication type
    
    // Custom hosted page settings
    'pageName' => 'MyCustomPage',
    'pageSet' => 'PTZ/MyPageSet',
])->send();
```

## Currency Support

The gateway supports **all ISO 4217 currency codes** with automatic numeric code conversion. You can use either the 3-letter ISO code (e.g., 'USD', 'EUR', 'GTQ') or the 3-digit numeric code (e.g., '840', '978', '320').

Common currencies include:
- GTQ (320) - Guatemalan Quetzal
- USD (840) - US Dollar
- EUR (978) - Euro
- GBP (826) - British Pound Sterling
- CAD (124) - Canadian Dollar
- AUD (036) - Australian Dollar
- JPY (392) - Japanese Yen
- CNY (156) - Chinese Yuan
- INR (356) - Indian Rupee
- MXN (484) - Mexican Peso
- BRL (986) - Brazilian Real

The gateway automatically converts ISO currency codes to their numeric equivalents as required by the Powertranz API. All 180+ world currencies are supported.

## Testing

Run the unit tests:

```bash
composer test
```

Run code style checks:

```bash
composer check-style
```

Fix code style issues:

```bash
composer fix-style
```

## API Endpoints

The gateway uses the following Powertranz API endpoints:

- **Test Environment**: `https://staging.ptranz.com`
- **Production Environment**: `https://ptranz.com`

### Endpoints Used:

- `/Api/Spi/Auth` - Payment initiation (SPI token generation)
- `/Api/spi/Payment` - Complete payment (process SPI token)
- `/Api/refund` - Process refunds
- `/Api/Transactions/{id}` - Fetch transaction details

## Response Codes

Common ISO response codes returned by the gateway:

- `00` - Approved/Success
- `05` - Declined
- `99` - General error/Not found

## Security

- Never expose your Powertranz credentials in client-side code
- Always validate the transaction status server-side
- Use HTTPS for all API communications
- Store sensitive transaction data securely

## Support

For gateway-specific issues, contact Powertranz support.
For library issues, please use the [GitHub issue tracker](https://github.com/omnipay/omnipay-powertranz/issues).

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).