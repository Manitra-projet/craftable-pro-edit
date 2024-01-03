<template>
  <DatePicker
    v-model="value"
    :mode="mode"
    :locale="locale ?? $page.props.auth?.user?.locale ?? 'en'"
    is24hr
    color="indigo"
    :popover="{ visibility: 'focus' }"
  >
    <template v-slot="{ inputValue, inputEvents }">
      <TextInput
        :label="label"
        :disabled="disabled"
        :required="required"
        :name="name"
        :placeholder="placeholder"
        v-on="inputEvents"
        :modelValue="inputValue"
        :leftIcon="leftIcon"
        :labelPlacement="labelPlacement"
        autocomplete="off"
      />
    </template>
  </DatePicker>
</template>

<script setup lang="ts">
import { CalendarDaysIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";
import { DatePicker } from "v-calendar";
import dayjs from "dayjs";
import "v-calendar/dist/style.css";
import TextInput from "./TextInput.vue";
import type { Component } from "vue";

import type { DatePickerMode } from "../types";
import { usePage } from "@inertiajs/vue3";

interface Props {
  label?: string;
  name: string;
  placeholder?: string;
  rules?: string;
  required?: boolean;
  disabled?: boolean;
  mode?: DatePickerMode;
  onBlur?: Function;
  modelValue: Date | string;
  labelPlacement?: "top" | "left";
  leftIcon?: Component;
  locale?: string;
}

const props = withDefaults(defineProps<Props>(), {
  required: false,
  disabled: false,
  mode: "date",
  labelPlacement: "top",
  leftIcon: CalendarDaysIcon,
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
  get: () => {
    const date = dayjs(props.modelValue);

    if (!date.isValid()) {
      return "";
    }

    if (props.mode === "date") {
      return date.format("YYYY-MM-DD");
    }
    return date.format("YYYY-MM-DD HH:mm");
  },
  set: (value) => {
    if (usePage().props?.errors?.[props.name]) {
      delete usePage().props!.errors[props.name];
    }

    const date = dayjs(value);

    if (!date.isValid()) {
      emit("update:modelValue", "");
      return;
    }

    if (props.mode === "date") {
      emit("update:modelValue", date.format("YYYY-MM-DD"));
    } else {
      emit("update:modelValue", date.format("YYYY-MM-DD HH:mm"));
    }
  },
});
</script>
