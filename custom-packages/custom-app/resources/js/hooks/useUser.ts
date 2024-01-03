import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { PageProps } from "../types/page";

export function useUser() {
  const user = computed(() => (usePage().props as PageProps)?.auth.user);

  return {
    user: user,
  };
}
