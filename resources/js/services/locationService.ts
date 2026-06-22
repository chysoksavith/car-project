import api from "@/lib/api";

// # Types
export interface LocationOption {
    code: string;
    name_en: string;
    name_km: string;
}

export type LocationType = "province" | "district" | "commune" | "village";

// # Session cache
const cache = new Map<string, LocationOption[]>();

// # Cache Key
function cacheKey(type: LocationType, parentCode?: string): string {
    return `${type}:${parentCode ?? "root"}`;
}

// # Fetch with cache
export async function getLocations(
    type: LocationType,
    parentCode?: string,
): Promise<LocationOption[]> {
    const key = cacheKey(type, parentCode);

    if (cache.has(key)) return cache.get(key)!;

    const res = await api.get<LocationOption[]>(route("admin.locations.index"), {
        params: { type, ...(parentCode ? { parent_code: parentCode } : {}) },
    });

    cache.set(key, res.data);
    return res.data;
}

// # Preload provinces on app boot
export function preloadProvinces(): void {
    getLocations("province");
}
