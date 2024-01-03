<template>
  <tr
    class="border-b"
    :class="{
      'bg-indigo-50': level === 0,
      'bg-slate-50': level === 1,
      'bg-white': level > 1,
    }"
  >
    <ListingDataCell class="relative py-0 pr-0">
      <div
        class="flex"
        :class="{
          'pl-8': level === 1,
          'pl-16': level === 2,
          'pl-24': level === 3,
          'pl-32': level === 4,
        }"
      >
        <button
          v-if="hasChildren"
          type="button"
          @click.prevent="disclosureIsOpen = !disclosureIsOpen"
          class="flex"
        >
          <ChevronUpIcon v-if="disclosureIsOpen" class="h-5 w-5" />
          <ChevronDownIcon v-else class="h-5 w-5" />

          <FolderIcon class="ml-3 mr-2 h-5 w-5" />
        </button>

        <div
          class="text-sm text-slate-900"
          :class="{ 'font-medium': level === 0 }"
        >
          <template v-if="hasChildren">
            {{ $t("permissions", permissionName) }}
          </template>

          <template v-else>
            {{ $t("permissions", permission) }}
          </template>
        </div>
      </div>
    </ListingDataCell>

    <template v-if="hasChildren">
      <ListingDataCell v-for="role in roles" class="border-l">
        <Checkbox
          class="justify-center"
          :modelValue="
            calculateBulkValue(role) === null || calculateBulkValue(role)
          "
          :checked="
            calculateBulkValue(role) === null || calculateBulkValue(role)
          "
          :indeterminate="calculateBulkValue(role) === null"
          @change="
            $event.target.checked
              ? modifyChildren(role, true)
              : modifyChildren(role, false)
          "
        />
      </ListingDataCell>
    </template>

    <template v-else>
      <ListingDataCell v-for="role in roles" class="border-l">
        <Checkbox
          class="justify-center"
          :modelValue="isPermissionAssigned(role)"
          @change="permissionCheck(role)"
        />
      </ListingDataCell>
    </template>
  </tr>

  <div v-if="hasChildren && disclosureIsOpen" class="contents">
    <PermissionTableRow
      v-for="[key, value] of Object.entries(permission)"
      v-model="roles"
      :permission="value"
      :permissionName="permissionName + '.' + key"
      :level="level + 1"
      :key="key"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/24/outline";
import { FolderIcon } from "@heroicons/vue/24/solid";
import { Checkbox } from "custom-app/Components";
import { ListingDataCell } from "custom-app/Components";

interface Permission {
  [key: string]: Permission | string;
}

interface Props {
  modelValue: { id: number; name: string; permissions: string[] }[];
  permission: Permission | string;
  permissionName: string | number;
  level?: number;
}

const props = withDefaults(defineProps<Props>(), {
  level: 0,
});

const emit = defineEmits(["update:modelValue"]);

const roles = computed({
  get: () => {
    return props.modelValue;
  },
  set: (value) => {
    emit("update:modelValue", value);
  },
});

const disclosureIsOpen = ref<boolean>(true);

const hasChildren = computed(() => typeof props.permission === "object");

const isPermissionAssigned = (role: any) => {
  return role.permissions.includes(props.permission);
};

const permissionCheck = (role: any) => {
  const roleIndex = props.modelValue.findIndex((r: any) => r.id === role.id);

  if (isPermissionAssigned(role)) {
    const withoutPermission = props.modelValue[roleIndex].permissions.filter(
      function (permission: any) {
        return permission !== props.permission;
      }
    );
    props.modelValue[roleIndex].permissions = withoutPermission;
  } else {
    props.modelValue[roleIndex].permissions.push(props.permission as string);
  }

  emit("update:modelValue", props.modelValue);
};

const calculateBulkValue = (role: any) => {
  const roleIndex = props.modelValue.findIndex((r: any) => r.id === role.id);
  const allChildrenPermissions = getValues(props.permission);
  const rolePermissions = props.modelValue[roleIndex].permissions;

  if (allChildrenPermissions.every((p: any) => rolePermissions.includes(p))) {
    return true;
  }

  if (allChildrenPermissions.every((p: any) => !rolePermissions.includes(p))) {
    return false;
  }

  return null;
};

const modifyChildren = (role: any, toAdd: any) => {
  const roleIndex = props.modelValue.findIndex((r: any) => r.id === role.id);
  const allChildrenPermissions = getValues(props.permission);

  if (toAdd) {
    allChildrenPermissions.forEach((p: any) => {
      props.modelValue[roleIndex].permissions.push(p);
    });
  } else {
    const withoutChildrenPermissions = props.modelValue[
      roleIndex
    ].permissions.filter(function (p: any) {
      return !allChildrenPermissions.includes(p);
    });
    props.modelValue[roleIndex].permissions = withoutChildrenPermissions;
  }

  emit("update:modelValue", props.modelValue);
};

const getValues = (obj: any): any => {
  let objects: any[] = [];

  for (var i in obj) {
    if (!obj.hasOwnProperty(i)) continue;

    if (typeof obj[i] == "object") {
      objects = objects.concat(getValues(obj[i]));
    } else {
      objects.push(obj[i]);
    }
  }
  return objects;
};
</script>
