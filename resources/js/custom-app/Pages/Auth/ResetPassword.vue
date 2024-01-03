<template>
  <ResetPasswordPage v-bind="props" />
</template>

<script setup lang="ts">
import ResetPasswordPage from "custom-app/Pages/Auth/ResetPassword.vue";

interface Props {
  email: string;
  token: string;
}

const props = defineProps<Props>();
</script>
