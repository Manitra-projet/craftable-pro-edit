<template>
  <RoleEditPage v-bind="props" />
</template>

<script setup lang="ts">
import type { Role } from "custom-app/types/models";
import RoleEditPage from "custom-app/Pages/Roles/Edit.vue";

interface Props {
  role: Role
  permissionsTree: any
}

const props = defineProps<Props>();
</script>
