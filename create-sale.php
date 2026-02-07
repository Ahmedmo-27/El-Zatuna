<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\User::find(1); // Change this to your user ID
$webinar = \App\Models\Webinar::first(); // Get first available webinar

if (!$webinar) {
    echo "No webinar found in the database!\n";
    exit(1);
}

echo "Creating sale for:\n";
echo "User: {$user->full_name} (ID: {$user->id})\n";
echo "Webinar: {$webinar->title} (ID: {$webinar->id})\n";
echo "Price: {$webinar->price}\n\n";

// Create Sale record
$sale = \App\Models\Sale::create([
    'buyer_id' => $user->id,
    'seller_id' => $webinar->creator_id ?? $webinar->teacher_id,
    'webinar_id' => $webinar->id,
    'type' => 'webinar',
    'payment_method' => \App\Models\Sale::$paymentChannel,
    'amount' => $webinar->price ?? 0,
    'total_amount' => $webinar->price ?? 0,
    'tax' => 0,
    'commission' => 0,
    'discount' => 0,
    'access_to_purchased_item' => true,
    'created_at' => time(),
]);

echo "✓ Sale created successfully! (ID: {$sale->id})\n";
echo "\nNow check /panel/courses/purchases - your course should appear!\n";
