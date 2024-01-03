<template>
  <div class="w-full">
    <label
      v-if="label"
      :for="name"
      class="mb-1 block text-sm font-medium text-gray-700"
    >
      {{ label }}
    </label>

    <div v-auto-animate class="mt-1 rounded-md shadow-sm sm:col-span-2 sm:mt-0">
      <div
        v-if="filteredFiles.length"
        class="rounded-md border border-gray-300"
        :class="{
          'rounded-b-none border-b-0':
            !maxNumberOfFiles || filteredFiles.length < maxNumberOfFiles,
        }"
      >
        <div
          v-if="onlyThumbs"
          v-auto-animate
          class="flex flex-wrap items-center gap-4 p-5"
          :class="{ 'justify-center': centered }"
        >
          <div
            v-for="file in filteredFiles"
            :key="file.uuid"
            class="group relative"
          >
            <slot name="thumb" :file="file">
              <FileThumbnail :file="file" />
            </slot>
            <label
              :for="file.uuid"
              type="button"
              class="absolute inset-0 flex cursor-pointer items-center justify-center rounded bg-gray-900/60 opacity-0 transition-opacity group-hover:opacity-100"
            >
              <ArrowPathRoundedSquareIcon class="h-8 w-8 text-white" />
              <input
                :id="file.uuid"
                class="sr-only absolute"
                type="file"
                :name="file.uuid"
                multiple="false"
                :accept="accept"
                @input="switchFile(file.uuid, $event)"
              />
            </label>
            <button
              type="button"
              class="text-danger-500 hover:text-danger-700 absolute -right-2 -top-2 rounded-full bg-white"
              @click="removeImage(file)"
            >
              <XCircleIcon class="h-6 w-6" />
            </button>
          </div>
        </div>
        <div
          v-else
          v-auto-animate
          class="divide-y divide-gray-100 border-gray-300"
        >
          <div
            v-for="file in filteredFiles"
            :key="file.uuid"
            class="relative grid grid-cols-[8rem_repeat(3,_1fr)] items-center gap-5 p-5"
          >
            <div class="group relative">
              <slot name="thumb" :file="file">
                <FileThumbnail :file="file" />
              </slot>
              <div
                class="absolute inset-0 flex flex-col items-center justify-center gap-3 rounded bg-gray-900/60 p-2 opacity-0 transition-opacity group-hover:opacity-100"
              >
                <label :for="file.uuid" type="button">
                  <Button
                    :left-icon="ArrowPathRoundedSquareIcon"
                    size="sm"
                    color="gray"
                    variant="outline"
                    as="span"
                  >
                    {{ $t("craftable-pro", "Replace") }}
                  </Button>

                  <input
                    :id="file.uuid"
                    class="sr-only absolute"
                    type="file"
                    :name="file.uuid"
                    multiple="false"
                    :accept="accept"
                    @input="switchFile(file.uuid, $event)"
                  />
                </label>
                <Modal v-if="!file.id" size="lg">
                  <template #trigger="{ setIsOpen }">
                    <Button
                      size="sm"
                      color="gray"
                      variant="outline"
                      :left-icon="ArrowsPointingInIcon"
                      @click="() => setIsOpen(true)"
                    >
                      {{ $t("custom-app", "Crop") }}
                    </Button>
                  </template>
                  <template #content="{ setIsOpen }">
                    <ImageEditor
                      :src="file"
                      @onCrop="
                        (croppedFile, path) => {
                          file.file = croppedFile;
                          file.path = path;
                          setIsOpen(false);
                        }
                      "
                    />
                  </template>
                </Modal>
              </div>
            </div>
            <div class="text-sm text-gray-500">
              <p class="uppercase">
                {{ getFileExtension(file.file_name) }}
              </p>
              <p>{{ formatBytes(file.size) }}</p>
            </div>
            <div class="col-span-2 space-y-3">
              <slot
                name="inputs"
                :file="file"
                :set-custom-property="setCustomProperty"
                :get-custom-property="getCustomProperty"
              >
                <TextInput
                  v-model="file.custom_properties.name"
                  size="sm"
                  label="File name"
                  :name="`${file.uuid ?? file.id}_name`"
                />
                <TextInput
                  v-model="file.custom_properties.alt"
                  size="sm"
                  label="Alt text"
                  :name="`${file.uuid ?? file.id}_alt`"
                />
              </slot>
            </div>
            <button
              type="button"
              class="absolute right-3 top-2 rounded-full text-gray-500 hover:text-gray-700"
              @click="removeImage(file)"
            >
              <XMarkIcon class="h-6 w-6" />
            </button>
          </div>
        </div>
      </div>
      <label
        v-if="!maxNumberOfFiles || filteredFiles.length < maxNumberOfFiles"
        :for="name"
        class="relative block shrink-0 cursor-pointer rounded-b-md border border-dashed p-5"
        :class="{
          'rounded-t-md': !filteredFiles.length,
          'border-primary-400': dropIsActive,
          'hover:border-primary-400 border-gray-300': !dropIsActive,
        }"
        @dragover.prevent="dragOver"
        @dragleave.prevent="dragLeave"
        @drop.prevent="onFilesDrop"
      >
        <input
          :id="name"
          class="sr-only absolute"
          type="file"
          :name="name"
          :multiple="multiple"
          :accept="accept"
          @input="onFilesSelect"
        />
        <div
          class="flex items-center gap-2"
          :class="{ 'flex-col text-center': centered }"
        >
          <slot
            name="placeholder"
            :max-number-of-files="maxNumberOfFiles"
            :formated-accept="formatedAccept"
            :max-file-size="maxFileSize"
            :format-bytes="formatBytes"
          >
            <slot name="icon">
              <DocumentPlusIcon class="h-10 w-10 stroke-1 text-gray-500" />
            </slot>

            <div class="space-y-1">
              <div class="flex text-sm text-gray-600">
                <span>Select or drag and drop</span>
                <span v-if="maxNumberOfFiles && maxNumberOfFiles > 1">
                  >&nbsp;maximum of
                  {{ maxNumberOfFiles }} files</span
                >
                <span v-else>&nbsp;file</span>
              </div>
              <p class="text-xs text-gray-500">
                <span v-if="formatedAccept">{{ formatedAccept }}</span>
                <span v-if="maxFileSize">
                  up to {{ formatBytes(maxFileSize) }}
                </span>
              </p>
            </div>
          </slot>
        </div>
      </label>
    </div>
  </div>
</template>

<script setup lang="ts">
import { v4 as uuidv4 } from "uuid";
import axios from "axios";
import { computed, ref, toRefs, watch } from "vue";
import { useToast } from "@brackets/vue-toastification";
import {
  DocumentIcon,
  DocumentPlusIcon,
  XMarkIcon,
  XCircleIcon,
  ArrowPathRoundedSquareIcon,
  ArrowsPointingInIcon,
} from "@heroicons/vue/24/outline";
import {
  Button,
  ImageEditor,
  Modal,
  TextInput,
} from "custom-app/Components";
import FileThumbnail from "./FileThumbnail.vue";
import omit from "lodash/omit";
import { formatBytes, getFileExtension } from "custom-app/helpers";
import { trans } from "custom-app/plugins/laravel-vue-i18n";
import { UploadedFile } from "custom-app/types";
import { get, set } from "lodash";

interface Props {
  name: string;
  label?: string;
  modelValue?: UploadedFile[];
  accept?: string;
  maxNumberOfFiles?: number | boolean;
  onlyThumbs?: boolean;
  maxFileSize?: number;
  centered?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: [],
  // TODO: default value from config
  maxFileSize: 2 * 1024 * 1024,
  centered: false,
});

const multiple = computed(
  () => !props.maxNumberOfFiles || props.maxNumberOfFiles > 1
);

const toast = useToast();
const emit = defineEmits(["update:modelValue"]);

const files = ref(props.modelValue);

const filteredFiles = computed(() =>
  files.value
    ? files.value
        ?.filter((file) => !file.action || file.action !== "delete")
        .sort((a, b) => a.order_column - b.order_column)
    : []
);

watch(
  () => [files.value],
  () => {
    emit(
      "update:modelValue",
      files.value?.map((file: UploadedFile) => omit(file, ["file"]))
    );
  },
  { deep: true }
);

watch(
  () => props.modelValue,
  (newValue) => {
    newValue?.forEach((file) => {
      const alreadyUploadedFile = files.value?.find(
        (f) => f.path === file.file_name
      );
      if (files.value && alreadyUploadedFile) {
        files.value.find((f) => f.path === file.file_name).action = null;
      }
    });
  },
  { deep: true }
);

const onFilesSelect = (e: HTMLInputElement) => {
  const filesToUpload = Array.from(e.target.files) as File[];

  uploadFiles(filesToUpload);
};

const removeImage = (file: UploadedFile) => {
  if (file) {
    // eslint-disable-next-line no-param-reassign
    file.action = "delete";
  }
};

const formatedAccept = computed(() => {
  if (!props.accept) {
    return "";
  }

  return props.accept
    ?.split(",")
    .map((item) => {
      if (item.includes("/") && item.split("/")[1] !== "*") {
        return `.${item.split("/")[1]}`;
      }

      return item;
    })
    .join(", ");
});

const dropIsActive = ref(false);

const dragOver = () => {
  dropIsActive.value = true;
};

const dragLeave = () => {
  dropIsActive.value = false;
};

const onFilesDrop = (e: any) => {
  const filesToUpload = Array.from(e.dataTransfer.files) as File[];

  uploadFiles(filesToUpload);

  dropIsActive.value = false;
};

const uploadFiles = (filesToUpload: File[]) => {
  if (props.maxNumberOfFiles) {
    filesToUpload = filesToUpload.slice(
      0,
      (props.maxNumberOfFiles as number) - filteredFiles.value.length
    );
  }

  filesToUpload.forEach((file: File) => {
    if (file.size > props.maxFileSize!) {
      toast.error(trans("craftable-pro", "File size is too big!"));
      return;
    }

    const formData = new FormData();
    formData.append("file", file);

    axios
      .post(`/admin/upload`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
      .then((response) => {
        files.value!.push({
          id: null,
          uuid: uuidv4(),
          collection_name: "images",
          action: "add",
          path: response.data.path,
          size: file.size,
          file_name: file.name,
          custom_properties: {
            name: file.name,
            alt: "",
          },
          file: file,
        });
      })
      .catch((e) => {
        toast.error(e.message);
      });
  });
};

const switchFile = (uuid: string, e: HTMLInputElement) => {
  const fileToSwitch = (Array.from(e.target.files) as File[])[0];

  if (fileToSwitch) {
    if (fileToSwitch.size > props.maxFileSize!) {
      toast.error(trans("craftable-pro", "File size is too big!"));
      return;
    }

    const formData = new FormData();
    formData.append("file", fileToSwitch);

    axios
      .post(`/admin/upload`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
      .then((response) => {
        const index = files.value?.findIndex((file) => file.uuid === uuid);

        const fileToReplace = files.value?.find((file) => file.uuid === uuid);

        if (typeof index !== "undefined" && index !== -1 && fileToReplace) {
          removeImage(fileToReplace);

          files.value!.splice(index, 0, {
            ...fileToReplace,
            id: null,
            action: "add",
            path: response.data.path,
            file: fileToSwitch,
            file_name: fileToSwitch.name,
            size: fileToSwitch.size,
          });
        }
      })
      .catch((e) => {
        toast.error(e.message);
      });
  }
};

const setCustomProperty = (
  file: UploadedFile,
  property: string,
  value: string
) => {
  set(file.custom_properties, property, value);
};

const getCustomProperty = (file: UploadedFile, property: string) => {
  if (!get(file?.custom_properties, property)) {
    set(file.custom_properties, property, "");
  }
  return get(file?.custom_properties, property);
};
</script>
