import axios from "axios";
import { computed, ref } from "vue";

export function useBulkSelect() {
  const selectedItems = ref<number[]>([]);
  const allItems = ref<number[]>([]);
  const loadingAllItems = ref<boolean>(false);

  const indeterminate = computed(
    () => selectedItems.value.length > 0 && !allItemsSelected.value
  );

  const allItemsSelected = computed(
    () =>
      !!allItems.value.length &&
      selectedItems.value.length === allItems.value.length
  );

  const selectAllItems = () => {
    loadingAllItems.value = true;
    axios
      .get(route(route().current()), {
        params: {
          ...route().params,
          bulk_select_all: 1,
        },
      })
      .then((response) => {
        selectedItems.value = response.data;
        allItems.value = response.data;
      })
      .finally(() => {
        loadingAllItems.value = false;
      });
  };

  const unselectAllItems = () => {
    selectedItems.value = [];
  };

  return {
    selectedItems,
    indeterminate,
    allItemsSelected,
    selectAllItems,
    loadingAllItems,
    unselectAllItems,
  };
}
