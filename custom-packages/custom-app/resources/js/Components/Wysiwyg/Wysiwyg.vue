<template>
  <FormControl :name="name" :label="label" :error="error">
    <div
      class="relative block w-full rounded-md border bg-white text-gray-800 shadow-sm focus-within:outline-none focus-within:ring-1"
      :class="{
        'border-red-300 placeholder-red-300 focus-within:border-red-500 focus-within:ring-red-500':
          !!error,
        'focus-within:border-primary-500 focus-within:ring-primary-500 border-gray-300':
          !error,
      }"
    >
      <div
        v-if="!withoutToolbar"
        class="flex flex-wrap items-center gap-2 divide-x divide-gray-100 border-b border-gray-300 px-2 py-1.5"
      >
        <div class="flex flex-wrap gap-1">
          <HeadingSelect :editor="editor" />
        </div>
        <div class="flex flex-wrap gap-1 pl-2">
          <ToolbarButton
            @click="editor?.chain().toggleBold().run()"
            :active="editor?.isActive('bold')"
            title="⌘B"
            :icon="BoldIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleItalic().run()"
            :active="editor?.isActive('italic')"
            title="⌘I"
            :icon="ItalicIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleUnderline().run()"
            :active="editor?.isActive('underline')"
            title="⌘U"
            :icon="UnderlineIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleStrike().run()"
            :active="editor?.isActive('strike')"
            title="⌘⇧X"
            :icon="StrikeIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleCode().run()"
            :active="editor?.isActive('code')"
            title="⌘E"
            :icon="CodeIcon"
          />
        </div>
        <div class="flex flex-wrap gap-1 pl-2">
          <ToolbarButton
            @click="editor?.chain().setTextAlign('left').run()"
            :active="editor?.isActive({ textAlign: 'left' })"
            title="⌘⇧L"
            :icon="TextAlignLeftIcon"
          />
          <ToolbarButton
            @click="editor?.chain().setTextAlign('center').run()"
            :active="editor?.isActive({ textAlign: 'center' })"
            title="⌘⇧E"
            :icon="TextAlignCenterIcon"
          />
          <ToolbarButton
            @click="editor?.chain().setTextAlign('right').run()"
            :active="editor?.isActive({ textAlign: 'right' })"
            title="⌘⇧R"
            :icon="TextAlignRightIcon"
          />
          <ToolbarButton
            @click="editor?.chain().setTextAlign('justify').run()"
            :active="editor?.isActive({ textAlign: 'justify' })"
            title="⌘⇧J"
            :icon="TextAlignJustifyIcon"
          />
        </div>
        <div class="flex flex-wrap gap-1 pl-2">
          <ToolbarButton
            @click="editor?.chain().toggleOrderedList().run()"
            :active="editor?.isActive('orderedList')"
            title="⌘⇧7"
            :icon="OrderedListIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleBulletList().run()"
            :active="editor?.isActive('bulletList')"
            title="⌘⇧8"
            :icon="BulletListIcon"
          />
        </div>
        <div class="flex flex-wrap gap-1 pl-2">
          <LinkPromptModal :editor="editor" @linkAdded="setLink" />
          <ToolbarButton
            @click="editor?.chain().toggleBlockquote().run()"
            :active="editor?.isActive('blockquote')"
            title="⌘⇧B"
            :icon="BlockquoteIcon"
          />
          <ToolbarButton
            @click="editor?.chain().toggleCodeBlock().run()"
            :active="editor?.isActive('codeBlock')"
            title="⌘⎇C"
            :icon="CommandLineIcon"
          />
          <ImageUploadButton @imageUploaded="addImage" />
          <YoutubePromptModal @youtubeAdded="addVideo" />
        </div>
      </div>
      <div class="relative">
        <EditorContent :editor="editor" />

        <div
          v-if="error"
          class="pointer-events-none absolute inset-y-0 right-0 top-3 items-center pr-3"
        >
          <ExclamationCircleIcon
            class="h-5 w-5 text-red-500"
            aria-hidden="true"
          />
        </div>
      </div>
    </div>
  </FormControl>
</template>

<script setup lang="ts">
import { withDefaults, watch, ref, computed } from "vue";
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Link from "@tiptap/extension-link";
import Underline from "@tiptap/extension-underline";
import TextAlign from "@tiptap/extension-text-align";
import Youtube from "@tiptap/extension-youtube";
import Image from "@tiptap/extension-image";
import {
  BlockquoteIcon,
  BoldIcon,
  BulletListIcon,
  CodeIcon,
  ItalicIcon,
  OrderedListIcon,
  StrikeIcon,
  TextAlignCenterIcon,
  TextAlignJustifyIcon,
  TextAlignLeftIcon,
  TextAlignRightIcon,
  UnderlineIcon,
} from "./icons";
import { CommandLineIcon } from "@heroicons/vue/24/solid";

import ToolbarButton from "./ToolbarButton.vue";
import HeadingSelect from "./HeadingSelect.vue";
import ImageUploadButton from "./ImageUploadButton.vue";
import { isFileImage } from "custom-app/helpers";
import YoutubePromptModal from "./YoutubePromptModal.vue";
import LinkPromptModal from "./LinkPromptModal.vue";
import { UploadedFile } from "custom-app/types";
import { usePage } from "@inertiajs/vue3";
import { PageProps } from "../../types/page";
import { FormControl } from "..";
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid";

interface Props {
  name: string;
  label?: string;
  modelValue?: string;
  withoutToolbar?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: "",
  withoutToolbar: false,
});

const emit = defineEmits(["update:modelValue"]);

const error = computed(
  () => (usePage().props as PageProps)?.errors[props.name] ?? false
);

watch(
  () => props.modelValue,
  (value) => {
    const isSame = editor.value?.getHTML() === value;

    if (isSame) {
      return;
    }

    editor.value?.commands.setContent(value, false);
  }
);

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    // https://tiptap.dev/api/extensions/starter-kit
    StarterKit,
    Underline,
    Link.configure({
      openOnClick: false,
    }),
    TextAlign.configure({
      types: ["heading", "paragraph"],
    }),
    Youtube.configure({
      width: 640,
      height: 360,
      nocookie: true,
    }),
    Image.configure({
      inline: true,
    }),
  ],
  editorProps: {
    attributes: {
      class:
        "min-h-[160px] max-w-none prose sm:prose-sm prose-p:!my-0 prose-ul:!my-0 prose-li:!my-0 prose-img:!inline py-2 px-3 text-gray-800",
    },
  },
  onUpdate: () => {
    if ((usePage().props as PageProps)?.errors[props.name]) {
      (usePage().props as PageProps).errors[props.name] = false;
    }
    emit("update:modelValue", editor.value?.getHTML());
  },
});

const setLink = (url: string) => {
  // empty
  if (url === "") {
    editor.value?.chain().focus().extendMarkRange("link").unsetLink().run();

    return;
  }

  // update link
  editor.value
    ?.chain()
    .focus()
    .extendMarkRange("link")
    .setLink({ href: url })
    .run();
};

const addVideo = (value?: string) => {
  if (value) {
    editor.value?.commands.setYoutubeVideo({
      src: value,
    });
  }
};

const addImage = (mediaArr: UploadedFile[] | UploadedFile) => {
  if (!Array.isArray(mediaArr)) {
    mediaArr = [mediaArr];
  }

  mediaArr.forEach((media) => {
    if (media.original_url) {
      if (isFileImage(media.original_url)) {
        editor.value
          ?.chain()
          .focus()
          .insertContent(
            `<p><img src="${media.original_url}" alt="${
              media.custom_properties?.alt ?? media.file_name
            }" /></p>`
          )
          .run();
      } else {
        editor.value
          ?.chain()
          .focus()
          .insertContent(
            `<a href="${media.original_url}">${
              media.custom_properties?.name ?? media.file_name
            }</a>`
          )
          .run();
      }
    }
  });
};
</script>

<style>
.ProseMirror:focus {
  outline: none;
}
</style>
