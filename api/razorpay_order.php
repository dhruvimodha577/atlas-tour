<?php
/**
 * Razorpay Order Creation - TEST MODE
 * Creates a ₹1 (100 paise) test order
 * Key & Secret are intentionally empty for testing environment setup
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ==========================================
// RAZORPAY LIVE MODE CREDENTIALS
// Replace with your actual LIVE keys from:
// https://dashboard.razorpay.com/app/keys
// ==========================================
$razorpay_key_id     = "rzp_live_T0E03YO2u78Pfu";  // LIVE key_id
$razorpay_key_secret = "IxFXhxbSWSxuk11XiV1eBUoX";  // LIVE key_secret

// Validate keys exist
if (empty($razorpay_key_id) || empty($razorpay_key_secret)) {
    // Return a mock order for demo/UI testing when keys are not configured
    echo json_encode([
        'success'    => true,
        'mock'       => true,
        'order_id'   => 'order_mock_' . uniqid(),
        'amount'     => 100,
        'currency'   => 'INR',
        'key_id'     => 'rzp_test_demo',
        'message'    => 'Mock order (Razorpay keys not configured)'
    ]);
    exit;
}

// Amount in paise: ₹1 = 100 paise (TEST MODE)
$amount   = 100;
$currency = 'INR';
$receipt  = 'atlas_tour_' . time();

$orderData = [
    'receipt'         => $receipt,
    'amount'          => $amount,
    'currency'        => $currency,
    'payment_capture' => 1
];

$jsonData = json_encode($orderData);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_USERPWD,        $razorpay_key_id . ':' . $razorpay_key_secret);
curl_setopt($ch, CURLOPT_POST,           true);
curl_setopt($ch, CURLOPT_POSTFIELDS,     $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response   = curl_exec($ch);
$httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError  = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['success' => false, 'error' => 'cURL error: ' . $curlError]);
    exit;
}

$order = json_decode($response, true);

if ($httpCode === 200 && isset($order['id'])) {
    echo json_encode([
        'success'  => true,
        'order_id' => $order['id'],
        'amount'   => $order['amount'],
        'currency' => $order['currency'],
        'key_id'   => $razorpay_key_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error'   => $order['error']['description'] ?? 'Unknown Razorpay error',
        'raw'     => $order
    ]);
}
