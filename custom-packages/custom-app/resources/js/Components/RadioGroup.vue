<template>
  <div>
    <label v-if="label" class="text-base font-medium text-gray-900">
      {{ label }}
    </label>
    <p class="text-sm leading-5 text-gray-500">
      <slot />
    </p>
    <fieldset class="mt-3">
      <legend v-if="label" class="sr-only">{{ label }}</legend>
      <div class="space-y-4">
        <div
          v-for="option in options"
          :key="option.value"
          class="flex items-center"
        >
          <input
            v-model="value"
            :id="option.value.toString()"
            :value="option.value.toString()"
            :name="name"
            type="radio"
            class="h-4 w-4 cursor-pointer border-gray-300 text-primary-600 focus:ring-primary-500"
          />
          <div class="ml-3">
            <slot name="option" :option="option">
              <label
                :for="option.value.toString()"
                class="block cursor-pointer text-sm font-medium text-gray-700"
              >
                {{ option.label }}
              </label>
            </slot>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</template>

<script setup lang="ts">
import { useInput } from "../hooks/useInput";

type Option = {
  value: string | number;
  label: string;
  [x: string | number | symbol]: unknown;
};

interface Props {
  name: string;
  label?: string;
  options: Array<Option>;
  modelValue?: string;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: "",
});

const emit = defineEmits(["update:modelValue"]);

const { value } = useInput(props, emit);
</script>
