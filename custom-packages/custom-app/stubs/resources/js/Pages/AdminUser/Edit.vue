<template>
  <AdminUserEditPage v-bind="props" />
</template>

<script setup lang="ts">
import AdminUserEditPage from "custom-app/Pages/AdminUser/Edit.vue";
import type { AdminUser, Role } from "custom-app/types/models";

interface Props {
  adminUser: AdminUser;
  roles: Role[];
  locales: string[];
}

const props = defineProps<Props>();
</script>
