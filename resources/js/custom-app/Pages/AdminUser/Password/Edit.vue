<template>
  <AdminUserPasswordPage v-bind="props" />
</template>

<script setup lang="ts">
import type { AdminUser } from "custom-app/types/models";
import AdminUserPasswordPage from "custom-app/Pages/AdminUser/Password/Edit.vue";

interface Props {
  adminUser: AdminUser;
}

const props = defineProps<Props>();
</script>
