<?php
$files = [
    'app/Http/Controllers/Admin/ContainerController.php',
    'app/Http/Resources/Admin/ContainerResource.php',
    'app/Http/Requests/Admin/StoreContainerRequest.php',
    'app/Http/Requests/Admin/UpdateContainerRequest.php',
    'app/Services/ContainerService.php',
    'routes/admin.php',
    'database/seeders/RolesAndPermissionsSeeder.php',
    'resources/js/Components/Layout/Sidebar.vue',
    'resources/js/Pages/Admin/Containers/Index.vue',
    'resources/js/Pages/Admin/Containers/Create.vue',
    'resources/js/Pages/Admin/Containers/Edit.vue',
    'resources/js/Pages/Admin/Containers/ContainerForm.vue'
];
foreach($files as $file) {
    if(file_exists($file)) {
        $c = file_get_contents($file);
        $c = str_replace(['Shipments', 'shipments', 'Shipment', 'shipment'], ['Containers', 'containers', 'Container', 'container'], $c);
        file_put_contents($file, $c);
        echo "Updated $file\n";
    } else {
        echo "Missing $file\n";
    }
}
