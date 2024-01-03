<template>
  <PageHeader sticky :title="$t('custom-app', 'Create [[modelName]]')">
    <Button
      :leftIcon="ArrowDownTrayIcon"
      @click="submit"
      :loading="form.processing"
      v-can="'custom-app.[[modelPermissionName]].create'"
    >
      {{ $t("custom-app", "Save") }}
    </Button>
  </PageHeader>

  <Form :form="form" :submit="submit" [[relationsFormProps]] />
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, Button } from "custom-app/Components";
import { useForm } from "custom-app/hooks/useForm";
import Form from "./Form.vue";
import type { [[modelIndexName]]Form } from "./types";

[[translatableFunctionality]]

interface Props {
  [[relationsProps]]
}

const props = defineProps<Props>();

const { form, submit } = useForm<[[modelIndexName]]Form>(
    {
          [[createFormColumns]]
    },
    route("[[modelStoreRoute]]"),
    "post"
);
</script>
