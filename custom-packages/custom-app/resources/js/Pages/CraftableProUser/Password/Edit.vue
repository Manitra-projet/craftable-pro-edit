<template>
  <PageHeader sticky :title="$t('custom-app', 'Password')">
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
    <div class="mx-auto flex max-w-3xl flex-col gap-6 2xl:max-w-4xl">
      <PasswordCard :form="form" />
    </div>
  </PageContent>
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { PageHeader, PageContent, Button } from "custom-app/Components";
import type { CraftableProUser } from "custom-app/types/models";
import { useForm } from "custom-app/hooks/useForm";
import PasswordCard from "../Components/PasswordCard.vue";

interface Props {
  craftableProUser: CraftableProUser;
}

const props = defineProps<Props>();

const { form, submit } = useForm(
  {
    password: "",
    password_confirmation: "",
  },
  route("custom-app.custom-app-users.password.update")
);
</script>
