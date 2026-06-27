export interface InspectionItem {
    id: number;
    parent_id: number | null;
    name_kh: string;
    name_en: string | null;
    default_price: number;
    is_active: boolean;
    parent?: {
        id: number;
        name_kh: string;
    };
}

export interface InspectionItemFormData {
    parent_id: number | null | string;
    name_kh: string;
    name_en: string | null;
    default_price: number;
    is_active: boolean;
}

export interface InspectionItemCategory {
    id: number;
    name_kh: string;
    name_en: string | null;
}

export interface CarInspection {
    id: number;
    car_id: number | null;
    inspection_item_id: number;
    cost: number;
    condition: string | null;
    note: string | null;
    inspection_item: {
        id: number;
        name_kh: string;
        name_en: string | null;
        default_price: number;
        parent_id: number | null;
        parent?: {
            id: number;
            name_kh: string;
            name_en: string | null;
        };
    };
}

export interface CarInspectionFormData {
    cost: number;
    condition: string | null;
    note: string | null;
}
