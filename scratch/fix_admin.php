<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'admin@bus88.com')->first();
if (!$user) {
    User::create([
        'name' => 'Administrator',
        'email' => 'admin@bus88.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'phone' => '081234567890',
    ]);
    echo "Admin created successfully.\n";
} else {
    $user->update([
        'password' => Hash::make('password'),
        'role' => 'admin'
    ]);
    echo "Admin updated successfully.\n";
}
