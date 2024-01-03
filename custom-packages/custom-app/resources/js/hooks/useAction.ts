import { useToast } from "@brackets/vue-toastification";
import type {
  Errors,
  Method,
  Page,
  RequestPayload,
  VisitOptions,
} from "@inertiajs/core";
import { router } from "@inertiajs/vue3";
import omit from "lodash/omit";

export function useAction() {
  const toast = useToast();

  const action = (
    method: Method,
    url: string,
    data?: RequestPayload,
    customOptions?: VisitOptions
  ) => {
    // Set default options
    const options = {
      preserveScroll: true,
      onSuccess: (page: Page) => {
        if (page.props.message) {
          toast.success(page.props.message);
        }
        customOptions?.onSuccess?.(page);
      },
      onError: (errors: Errors) => {
        if (errors && Object.values(errors)) {
          toast.error(Object.values(errors)[0]);
        }
        customOptions?.onError?.(errors);
      },
      // merge custom options
      ...omit(customOptions, ["onSuccess", "onError"]),
    };

    router.visit(url, {
      method,
      data,
      ...options,
      preserveState: true,
      preserveScroll: true,
    });
  };

  return { action };
}
