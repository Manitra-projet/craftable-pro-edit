<template>
  <div class="divide-y divide-gray-100">
    <div class="py-1">
      <DropdownItem class="font-medium text-gray-900">
        {{ user?.email }}
      </DropdownItem>
    </div>
    <div class="py-1">
      <DropdownItem href="/admin/profile">
        {{ $t("custom-app", "Profile") }}
      </DropdownItem>
      <DropdownItem href="/admin/password">
        {{ $t("custom-app", "Password") }}
      </DropdownItem>
    </div>
  </div>
</template>

<script setup lang="ts">
import { DropdownItem } from "custom-app/Components";
import { useUser } from "custom-app/hooks/useUser";

const { user } = useUser();
</script>
