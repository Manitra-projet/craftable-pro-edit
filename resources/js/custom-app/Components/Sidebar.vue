<template>
  <div>
    <nav class="mt-5 space-y-1">
      <SidebarGroup :title="$t('custom-app', 'Content')">
        <SidebarItem
          :href="route('custom-app.media.index')"
          :icon="PhotoIcon"
          v-can="'custom-app.media.index'"
        >
          {{ $t("custom-app", "Media") }}
        </SidebarItem>
        <!--AppendGeneratorLink-->
      </SidebarGroup>

      <SidebarGroup
        :title="$t('custom-app', 'System')"
        v-can:any="[
          'custom-app.admin-pro-user.index',
          'custom-app.role.index',
          'custom-app.translation.index',
          'custom-app.settings.edit',
        ]"
      >
        <SidebarItem
          :href="route('custom-app.admin-users.index')"
          :icon="UsersIcon"
          v-can="'custom-app.admin-user.index'"
        >
          {{ $t("custom-app", "Access") }}
        </SidebarItem>
        <SidebarItem
          :href="route('custom-app.roles.index')"
          :icon="KeyIcon"
          v-can="'custom-app.role.index'"
        >
          {{ $t("custom-app", "Roles") }}
        </SidebarItem>
        <SidebarItem
          :href="route('custom-app.translations.index')"
          :icon="LanguageIcon"
          v-can="'custom-app.translation.index'"
        >
          {{ $t("custom-app", "Localization") }}
        </SidebarItem>
        <SidebarItem
          :href="route('custom-app.settings.index')"
          :icon="Cog8ToothIcon"
          v-can="'custom-app.settings.edit'"
        >
          {{ $t("custom-app", "Settings") }}
        </SidebarItem>
      </SidebarGroup>
    </nav>
  </div>
</template>

<script setup lang="ts">
import {
  KeyIcon,
  LanguageIcon,
  PhotoIcon,
  UsersIcon,
  Cog8ToothIcon,
  CubeTransparentIcon,
} from "@heroicons/vue/24/outline";
import { SidebarItem, SidebarGroup } from "custom-app/Components";
</script>
