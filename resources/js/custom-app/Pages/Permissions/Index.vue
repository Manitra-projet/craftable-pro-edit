<template>
    <PermissionIndexPage v-bind="props" />
</template>

<script setup lang="ts">
import PermissionIndexPage from "custom-app/Pages/Permissions/Index.vue";
import type { Role } from "custom-app/types/models";

interface Props {
    roles: Role[];
    permissions: [];
}

const props = defineProps<Props>();
</script>
