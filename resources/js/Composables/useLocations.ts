import { ref, watch } from "vue";
import { getLocations } from "@/services/locationService";
import type { LocationOption } from "@/services/locationService";

export type { LocationOption };

// # Types
export interface LocationAddress {
    province: string;
    district: string;
    commune: string;
    village: string;
    house_no: string;
    road_no: string;
    address: string;
}

// # Composable
export function useLocations(address: LocationAddress) {
    const provinces = ref<LocationOption[]>([]);
    const districts = ref<LocationOption[]>([]);
    const communes  = ref<LocationOption[]>([]);
    const villages  = ref<LocationOption[]>([]);
    const loading   = ref(false);

    // # Init
    async function init() {
        loading.value = true;
        try {
            provinces.value = await getLocations("province");

            if (address.province) {
                districts.value = await getLocations("district", address.province);
            }
            if (address.district) {
                communes.value = await getLocations("commune", address.district);
            }
            if (address.commune) {
                villages.value = await getLocations("village", address.commune);
            }
        } finally {
            loading.value = false;
        }
    }

    // # Cascade — province → district
    watch(
        () => address.province,
        async (val, old) => {
            if (old === undefined) return;
            address.district = "";
            address.commune  = "";
            address.village  = "";
            districts.value  = [];
            communes.value   = [];
            villages.value   = [];
            if (val) districts.value = await getLocations("district", val);
        },
    );

    // # Cascade — district → commune
    watch(
        () => address.district,
        async (val, old) => {
            if (old === undefined) return;
            address.commune = "";
            address.village = "";
            communes.value  = [];
            villages.value  = [];
            if (val) communes.value = await getLocations("commune", val);
        },
    );

    // # Cascade — commune → village
    watch(
        () => address.commune,
        async (val, old) => {
            if (old === undefined) return;
            address.village = "";
            villages.value  = [];
            if (val) villages.value = await getLocations("village", val);
        },
    );

    return { provinces, districts, communes, villages, loading, init };
}
