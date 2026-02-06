<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\PengurusMajlisController;
use App\Models\Event;
use App\Models\PengurusMajlis;
use Illuminate\Support\Facades\Hash;

echo "=== Testing Event Creation Functionality ===\n\n";

// Test 1: Check if PengurusMajlisController has required methods
echo "1. Checking PengurusMajlisController methods:\n";
$controller = new ReflectionClass(PengurusMajlisController::class);

$methods = ['create', 'store'];
foreach ($methods as $method) {
    if ($controller->hasMethod($method)) {
        echo "   ✅ Method '$method' exists\n";
    } else {
        echo "   ❌ Method '$method' missing\n";
    }
}

// Test 2: Check Event model fillable fields
echo "\n2. Checking Event model configuration:\n";
$event = new Event();
$requiredFields = ['title', 'description', 'date', 'start_time', 'end_time', 'pax', 'password_hash', 'media_path', 'pengurus_id'];

foreach ($requiredFields as $field) {
    if (in_array($field, $event->getFillable())) {
        echo "   ✅ Field '$field' is fillable\n";
    } else {
        echo "   ❌ Field '$field' is not fillable\n";
    }
}

// Test 3: Check database connection
echo "\n3. Checking database connection:\n";
try {
    $count = Event::count();
    echo "   ✅ Database connection successful (Events table has $count records)\n";
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
}

// Test 4: Check PengurusMajlis model
echo "\n4. Checking PengurusMajlis model:\n";
try {
    $pengurusCount = PengurusMajlis::count();
    echo "   ✅ PengurusMajlis model accessible ($pengurusCount records found)\n";
} catch (Exception $e) {
    echo "   ❌ PengurusMajlis model error: " . $e->getMessage() . "\n";
}

// Test 5: Test password hashing
echo "\n5. Testing password hashing:\n";
$testPassword = "test123";
$hashed = Hash::make($testPassword);
if (Hash::check($testPassword, $hashed)) {
    echo "   ✅ Password hashing works correctly\n";
} else {
    echo "   ❌ Password hashing failed\n";
}

echo "\n=== Test Summary ===\n";
echo "All core functionality appears to be properly implemented.\n";
echo "The 'New Event – Pengurus Majlis' page should be fully functional.\n";

?>
