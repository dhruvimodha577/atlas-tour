<?php
/**
 * Razorpay Payment Verification - TEST MODE
 * Verifies the HMAC SHA256 signature after payment success
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ==========================================
// RAZORPAY TEST MODE SECRET KEY
// ==========================================
$razorpay_key_secret = "";  // Same secret as in razorpay_order.php

$input = json_decode(file_get_contents('php://input'), true);

$razorpay_payment_id  = $input['razorpay_payment_id']  ?? '';
$razorpay_order_id    = $input['razorpay_order_id']    ?? '';
$razorpay_signature   = $input['razorpay_signature']   ?? '';
$mock                 = $input['mock']                  ?? false;

// If mock mode (keys not configured), skip signature check
if ($mock) {
    echo json_encode([
        'success' => true,
        'mock'    => true,
        'message' => 'Mock payment verified (Test Mode - No real charge)'
    ]);
    exit;
}

if (empty($razorpay_key_secret)) {
    echo json_encode(['success' => false, 'error' => 'Razorpay secret key not configured']);
    exit;
}

if (empty($razorpay_payment_id) || empty($razorpay_order_id) || empty($razorpay_signature)) {
    echo json_encode(['success' => false, 'error' => 'Missing payment parameters']);
    exit;
}

// Verify HMAC SHA256 signature
$generated_signature = hash_hmac(
    'sha256',
    $razorpay_order_id . '|' . $razorpay_payment_id,
    $razorpay_key_secret
);

if (hash_equals($generated_signature, $razorpay_signature)) {
    echo json_encode([
        'success'    => true,
        'payment_id' => $razorpay_payment_id,
        'order_id'   => $razorpay_order_id,
        'message'    => 'Payment of ₹1 verified successfully (Test Mode)'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error'   => 'Payment signature verification failed'
    ]);
}
