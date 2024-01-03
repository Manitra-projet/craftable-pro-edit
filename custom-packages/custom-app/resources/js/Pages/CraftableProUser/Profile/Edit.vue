<template>
  <PageHeader
    sticky
    :title="$t('custom-app', 'Profile')"
    :subtitle="
      $t('custom-app', 'Last updated at :updated_at', {
        updated_at: dayjs(craftableProUser.updated_at).format(
          'DD.MM.YYYY HH:mm'
        ),
      })
    "
  >
    <div class="flex gap-3">
      <Button
        :leftIcon="ArrowDownTrayIcon"
        @click="submit"
        :loading="form.processing"
      >
        {{ $t("custom-app", "Save") }}
      </Button>
    </div>
  </PageHeader>

  <PageContent>
    <div class="mx-auto max-w-3xl 2xl:max-w-4xl">
      <ProfileCard :form="form" />
    </div>
  </PageContent>
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import {
  PageHeader,
  PageContent,
  Button,
  Multiselect,
} from "custom-app/Components";
import type { CraftableProUser } from "custom-app/types/models";
import { useForm } from "custom-app/hooks/useForm";
import dayjs from "dayjs";
import ProfileCard from "../Components/ProfileCard.vue";

interface Props {
  craftableProUser: CraftableProUser;
}

const props = defineProps<Props>();

const { form, submit } = useForm(
  {
    first_name: props.craftableProUser.first_name ?? "",
    last_name: props.craftableProUser.last_name ?? "",
    email: props.craftableProUser.email ?? "",
    locale: props.craftableProUser.locale ?? "",
    avatar: props.craftableProUser.avatar ?? [],
  },
  route("custom-app.custom-app-users.profile.update")
);
</script>
