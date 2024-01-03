<template>
  <RoleIndexPage v-bind="props" />
</template>

<script setup lang="ts">
import RoleIndexPage from "custom-app/Pages/Roles/Index.vue";
import { PaginatedCollection } from "custom-app/types/pagination";
import type { Role } from "custom-app/types/models";
import { getCurrentInstance, onMounted, useSSRContext } from "vue";
import $ from 'jquery'

interface Props {
  roles: PaginatedCollection<Role>;
}

onMounted(()=>{
  console.log(getCurrentInstance());
})

const props = defineProps<Props>();

</script>
