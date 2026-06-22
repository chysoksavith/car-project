<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = app(App\Services\ContainerService::class);
    
    // Auth login as a user
    $user = App\Models\User::first();
    auth()->login($user);

    $file1 = \Illuminate\Http\UploadedFile::fake()->image('car1.jpg');
    $file2 = \Illuminate\Http\UploadedFile::fake()->image('car2.jpg');

    $data = [
        'supplier_id' => 1,
        'bl_number' => 'BL_TEST_' . time(),
        'container_number' => 'CONT_TEST_' . time(),
        'status' => 'on_the_way',
        'cars' => [
            [
                'name' => 'Car 1',
                'maker_id' => 1,
                'images' => [
                    $file1,
                    $file2,
                ]
            ]
        ]
    ];

    $container = $service->createContainer($data);
    echo "Success! Container created with ID " . $container->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
