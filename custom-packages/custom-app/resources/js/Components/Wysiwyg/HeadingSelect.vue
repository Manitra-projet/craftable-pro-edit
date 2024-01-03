<template>
  <Listbox as="div" v-model="selectedTextStyle">
    <div class="relative" v-auto-animate>
      <ListboxButton
        class="relative w-32 cursor-pointer rounded-md bg-white py-1.5 pl-2 pr-6 text-left font-medium text-gray-700 hover:bg-gray-100 focus:outline-none sm:text-sm"
      >
        <span class="flex items-center">
          <span class="block truncate">
            {{ selectedTextStyle.label }}
          </span>
        </span>
        <span
          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
        >
          <ChevronUpDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </span>
      </ListboxButton>

      <ListboxOptions
        class="absolute z-10 mt-1 max-h-60 w-40 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
      >
        <ListboxOption
          as="template"
          v-for="textStyle in textStyles"
          :key="textStyle.label"
          :value="textStyle"
          v-slot="{ active, selected }"
        >
          <li
            :class="[
              active ? 'bg-primary-600 text-white' : 'text-gray-900',
              'relative cursor-default select-none py-2 px-3',
            ]"
          >
            <div class="flex items-center justify-between">
              <span
                :class="[
                  selected ? 'font-semibold' : 'font-normal',
                  'block truncate',
                ]"
              >
                {{ textStyle.label }}
              </span>
              <span class="ml-3 flex-none text-xs font-semibold text-gray-400">
                <kbd class="font-sans">
                  {{ textStyle.shortcut }}
                </kbd>
              </span>
            </div>
          </li>
        </ListboxOption>
      </ListboxOptions>
    </div>
  </Listbox>
</template>
<script setup lang="ts">
import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import { Level } from "@tiptap/extension-heading";
import { Editor } from "@tiptap/vue-3";
import { computed } from "vue";

interface Props {
  editor: Editor | undefined;
}

const props = defineProps<Props>();

const textStyles: Array<{
  label: string;
  level: Level | null;
  shortcut: string;
}> = [
  {
    label: "Normal",
    level: null,
    shortcut: "⌘⎇0",
  },
  {
    label: "Heading 1",
    level: 1,
    shortcut: "⌘⎇1",
  },
  {
    label: "Heading 2",
    level: 2,
    shortcut: "⌘⎇2",
  },
  {
    label: "Heading 3",
    level: 3,
    shortcut: "⌘⎇3",
  },
  {
    label: "Heading 4",
    level: 4,
    shortcut: "⌘⎇4",
  },
  {
    label: "Heading 5",
    level: 5,
    shortcut: "⌘⎇5",
  },
  {
    label: "Heading 6",
    level: 6,
    shortcut: "⌘⎇6",
  },
];

const selectedTextStyle = computed({
  get: () => {
    return (
      textStyles.find((item) =>
        props.editor?.isActive("heading", { level: item.level })
      ) ?? textStyles[0]
    );
  },
  set: (value) => {
    if (value.level) {
      props.editor?.chain().focus().toggleHeading({ level: value.level }).run();
    } else {
      props.editor?.chain().focus().setParagraph().run();
    }
  },
});
</script>
