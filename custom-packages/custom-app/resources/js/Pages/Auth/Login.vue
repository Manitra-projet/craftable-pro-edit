<template>
  <div>
    <Head :title="$t('custom-app', 'Login')" />

    <div
      class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10"
      v-auto-animate
    >
      <Alert v-if="status" type="info" class="mb-6">
        {{ status }}
      </Alert>

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
          autocomplete="current-password"
        />

        <div class="flex items-center justify-between">
          <Checkbox
            v-model="form.remember"
            :label="$t('custom-app', 'Remember me')"
            name="remember-me"
          />

          <div v-if="canResetPassword" class="text-sm">
            <Link
              :href="route('custom-app.password.request')"
              class="font-medium text-primary-600 hover:text-primary-500"
            >
              {{ $t("custom-app", "Forgot your password?") }}
            </Link>
          </div>
        </div>

        <Button class="w-full" type="submit" :disabled="form.processing">
          {{ $t("custom-app", "Sign in") }}
        </Button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm, Head } from "@inertiajs/vue3";
import { Button, TextInput, Checkbox, Alert } from "custom-app/Components";

interface Props {
  canResetPassword: boolean;
  status: string;
}

defineProps<Props>();

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("custom-app.login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>
