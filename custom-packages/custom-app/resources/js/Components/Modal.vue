<template>
  <div>
    <slot name="trigger" :setIsOpen="setIsOpen" />
    <TransitionRoot as="template" :show="externalOpen ? props.open : open">
      <Dialog
        as="div"
        :open="externalOpen ? props.open : open"
        class="fixed inset-0 z-50 overflow-y-auto"
        @close="setIsOpen"
      >
        <button class="absolute h-0 w-0 opacity-0" />
        <div
          class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <DialogOverlay
              class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            />
          </TransitionChild>

          <!-- This element is to trick the browser into centering the modal contents. -->
          <span
            class="hidden sm:inline-block sm:h-screen sm:align-middle"
            aria-hidden="true"
          >
            &#8203;
          </span>
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              class="relative inline-block transform rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:p-6 sm:align-middle"
              :class="{
                'sm:max-w-md': size === 'sm',
                'sm:max-w-xl': size === 'md',
                'sm:max-w-5xl': size === 'lg',
              }"
            >
              <div
                class="sm:flex"
                :class="{
                  'sm:items-start': !centered,
                  'sm:flex-col sm:items-center': centered,
                }"
              >
                <div
                  v-if="type"
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                  :class="{
                    'bg-danger-100': type === 'danger',
                    'bg-success-100': type === 'success',
                    'bg-info-100': type === 'info',
                    'bg-warning-100': type === 'warning',
                  }"
                >
                  <ExclamationCircleIcon
                    v-if="type === 'danger'"
                    class="h-6 w-6 text-danger-600"
                    aria-hidden="true"
                  />
                  <CheckCircleIcon
                    v-if="type === 'success'"
                    class="h-6 w-6 text-success-600"
                    aria-hidden="true"
                  />
                  <InformationCircleIcon
                    v-if="type === 'info'"
                    class="h-6 w-6 text-info-600"
                    aria-hidden="true"
                  />
                  <ExclamationCircleIcon
                    v-if="type === 'warning'"
                    class="h-6 w-6 text-warning-600"
                    aria-hidden="true"
                  />
                </div>
                <div
                  class="text-center"
                  :class="{
                    'w-full': !type,
                    'sm:text-left': !centered,
                    'sm:ml-4': type && !centered,
                    'mt-3 sm:mt-4': type && centered,
                    'sm:flex-col sm:items-center': centered,
                  }"
                >
                  <DialogTitle
                    v-if="hasTitle"
                    as="h3"
                    class="mb-2 text-lg font-medium leading-6 text-gray-900"
                  >
                    <slot name="title" />
                  </DialogTitle>
                  <div class="text-sm text-gray-500">
                    <slot name="content" :setIsOpen="setIsOpen" />
                  </div>
                </div>
              </div>
              <div
                class="mt-5 gap-4"
                :class="{
                  'sm:mt-4 sm:flex': !centered,
                  'sm:justify-end': alignButtons === 'right',
                  'sm:justify-start': alignButtons === 'left',
                  'grid auto-cols-fr grid-flow-col sm:mt-6 sm:items-center':
                    centered,
                  'sm:ml-10 sm:pl-4': type && !centered,
                }"
              >
                <slot name="buttons" :setIsOpen="setIsOpen" />
              </div>
            </div>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, useSlots } from "vue";
import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import {
  ExclamationCircleIcon,
  InformationCircleIcon,
  CheckCircleIcon,
  XCircleIcon,
} from "@heroicons/vue/24/outline";
import { ComponentVariant } from "../types";

interface Props {
  open?: boolean;
  type?: ComponentVariant | null;
  centered?: boolean;
  alignButtons?: "left" | "right";
  externalOpen?: boolean;
  size?: "sm" | "md" | "lg";
}

const props = withDefaults(defineProps<Props>(), {
  open: false,
  centered: false,
  alignButtons: "left",
  externalOpen: false,
  size: "md",
});

const slots = useSlots();
const hasTitle = computed(() => !!slots.title);

const open = ref(props.open);
const emit = defineEmits(["toggleOpen"]);

const setIsOpen = (value: boolean = false) => {
  open.value = value;
  emit("toggleOpen");
};
</script>
