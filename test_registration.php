<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

// Simulate a registration request
$email = 'test' . time() . '@example.com'; // Unique email
$request = new Request();
$request->merge([
    'name' => 'Test User',
    'email' => $email,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'client',
]);

$controller = new AuthController();
try {
    $response = $controller->register($request);
    echo "Registration successful!\n";
    echo "Response: " . $response . "\n";
} catch (Exception $e) {
    echo "Registration failed: " . $e->getMessage() . "\n";
}

// Check if user was created
$user = \App\Models\User::where('email', $email)->first();
if ($user) {
    echo "User created: " . $user->name . " (" . $user->email . ")\n";
} else {
    echo "User not found in database.\n";
}

// Log out the user
\Auth::logout();
echo "User logged out.\n";
