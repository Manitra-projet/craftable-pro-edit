import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function useInput(
  props: { name: string; modelValue?: any },
  emit: (event: "update:modelValue", ...args: any[]) => void
) {
  const error = computed(() => usePage().props?.errors?.[props.name]);

  const value = computed({
    get: () => props.modelValue,
    set: (newValue) => {
      if (usePage().props?.errors?.[props.name]) {
        delete usePage().props!.errors[props.name];
      }
      emit("update:modelValue", newValue);
    },
  });

  return {
    value,
    error,
  };
}
