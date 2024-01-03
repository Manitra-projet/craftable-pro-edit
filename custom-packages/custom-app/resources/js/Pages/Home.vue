<template>
  <PageContent>
    <div
      class="flex h-96 items-center justify-center rounded-lg border-4 border-dashed border-gray-200 p-4"
    >
      <span class="text-center text-xl italic text-gray-300">
        {{ $t("custom-app", "Welcome to Craftable PRO") }}
      </span>
    </div>
  </PageContent>
</template>

<script setup lang="ts">
import { PageContent } from "custom-app/Components";
import { useToast } from "@brackets/vue-toastification";
import { trans, transChoice } from "custom-app/plugins/laravel-vue-i18n";

const toast = useToast();

toast(trans("custom-app", "I'm a :type toast!", { type: "default" }));
toast.info(trans("custom-app", "I'm a :type toast!", { type: "info" }), {
  description: transChoice(
    "custom-app",
    "{1} :count minute ago|[2,*] :count minutes ago",
    10
  ),
});
toast.warning(
  trans("custom-app", "I'm a :type toast!", { type: "warning" })
);
toast.error(trans("custom-app", "I'm a :type toast!", { type: "error" }));
toast.success(
  trans("custom-app", "I'm a :type toast!", { type: "success" }),
  {
    description: "...with an optional description!",
  }
);
</script>
