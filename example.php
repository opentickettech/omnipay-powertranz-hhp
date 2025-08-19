<?php

require_once __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;

// Parse command line arguments
if($argc > 1) {
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
}

// Helper function for output
function dd($var) {
    var_dump($var);
    exit;
}

// Initialize the gateway
$gateway = Omnipay::create('Powertranz');
$gateway->setPowertranzId('your-powertranz-id');
$gateway->setPowertranzPassword('your-powertranz-password');
$gateway->setTestMode(true);

// Example 1: Complete Purchase (Process SPI Token)
if (isset($_GET['spi'])) {
    try {
        $response = $gateway->completePurchase([
            'spiToken' => $_GET['spi'],
        ])->send();

        $data = [
            'success' => $response->isSuccessful(),
            'transaction_reference' => $response->getTransactionReference(),
            'message' => $response->getMessage(),
            'code' => $response->getCode(),
            'auth_code' => $response->getAuthorizationCode(),
            'masked_card' => $response->getMaskedCard(),
            'amount' => $response->getAmount(),
            'currency' => $response->getCurrency(),
        ];

        dd($data);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        dd($e->getMessage());
    }
}

// Example 2: Refund Transaction
if (isset($_GET['amount']) && isset($_GET['transaction_id'])) {
    try {
        $response = $gateway->refund([
            'transactionReference' => $_GET['transaction_id'],
            'amount' => $_GET['amount'],
            'currency' => 'GTQ', // Or get from $_GET['currency']
        ])->send();

        $data = [
            'success' => $response->isSuccessful(),
            'transaction_reference' => $response->getTransactionReference(),
            'message' => $response->getMessage(),
            'code' => $response->getCode(),
            'refunded_amount' => $response->getAmount(),
        ];

        dd($data);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        dd($e->getMessage());
    }
}

// Example 3: Fetch Transaction Details
if (isset($_GET['transaction_id']) && !isset($_GET['amount'])) {
    try {
        $response = $gateway->fetchTransaction([
            'transactionReference' => $_GET['transaction_id'],
        ])->send();

        $data = [
            'success' => $response->isSuccessful(),
            'is_paid' => $response->isPaid(),
            'transaction_reference' => $response->getTransactionReference(),
            'order_id' => $response->getTransactionId(),
            'message' => $response->getMessage(),
            'code' => $response->getCode(),
            'amount' => $response->getAmount(),
            'currency' => $response->getCurrency(),
            'card_type' => $response->getCardType(),
            'masked_card' => $response->getMaskedCard(),
            'transaction_date' => $response->getTransactionDate(),
            'transaction_status' => $response->getTransactionStatus(),
        ];

        dd($data);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        dd($e->getMessage());
    }
}

// Example 4: Initiate Purchase (Start Payment)
if (isset($_GET['start'])) {
    try {
        $orderId = 'ORDER-' . uniqid() . '-' . time();

        $response = $gateway->purchase([
            'amount' => 1.00, // Or get from $_GET['amount']
            'currency' => 'GTQ',
            'transactionId' => $orderId,
            'returnUrl' => 'https://openticket.tech/' . time(),
            'description' => 'Test payment',

            // Optional parameters
            'pageName' => 'PageName',
            'pageSet' => 'PTZ/PageSet',
            'challengeIndicator' => '03',
            'challengeWindowSize' => 4,
            'authenticationIndicator' => '04',
        ])->send();

        if ($response->isRedirect()) {
            $data = [
                'success' => true,
                'redirect_required' => true,
                'spi_token' => $response->getSpiToken(),
                'transaction_reference' => $response->getTransactionReference(),
                'redirect_data' => $response->getRedirectData(),
                'redirect_url' => 'https://ev3b.s3.eu-west-1.amazonaws.com/tmp/powertranz_payment_form.html?SpiToken='.$response->getSpiToken(),
                'order_id' => $orderId,
                'message' => $response->getMessage(),
            ];
        } else {
            $data = [
                'success' => false,
                'message' => $response->getMessage(),
                'code' => $response->getCode(),
            ];
        }

        dd($data);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        dd($e->getMessage());
    }
}

// Default: Show usage
echo "Omnipay Powertranz Gateway Examples\n";
echo "====================================\n\n";
echo "Usage:\n";
echo "  Start payment:         php example.php start=1\n";
echo "  Complete payment:      php example.php spi=YOUR_SPI_TOKEN\n";
echo "  Fetch transaction:     php example.php transaction_id=TXN_ID\n";
echo "  Refund transaction:    php example.php transaction_id=TXN_ID amount=5.00\n";
echo "\n";
echo "Optional parameters:\n";
echo "  amount=10.00          Payment amount\n";
echo "  currency=GTQ          Currency code (GTQ, USD, EUR, GBP)\n";
echo "\n";