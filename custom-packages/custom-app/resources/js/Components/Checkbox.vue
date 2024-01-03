<template>
  <div class="flex items-center">
    <input
      type="checkbox"
      class="block h-4 w-4 cursor-pointer rounded border border-gray-300 text-primary-600 focus:ring-0 focus:ring-transparent focus:ring-offset-0 focus-visible:ring-2 focus-visible:ring-primary-300 focus-visible:ring-offset-2"
      :value="inputValue"
      v-model="model"
      :checked="checked"
      :indeterminate="indeterminate"
      :name="name"
      :id="name"
    />

    <label
      v-if="label"
      :for="name"
      class="ml-2 block cursor-pointer text-sm text-gray-900"
    >
      {{ label }}
    </label>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface Props {
  name?: string;
  label?: string;
  inputValue?: string | number;
  checked?: boolean;
  indeterminate?: boolean;
  modelValue?: boolean | Array<string | number>;
}

const props = withDefaults(defineProps<Props>(), {
  checked: false,
  indeterminate: false,
});

const emit = defineEmits(["update:modelValue"]);

const model = computed({
  get: () => props.modelValue,
  set: (newValue) => {
    emit("update:modelValue", newValue);
  },
});
</script>
