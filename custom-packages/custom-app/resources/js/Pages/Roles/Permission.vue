<template>
  <template v-for="(value, key) in parentPermissions">
    <template v-if="typeof value !== 'boolean'">
      <Accordion :default-open="defaultOpen">
        <template #title>
          <span :class="permissionTitleFontClass()">{{
            $t("permissions", permissionTitle(key))
          }}</span>
        </template>
        <template #content>
          <Permission
            :default-open="false"
            v-model="parentPermissions[key]"
            :parent-permission="permissionTitle(key)"
          >
          </Permission>
        </template>
      </Accordion>
    </template>
    <template v-else>
      <Checkbox
        v-model="parentPermissions[key]"
        :key="permissionTitle(key)"
        :name="permissionTitle(key)"
        :label="$t('permissions', permissionTitle(key))"
        :value="key"
        :checked="value"
        @change="permissionCheck(permissionTitle(key))"
      />
    </template>
  </template>
</template>

<script setup lang="ts">
import { Accordion, Checkbox } from "craftable-pro/Components";
import { computed } from "vue";
import { trans } from "craftable-pro/plugins/laravel-vue-i18n";
import findLast from "lodash/findLast";

interface Props {
  modelValue: any;
  parentPermission?: string;
  defaultOpen: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits(["update:modelValue"]);

const parentPermissions = computed({
  get: () => {
    return props.modelValue;
  },
  set: (value) => {
    emit("update:modelValue", value);
  },
});

const permissionTitle = (key: string) => {
  if (props.parentPermission) {
    return props.parentPermission + "." + key;
  }
  return key;
};

const permissionTitleFontClass = () => {
  if (props.parentPermission) {
    return "text-sm";
  }
  return "text-lg";
};

const permissionCheck = ($key: string) => {
  if ($key.search("craftable-pro.role") === 0) {
    if (
      !confirm(
        trans(
          "craftable-pro",
          "Do you really want to revoke permission to manage permissions?"
        )
      )
    ) {
      const item = findLast($key.split("."));
      props.modelValue[item] = !props.modelValue[item];

      emit("update:modelValue", props.modelValue);
    }
  }
};
</script>
