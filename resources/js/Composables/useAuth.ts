import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface AuthUser {
    id: number;
    name: string;
    email: string;
    initials: string;
}

interface SharedProps {
    auth: {
        user: AuthUser | null;
    };
    [key: string]: unknown;
}

/**
 * Provides convenient access to the authenticated user
 * shared by HandleInertiaRequests.
 */
export function useAuth() {
    const page = usePage<SharedProps>();

    const user = computed<AuthUser | null>(() => page.props.auth?.user ?? null);
    const isAuthenticated = computed(() => user.value !== null);

    return { user, isAuthenticated };
}
