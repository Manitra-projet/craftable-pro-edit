<template>
  <PageHeader
    sticky
    :title="$t('custom-app', 'Edit user')"
    :subtitle="`Last updated at ${dayjs(craftableProUser.updated_at).format(
      'DD.MM.YYYY HH:mm'
    )}`"
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
    <Form
      :locales="locales"
      :form="form"
      :craftableProUser="craftableProUser"
      :submit="submit"
      :roles="roles"
    />
  </PageContent>
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "custom-app/Components";
import { useForm } from "@inertiajs/vue3";
import Form from "./Form.vue";
import type { CraftableProUser } from "custom-app/types/models";
import dayjs from "dayjs";
import type { UploadedFile } from "../../types";
import type { Role } from "../../types/models";
import isNull from "lodash/isNull";
import omitBy from "lodash/omitBy";

interface Props {
  craftableProUser: CraftableProUser;
  avatar: UploadedFile[];
  roles: Role[];
  locales: string[];
}

const props = defineProps<Props>();

const form = useForm({
  first_name: props.craftableProUser.first_name ?? "",
  last_name: props.craftableProUser.last_name ?? "",
  email: props.craftableProUser.email ?? "",
  password: null,
  password_confirmation: null,
  locale: props.craftableProUser.locale ?? "",
  active: props.craftableProUser.active ?? false,
  role_id: props.craftableProUser.roles
    ? props.craftableProUser.roles?.[0]?.id
    : null,
  avatar: props.craftableProUser.avatar ?? [],
});

const submit = () => {
  form
    .transform((data) => omitBy(data as object, isNull))
    .put(
      route("custom-app.custom-app-users.update", [
        props.craftableProUser.id,
      ])
    );
};
</script>
