import { ref, computed, watch, Ref } from 'vue';

export function useDependentDropdown<T extends Record<string, any>>(
    sourceList: Ref<T[]> | T[],
    dependentKey: keyof T,
    parentValue: Ref<any>,
    selectedValue: Ref<any>
) {
    const list = computed(() => {
        const source = Array.isArray(sourceList) ? sourceList : sourceList.value;
        if (!parentValue.value) return source;
        return source.filter(item => item[dependentKey] === parentValue.value);
    });

    watch(parentValue, () => {
        if (selectedValue.value) {
            const isValid = list.value.some(item => item.id === selectedValue.value);
            if (!isValid) {
                selectedValue.value = "";
            }
        }
    });

    return {
        filteredList: list
    };
}
