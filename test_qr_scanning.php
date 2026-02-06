<?php

/**
 * QR Code Scanning Test Script
 *
 * This script tests the QR attendance system with various QR code formats
 * to ensure the fixes handle all edge cases properly.
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\AttendanceController;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Mock QR code formats to test
$testQrCodes = [
    // Valid JSON format
    'json_valid' => '{"user_id": 1, "event_id": 1, "timestamp": ' . time() . '}',

    // Plain number
    'plain_number' => '1',

    // URL format
    'url_format' => '/attendance/scan/1',

    // Malformed JSON
    'json_malformed' => '{"user_id": 1, "event_id":',

    // Empty string
    'empty' => '',

    // Random text
    'random_text' => 'hello world',

    // JSON with wrong event
    'json_wrong_event' => '{"user_id": 1, "event_id": 999, "timestamp": ' . time() . '}',

    // Expired JSON
    'json_expired' => '{"user_id": 1, "event_id": 1, "timestamp": ' . (time() - 86401) . '}',
];

echo "=== QR Attendance System Test ===\n\n";

foreach ($testQrCodes as $testName => $qrCode) {
    echo "Testing: $testName\n";
    echo "QR Code: '$qrCode'\n";

    // Simulate the request that would come from the frontend
    $request = new Request();
    $request->merge([
        'event_id' => 1,
        'participant_id' => 1,
        'qr_data' => $qrCode ? json_encode(json_decode($qrCode, true) ?: null) : null,
    ]);

    // Create controller instance
    $controller = new AttendanceController();

    try {
        // Mock authentication (assuming pengurus majlis is logged in)
        // In real testing, you'd need to set up proper authentication

        $response = $controller->record($request);
        $responseData = json_decode($response->getContent(), true);

        echo "Response: " . json_encode($responseData) . "\n";
        echo "Status: " . ($responseData['success'] ? 'SUCCESS' : 'FAILED') . "\n";

    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }

    echo "------------------------\n\n";
}

echo "Test completed. Check the results above.\n";
echo "Expected results:\n";
echo "- json_valid: Should succeed\n";
echo "- plain_number: Should succeed (if user exists)\n";
echo "- url_format: Should succeed (if user exists)\n";
echo "- json_malformed: Should fail gracefully\n";
echo "- empty: Should succeed (if user exists)\n";
echo "- random_text: Should fail gracefully\n";
echo "- json_wrong_event: Should fail\n";
echo "- json_expired: Should fail\n";
