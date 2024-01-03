<template>
  <AdminUserProfilePage v-bind="props" />
</template>

<script setup lang="ts">
import type { AdminUser } from "custom-app/types/models";
import AdminUserProfilePage from "custom-app/Pages/AdminUser/Profile/Edit.vue";

interface Props {
  adminUser: AdminUser;
  locales?: string[];
}

const props = defineProps<Props>();
</script>
