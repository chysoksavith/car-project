import { DirectiveBinding } from 'vue';
import { router } from '@inertiajs/vue3';

export const can = (el: HTMLElement, binding: DirectiveBinding) => {
    // Access Inertia's shared props via the global router instance
    const user = (router.page?.props as any)?.auth?.user;
    
    if (!user || !user.permissions) {
        el.style.display = 'none';
        return;
    }
    
    // Automatically allow 'super-admin'
    if (user.roles?.includes('super-admin')) return;
    
    const permission = binding.value;
    let hasPermission = false;
    
    if (Array.isArray(permission)) {
        hasPermission = permission.some(p => user.permissions.includes(p));
    } else {
        hasPermission = user.permissions.includes(permission);
    }
    
    if (!hasPermission) {
        // Hiding via display:none is standard for directives to avoid breaking the VDOM
        el.style.display = 'none';
    }
};
