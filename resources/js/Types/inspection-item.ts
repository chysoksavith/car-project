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
