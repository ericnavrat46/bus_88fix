<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->make('router')->post('/api/test-promo', [\App\Http\Controllers\PublicPromoController::class, 'validatePromo']);
$request = Illuminate\Http\Request::create(
    '/api/test-promo',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
    json_encode(['promo_code' => 'DISNEY2026', 'target_type' => 'ticket', 'amount' => 450000])
);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
