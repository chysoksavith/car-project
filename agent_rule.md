# Agent Rules & Codebase Standards

When writing or refactoring code for this project, you **MUST** adhere to the following architectural guidelines to maintain a clean, reusable, and consistent codebase.

## 1. Frontend Architecture (Vue 3 + Inertia + Tailwind/DaisyUI)

### Reusable Components First
Do not duplicate UI elements across pages. Always use or create centralized components in `resources/js/Components/`.
- **Buttons**: Use `<Button variant="..." size="..." icon="...">` instead of raw `<button class="btn...">`.
- **Modals**: Use `<Modal maxWidth="...">` instead of raw `<dialog class="modal">` structures.
- **Page Headers**: Use `<PageHeader title="..." description="...">` instead of manually styling flex containers and `h1` tags.
- **Action Buttons**: Use `<TableActionButtons @edit="..." @delete="...">` inside data tables or lists.
- **Forms**: Use components inside `resources/js/Components/Form/` (e.g., `<TextInput>`, `<SelectInput>`) rather than raw `<input>` or `<label>` tags.

### Icons and Assets
- **NO INLINE SVGs**. Do not copy-paste raw `<svg>` tags into Vue files. 
- **FontAwesome Only**: Use the installed FontAwesome library (e.g., `<i class="fa-solid fa-plus"></i>`).

### Notifications and Feedback
- **Do NOT manually code flash messages or `<div class="alert">` blocks in pages**.
- The `DashboardLayout.vue` globally watches `$page.props.flash` and automatically triggers `vue-toastification` popups. To show a message, simply return it from the Laravel backend (`return back()->with('success', 'Message');`).

---

## 2. Backend Architecture (Laravel)

### Request Flow Pattern
Adhere strictly to the **Controller → Service → Resource** pattern.
1. **Form Requests**: All validation must happen in a dedicated Form Request class (e.g., `StoreRoleRequest.php`), not in the controller.
2. **Controllers**: Controllers should be incredibly thin. Their only job is to receive the request, call the Service class, and return the Inertia view or redirect.
3. **Services**: All business logic (creating, updating, deleting models, complex calculations) lives in `app/Services/`.
4. **Resources**: API responses or props passed to Inertia should be formatted using API Resources (`app/Http/Resources/`) to encapsulate data serialization and hide hidden fields.

### Database & Seeders
- Seeders should be clean and granular.
- Keep domain logic isolated (e.g., `RolesAndPermissionsSeeder`, `UserSeeder`) and do not mix distinct concerns in a single file.
- Use model factories for standard test data generation.

### Naming Conventions
- Use standard RESTful resource methods in controllers (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).
- Vue views should match the controller structure (`Pages/Admin/Users/Index.vue`, `Pages/Admin/Roles/Create.vue`).
- Vue component filenames must use PascalCase (`PageHeader.vue`).
