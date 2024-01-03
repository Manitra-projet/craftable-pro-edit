<template>
  <DatePicker
    v-model="value"
    :mode="mode"
    locale="sk"
    is24hr
    color="indigo"
    :popover="{ visibility: 'focus' }"
    :isRange="true"
  >
    <template v-slot="{ inputValue, inputEvents }">
      <TextInput
        :label="label"
        :disabled="disabled"
        :required="required"
        :name="name"
        :placeholder="placeholder"
        v-on="inputEvents.start"
        :modelValue="
          inputValue?.start !== null
            ? `${inputValue?.start} - ${inputValue?.end}`
            : ''
        "
        :leftIcon="leftIcon"
        :labelPlacement="labelPlacement"
        autocomplete="off"
      />
    </template>
  </DatePicker>
</template>

<script setup lang="ts">
import { CalendarDaysIcon } from "@heroicons/vue/24/solid";
import { computed, defineEmits, defineProps } from "vue";
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
  modelValue: {
    start: string;
    end: string;
  } | null;
  labelPlacement?: "top" | "left";
  leftIcon?: Component;
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
    const format = props.mode === "date" ? "YYYY-MM-DD" : "YYYY-MM-DD HH:mm";

    let startDate = props.modelValue?.start
      ? dayjs(props.modelValue?.start).format(format)
      : "";
    let endDate = props.modelValue?.end
      ? dayjs(props.modelValue?.end).format(format)
      : "";

    return {
      start: startDate,
      end: endDate,
    };
  },
  set: (value) => {
    if (usePage().props?.errors?.[props.name]) {
      delete usePage().props!.errors[props.name];
    }
    const startDate = dayjs(value.start);
    const endDate = dayjs(value.end);

    if (!startDate.isValid() && !endDate.isValid()) {
      emit("update:modelValue", { start: "", end: "" });
      return;
    }

    if (props.mode === "date") {
      emit("update:modelValue", {
        start: startDate.format("YYYY-MM-DD"),
        end: endDate.format("YYYY-MM-DD"),
      });
    } else {
      emit("update:modelValue", {
        start: startDate.format("YYYY-MM-DD HH:mm"),
        end: endDate.format("YYYY-MM-DD HH:mm"),
      });
    }
  },
});
</script>
