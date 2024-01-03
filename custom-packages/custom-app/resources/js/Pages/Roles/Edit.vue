<template>
  <PageHeader
    sticky
    :title="`${$t('custom-app', 'Edit permissions for role')} ${role.name}`"
  >
    <Button
      :leftIcon="ArrowDownTrayIcon"
      @click="submit"
      :loading="form.processing"
    >
      {{ $t("custom-app", "Save") }}
    </Button>
  </PageHeader>

  <PageContent>
    <Form :form="form" :role="role" :submit="submit" />
  </PageContent>
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "custom-app/Components";
import { useForm } from "custom-app/hooks/useForm";
import Form from "./Form.vue";
import type { Role } from "custom-app/types/models";

interface Props {
  role: Role;
  permissionsTree: any;
}

const props = defineProps<Props>();

const { form, submit } = useForm<any>(
  {
    permissionsTree: props.permissionsTree,
  },
  route("custom-app.roles.update", [props.role.id])
);
</script>
