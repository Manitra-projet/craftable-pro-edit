<template>
  <PageHeader
    sticky
    :title="$t('custom-app', 'Edit [[modelName]]')"
    :subtitle="`Last updated at ${dayjs(
      [[modelNameLowerCase]].updated_at
    ).format('DD.MM.YYYY')}`"
  >
    <Button
      :leftIcon="ArrowDownTrayIcon"
      @click="submit"
      :loading="form.processing"
      v-can="'custom-app.[[modelPermissionName]].edit'"
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
import type { [[modelIndexName]], [[modelIndexName]]Form } from "./types";
import dayjs from "dayjs";
[[editVueImports]]

[[translatableFunctionality]]

interface Props {
  [[modelNameLowerCase]]: [[modelIndexName]];
  [[relationsProps]]
}

const props = defineProps<Props>();

const { form, submit } = useForm<[[modelIndexName]]Form>(
    {
          [[editFormColumns]]
    },
    route("[[modelUpdateRoute]]", [props.[[modelNameLowerCase]]?.id])
);
</script>
