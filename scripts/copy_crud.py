import os
import re

def replace_in_file(src, dest, replacements):
    with open(src, 'r') as f:
        content = f.read()
    for old, new in replacements:
        content = content.replace(old, new)
    os.makedirs(os.path.dirname(dest), exist_ok=True)
    with open(dest, 'w') as f:
        f.write(content)

replacements = [
    ('Fuel', 'Department'),
    ('fuel', 'department'),
    ('Fuels', 'Departments'),
    ('fuels', 'departments'),
]

files_to_copy = [
    ('app/Http/Controllers/Admin/FuelController.php', 'app/Http/Controllers/Admin/DepartmentController.php'),
    ('app/Services/FuelService.php', 'app/Services/DepartmentService.php'),
    ('app/Http/Requests/Admin/Fuel/StoreFuelRequest.php', 'app/Http/Requests/Admin/Department/StoreDepartmentRequest.php'),
    ('app/Http/Requests/Admin/Fuel/UpdateFuelRequest.php', 'app/Http/Requests/Admin/Department/UpdateDepartmentRequest.php'),
    ('app/Http/Resources/Admin/FuelResource.php', 'app/Http/Resources/Admin/DepartmentResource.php'),
    ('resources/js/Pages/Admin/Fuels/Index.vue', 'resources/js/Pages/Admin/Departments/Index.vue'),
    ('resources/js/Pages/Admin/Fuels/Create.vue', 'resources/js/Pages/Admin/Departments/Create.vue'),
    ('resources/js/Pages/Admin/Fuels/Edit.vue', 'resources/js/Pages/Admin/Departments/Edit.vue'),
    ('resources/js/Pages/Admin/Fuels/FuelForm.vue', 'resources/js/Pages/Admin/Departments/DepartmentForm.vue'),
]

for src, dest in files_to_copy:
    if os.path.exists(src):
        replace_in_file(src, dest, replacements)
        print(f"Copied {src} to {dest}")
    else:
        print(f"Not found: {src}")
