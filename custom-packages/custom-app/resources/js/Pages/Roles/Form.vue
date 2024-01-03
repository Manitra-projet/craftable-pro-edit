<template>
  <form>
    <Card>
      <div class="divide-y divide-gray-200">
        <Permission
          v-model="form.permissionsTree[key]"
          :parent-permission="key"
          v-for="(permissions, key) in form.permissionsTree"
        />
      </div>
    </Card>
  </form>
</template>

<script setup lang="ts">
import { ArrowDownTrayIcon } from "@heroicons/vue/24/outline";
import { Button, Card, CardFooter } from "custom-app/Components";
import { InertiaForm } from "@inertiajs/vue3";
import { Role } from "custom-app/types/models";
import { RoleForm } from "custom-app/Pages/Roles/types";
import Permission from "custom-app/Pages/Roles/Permission.vue";

interface Props {
  form: InertiaForm<RoleForm>;
  submit: void;
  role: Role;
}

const props = defineProps<Props>();
</script>
