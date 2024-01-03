<template>
  <Card :title="$t('custom-app', 'Profile')">
    <div class="grid grid-cols-6 gap-6">
      <ImageUpload
        v-model="form.avatar"
        name="avatar"
        :label="$t('custom-app', 'User photo')"
        class="col-span-6"
      />
      <TextInput
        v-model="form.first_name"
        name="first_name"
        :label="$t('custom-app', 'First name')"
        class="col-span-6 sm:col-span-3"
      />
      <TextInput
        v-model="form.last_name"
        name="last_name"
        :label="$t('custom-app', 'Last name')"
        class="col-span-6 sm:col-span-3"
      />
      <TextInput
        v-model="form.email"
        name="email"
        :label="$t('custom-app', 'E-mail')"
        type="email"
        class="col-span-6 sm:col-span-3"
        disabled
      />
      <Multiselect
        v-model="form.locale"
        name="locale"
        :label="$t('custom-app', 'Locale')"
        mode="single"
        :options="availableLocales"
        class="col-span-6 sm:col-span-3 sm:col-start-1"
        :canDeselect="false"
      >
        <template #singlelabel="{ value }">
          <LocaleFlag :locale="value.value" />
        </template>
        <template #option="{ option, search }">
          <LocaleFlag :locale="option.value" />
        </template>
      </Multiselect>
    </div>
  </Card>
</template>

<script setup lang="ts">
import { computed } from "vue";
import {
  Card,
  ImageUpload,
  Multiselect,
  TextInput,
  LocaleFlag,
} from "custom-app/Components";
import { InertiaForm, usePage } from "@inertiajs/vue3";
import type { CraftableProUserProfileForm } from "../types";

interface Props {
  form: InertiaForm<CraftableProUserProfileForm>;
}

const props = defineProps<Props>();

const availableLocales = computed(() => {
  return usePage().props.settings.available_locales;
});
</script>
