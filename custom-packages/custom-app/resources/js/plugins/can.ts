import { usePage } from "@inertiajs/vue3";
import { PageProps } from "custom-app/types/page";
import { DirectiveBinding } from "vue";

export const can = (el: HTMLElement, binding: DirectiveBinding) => {
  const permissions = usePage<PageProps>().props.auth.permissions;
  let can = true;

  if (binding.arg) {
    if (binding.arg === "all") {
      can = binding.value.every((permission: string) =>
        permissions.includes(permission)
      );
    }

    if (binding.arg === "any") {
      can = binding.value.some((permission: string) =>
        permissions.includes(permission)
      );
    }

    if (binding.arg === "none") {
      can = binding.value.every(
        (permission: string) => !permissions.includes(permission)
      );
    }

    if (binding.arg === "not") {
      can = !permissions.includes(binding.value);
    }
  } else {
    if (!permissions.includes(binding.value)) {
      can = false;
    }
  }

  if (!can) {
    el.parentNode?.removeChild(el);
  }
};
