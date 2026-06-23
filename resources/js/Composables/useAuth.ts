import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface AuthUser {
    id: number;
    name: string;
    email: string;
    initials: string;
    roles: string[];
    permissions: string[];
}

interface SharedProps {
    auth: {
        user: AuthUser | null;
    };
    [key: string]: unknown;
}

// # Provides convenient access to the authenticated user,
export function useAuth() {
    const page = usePage<SharedProps>();

    const user = computed<AuthUser | null>(() => page.props.auth?.user ?? null);
    const isAuthenticated = computed(() => user.value !== null);

    // # Check if the user has a specific role
    const hasRole = (role: string): boolean => {
        return user.value?.roles.includes(role) ?? false;
    };

    // # Check if the user has a specific permission
    const can = (permission: string): boolean => {
        if (hasRole('super-admin')) {
            return true;
        }
        return user.value?.permissions.includes(permission) ?? false;
    };

    // # Check if the user has any of the given permissions
    const canAny = (...permissions: string[]): boolean => {
        return permissions.some((p) => can(p));
    };

    // # Check if the user has all of the given permissions
    const canAll = (...permissions: string[]): boolean => {
        return permissions.every((p) => can(p));
    };


    // # Check if the user has any of the given roles
    const hasAnyRole = (...roles: string[]): boolean => {
        return roles.some((r) => hasRole(r));
    };

    return {
        user,
        isAuthenticated,
        can,
        canAny,
        canAll,
        hasRole,
        hasAnyRole,
    };
}
