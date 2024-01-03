<template>
  <PageHeader :title="$t('custom-app', 'Roles')">
    <Button :as="Link" :href="`permissions`" v-can="'custom-app.role.edit'">
      {{ $t("custom-app", "Manage permissions") }}
    </Button>
  </PageHeader>

  <PageContent>
    <Listing
      :baseUrl="route('custom-app.roles.index')"
      :data="roles"
      dataKey="roles"
      :withBulkSelect="false"
    >
      <template #tableHead>
        <ListingHeaderCell sortBy="id" class="w-14">
          {{ $t("custom-app", "ID") }}
        </ListingHeaderCell>
        <ListingHeaderCell sortBy="name">
          {{ $t("custom-app", "Name") }}
        </ListingHeaderCell>
        <ListingHeaderCell>
          {{ $t("custom-app", "Users") }}
        </ListingHeaderCell>
      </template>
      <template #tableRow="{ item, action }: any">
        <ListingDataCell>
          {{ item.id }}
        </ListingDataCell>
        <ListingDataCell>
          <div class="font-medium text-gray-900">
            {{ item.name }}
          </div>
        </ListingDataCell>
        <ListingDataCell>
          <AvatarGroup
            :additionalCount="
              item.users.length > avatarGroupLimit
                ? item.users.length - avatarGroupLimit
                : undefined
            "
            :additionalHref="
              route('custom-app.custom-app-users.index', {
                filter: { role: [item.name] },
              })
            "
          >
            <Avatar
              v-for="user in item.users.slice(0, avatarGroupLimit)"
              :key="user.id"
              :src="user.avatar_url"
              :name="`${user.first_name} ${user.last_name}`"
            />
          </AvatarGroup>
        </ListingDataCell>
      </template>
    </Listing>
  </PageContent>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import {
  PageHeader,
  PageContent,
  Listing,
  ListingHeaderCell,
  ListingDataCell,
  IconButton,
  Button,
  Avatar,
  AvatarGroup,
} from "custom-app/Components";
import { PaginatedCollection } from "custom-app/types/pagination";
import type { Role } from "custom-app/types/models";
import { ref } from "vue";

interface Props {
  roles: PaginatedCollection<Role>;
}

defineProps<Props>();

const avatarGroupLimit = ref(7);
</script>
