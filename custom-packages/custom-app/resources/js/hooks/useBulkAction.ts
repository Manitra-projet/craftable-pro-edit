import { useToast } from "@brackets/vue-toastification";
import type { VisitOptions, Method, Page, Errors } from "@inertiajs/core";
import { router } from "@inertiajs/vue3";
import { Ref } from "vue";

export function useBulkAction(selectedItems: Ref<number[]>) {
  const toast = useToast();

  const bulkAction = (
    method: Method,
    url: string,
    customOptions?: VisitOptions
  ) => {
    // Set default options
    const options = {
      preserveScroll: true,
      onSuccess: (page: Page) => {
        selectedItems.value = [];

        if (page.props.message) {
          toast.success(page.props.message);
        }
      },
      onError: (errors: Errors) => {
        if (errors && Object.values(errors)) {
          toast.error(Object.values(errors)[0]);
        }
      },
      // merge custom options
      ...customOptions,
    };

    const data = {
      ids: selectedItems.value,
    };

    router.visit(url, {
      method,
      data,
      ...options,
      preserveState: true,
      preserveScroll: true,
    });
  };

  return { bulkAction };
}
