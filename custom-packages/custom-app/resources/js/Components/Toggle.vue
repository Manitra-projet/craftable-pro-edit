<template>
  <Switch
    v-model="value"
    :class="[
      value ? 'bg-primary-600' : 'bg-gray-200',
      'relative inline-flex h-6 w-10 rounded-full p-1 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
    ]"
  >
    <span
      :class="[
        value ? 'translate-x-4' : 'translate-x-0',
        'pointer-events-none relative inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition ease-in-out',
      ]"
    >
      <span
        :class="[
          value
            ? 'opacity-0 duration-100 ease-out'
            : 'opacity-100 duration-200 ease-in',
          'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
        ]"
        aria-hidden="true"
      >
        <XMarkIcon v-if="withIcon" class="h-3 w-3 text-gray-400" />
        <label class="pr-2" v-if="label">{{ label }}</label>
      </span>
      <span
        :class="[
          value
            ? 'opacity-100 duration-200 ease-in'
            : 'opacity-0 duration-100 ease-out',
          'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
        ]"
        aria-hidden="true"
      >
        <CheckIcon v-if="withIcon" class="h-3 w-3 text-primary-600" />
      </span>
    </span>
  </Switch>
</template>

<script setup lang="ts">
import { Switch } from "@headlessui/vue";
import { XMarkIcon, CheckIcon } from "@heroicons/vue/24/solid";
import { useInput } from "../hooks/useInput";

interface Props {
  name: string;
  withIcon?: boolean;
  modelValue?: boolean;
  label?: string;
}

const props = withDefaults(defineProps<Props>(), {
  withIcon: false,
  modelValue: false,
});

const emit = defineEmits(["update:modelValue"]);

const { value } = useInput(props, emit);
</script>
