import { useForm as useFormInertia } from "@inertiajs/vue3";
import { useToast } from "@brackets/vue-toastification";
import type { Page } from "@inertiajs/core";



export function useForm<TForm extends Record<string, unknown>>(
  formData: TForm,
  url: string,
  method: "post" | "put" | "patch" | "delete" = "put",
  options?: {}
) {
  const toast = useToast();

  const defaultOptions = {
    onSuccess: (page: Page) => {
      if (page.props.message) {
        toast.success(page.props.message);
      }
    },
    onError: (errors: Record<string, string>) => {
      if (errors) {
        toast.error(Object.values(errors)[0]);
      }
    },
    ...options,
  };

  const form = useFormInertia(formData);

  const submit = () => {
    form?.submit(method, url, defaultOptions);
  };

  return {
    form,
    submit,
  };
}
