<template>
  <AdminUserCreatePage v-bind="props" />
</template>

<script setup lang="ts">
import AdminUserCreatePage from "custom-app/Pages/AdminUser/Create.vue";
import type { Role } from "custom-app/types/models";

interface Props {
  roles: Role[];
  locales: string[];
  defaultLocale: string;
}

const props = defineProps<Props>();
</script>
