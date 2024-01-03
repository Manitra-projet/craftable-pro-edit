<template>
  <Popover
    v-slot="{ open }"
    @mouseleave="setIsHovering(false)"
    class="relative h-full"
    :class="{
      'flex flex-col-reverse [&>*]:flex-1': props.position === 'top',
    }"
  >
    <PopoverButton
      as="div"
      class="flex items-center [&>*]:h-full"
      @mouseenter="setIsHovering(true)"
    >
      <slot name="button" />
    </PopoverButton>

    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="translate-y-1 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-1 opacity-0"
    >
      <div
        v-if="useHover ? isHovering : open"
        class="absolute left-1/2 z-10 max-w-xs -translate-x-1/2 transform whitespace-nowrap lg:max-w-sm"
        :class="{
          'top-full pt-2': props.position === 'bottom',
          'bottom-full pb-2': props.position === 'top',
        }"
      >
        <PopoverPanel static>
          <div
            class="absolute top-[100%] left-[50%] h-0 w-0 -translate-y-2 -translate-x-1/2 border-l-[8px] border-t-[6px] border-r-[8px] border-l-transparent border-r-transparent"
            :class="{
              'border-t-white ': color === 'white',
              'border-t-gray-700': color === 'black',
            }"
          />
          <div
            class="overflow-hidden rounded-md text-xs shadow-lg ring-1"
            :class="{
              'px-3 py-1': !noPadding,
              'bg-white text-gray-400 ring-gray-200': color === 'white',
              'bg-gray-700 text-gray-100 ring-gray-700': color === 'black',
            }"
          >
            <slot name="content" />
          </div>
        </PopoverPanel>
      </div>
    </transition>
  </Popover>
</template>

<script setup lang="ts">
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { ref } from "vue";

interface Props {
  position: "top" | "bottom";
  useHover?: boolean;
  noPadding?: boolean;
  color?: "white" | "black";
}

const props = withDefaults(defineProps<Props>(), {
  position: "top",
  useHover: true,
  noPadding: false,
  color: "black",
});

const isHovering = ref(false);

const setIsHovering = (value: boolean) => {
  if (props.useHover) {
    isHovering.value = value;
  }
};
</script>
