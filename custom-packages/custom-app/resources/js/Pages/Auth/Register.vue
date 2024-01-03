<template>
  <div>
    <Head :title="$t('custom-app', 'Register')" />

    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <form class="space-y-6" @submit.prevent="submit">
        <TextInput
          v-model="form.first_name"
          :label="$t('custom-app', 'First name')"
          name="first_name"
        />

        <TextInput
          v-model="form.last_name"
          :label="$t('custom-app', 'Last name')"
          name="last_name"
        />

        <TextInput
          v-model="form.email"
          :label="$t('custom-app', 'E-mail address')"
          name="email"
        />

        <TextInput
          v-model="form.password"
          :label="$t('custom-app', 'Password')"
          name="password"
          type="password"
          autocomplete="current-password"
        />

        <TextInput
          v-model="form.password_confirmation"
          :label="$t('custom-app', 'Confirm Password')"
          name="password_confirmation"
          type="password"
          autocomplete="new-password"
        />

        <SelectInput
            :options="locales"
            v-model="form.locale"
            label="Locale"
            name="locale"
        />

        <div class="flex items-center justify-end">
          <div class="text-sm">
            <Link
              :href="route('custom-app.login')"
              class="font-medium text-primary-600 hover:text-primary-500"
            >
              {{ $t("custom-app", "Already registered?") }}
            </Link>
          </div>
        </div>

        <Button class="w-full" type="submit" :disabled="form.processing">
          {{ $t("custom-app", "Register") }}
        </Button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm, Head } from "@inertiajs/vue3";
import { Button, TextInput } from "custom-app/Components";
import SelectInput from "custom-app/Components/SelectInput.vue";

interface Props {
  locales: string[];
  defaultLocale: string;
}

const props = defineProps<Props>();

const form = useForm({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
  locale: props.defaultLocale,
});

const submit = () => {
  form.post(route("custom-app.register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>
