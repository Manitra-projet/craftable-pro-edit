<template>
  <RoleIndexPage v-bind="props" />
</template>

<script setup lang="ts">
import RoleIndexPage from "custom-app/Pages/Roles/Index.vue";
import { PaginatedCollection } from "custom-app/types/pagination";
import type { Role } from "custom-app/types/models";

interface Props {
  roles: PaginatedCollection<Role>;
}

const props = defineProps<Props>();

</script>
