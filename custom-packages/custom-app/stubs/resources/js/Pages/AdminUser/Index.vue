<template>
  <AdminUserPage v-bind="props" />
</template>

<script setup lang="ts">
import AdminUserPage from "custom-app/Pages/AdminUser/Index.vue";
import { PaginatedCollection } from "custom-app/types/pagination";
import type { AdminUser } from "custom-app/types/models";

interface Props {
  adminUsers: PaginatedCollection<AdminUser>;
  filterOptions: {
    roles: string[];
  };
}

const props = defineProps<Props>();
</script>
