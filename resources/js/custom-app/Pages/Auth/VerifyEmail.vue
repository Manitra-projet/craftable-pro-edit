<template>
  <VerifyEmailPage v-bind="props" />
</template>

<script setup lang="ts">
import VerifyEmailPage from "custom-app/Pages/Auth/VerifyEmail.vue";

interface Props {
  status: string;
}

const props = defineProps<Props>();
</script>
