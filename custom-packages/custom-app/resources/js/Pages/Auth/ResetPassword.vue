<template>
  <div>
    <Head :title="$t('custom-app', 'Reset password')" />

    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <form class="space-y-6" @submit.prevent="submit">
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
          autocomplete="new-password"
        />

        <TextInput
          v-model="form.password_confirmation"
          :label="$t('custom-app', 'Confirm Password')"
          name="password_confirmation"
          type="password"
          autocomplete="new-password"
        />

        <Button class="w-full" type="submit" :disabled="form.processing">
          {{ $t("custom-app", "Reset Password") }}
        </Button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm, Head } from "@inertiajs/vue3";
import { Button, TextInput } from "custom-app/Components";

interface Props {
  email: string;
  token: string;
}

const props = defineProps<Props>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("custom-app.password.update"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>
