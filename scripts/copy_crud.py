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
    ('Maker', 'Fuel'),
    ('maker', 'fuel'),
    ('Makers', 'Fuels'),
    ('makers', 'fuels'),
]

files_to_copy = [
    ('app/Http/Controllers/Admin/MakerController.php', 'app/Http/Controllers/Admin/FuelController.php'),
    ('app/Services/MakerService.php', 'app/Services/FuelService.php'),
    ('app/Http/Requests/Admin/Maker/StoreMakerRequest.php', 'app/Http/Requests/Admin/Fuel/StoreFuelRequest.php'),
    ('app/Http/Requests/Admin/Maker/UpdateMakerRequest.php', 'app/Http/Requests/Admin/Fuel/UpdateFuelRequest.php'),
    ('app/Http/Resources/Admin/MakerResource.php', 'app/Http/Resources/Admin/FuelResource.php'),
    ('resources/js/Pages/Admin/Makers/Index.vue', 'resources/js/Pages/Admin/Fuels/Index.vue'),
    ('resources/js/Pages/Admin/Makers/Create.vue', 'resources/js/Pages/Admin/Fuels/Create.vue'),
    ('resources/js/Pages/Admin/Makers/Edit.vue', 'resources/js/Pages/Admin/Fuels/Edit.vue'),
    ('resources/js/Pages/Admin/Makers/MakerForm.vue', 'resources/js/Pages/Admin/Fuels/FuelForm.vue'),
]

for src, dest in files_to_copy:
    if os.path.exists(src):
        replace_in_file(src, dest, replacements)
        print(f"Copied {src} to {dest}")
    else:
        print(f"Not found: {src}")
