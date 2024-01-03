<template>
  <div>
    <label
      v-if="label"
      :for="name"
      class="text-blue-gray-900 mb-1 block text-sm font-medium"
    >
      {{ label }}</label
    >
    <div class="flex items-center gap-4">
      <label
        :for="name"
        class="text-blue-gray-900 relative cursor-pointer text-sm font-medium"
        @dragover.prevent="dragOver"
        @dragleave.prevent="dragLeave"
        @drop.prevent="onFilesDrop"
      >
        <Avatar
          size="xl"
          :src="filteredFiles?.[0]?.preview_url ?? filteredFiles?.[0]?.base64"
        />
      </label>
      <div class="flex gap-4">
        <Button variant="outline" color="gray" class="relative">
          <label
            :for="name"
            class="text-blue-gray-900 relative cursor-pointer text-sm font-medium"
            @dragover.prevent="dragOver"
            @dragleave.prevent="dragLeave"
            @drop.prevent="onFilesDrop"
          >
            <span>{{ $t("custom-app", "Change") }}</span>
          </label>
          <input
            :id="name"
            :name="name"
            type="file"
            class="absolute inset-0 -z-10 h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0"
            @input="onFilesSelect"
          />
        </Button>
        <Button
          variant="ghost"
          color="primary"
          @click="removeImage(files?.[0])"
        >
          {{ $t("custom-app", "Remove") }}
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { v4 as uuidv4 } from "uuid";
import axios from "axios";
import { computed, ref, watch } from "vue";
import { useToast } from "@brackets/vue-toastification";
import { trans } from "custom-app/plugins/laravel-vue-i18n";
import { Avatar, Button } from ".";
import { UploadedFile } from "custom-app/types";
import omit from "lodash/omit";

interface Props {
  name: string;
  label?: string;
  modelValue?: UploadedFile[];
  accept?: string;
  onlyThumbs?: boolean;
  maxFileSize?: number;
  centered?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: undefined,
  // TODO: default value from config
  maxFileSize: 1024 * 1024,
  centered: false,
});

const toast = useToast();
const emit = defineEmits(["update:modelValue"]);

const files = ref(props.modelValue);

const filteredFiles = computed(() =>
  files.value
    ? files.value?.filter((file) => !file.action || file.action !== "delete")
    : []
);

watch(
  () => [files.value],
  () => {
    files.value?.forEach((file) => {
      preview(file);
    });

    emit(
      "update:modelValue",
      files.value?.map((file: UploadedFile) => omit(file, ["file", "base64"]))
    );
  },
  { deep: true }
);

const preview = (file: UploadedFile) => {
  const reader = new FileReader();

  reader.onloadend = (e) => {
    // eslint-disable-next-line no-param-reassign
    file.base64 = reader.result;
  };

  if (file?.file) {
    reader.readAsDataURL(file.file);
  }
};

const onFilesSelect = (e: HTMLInputElement) => {
  const filesToUpload = Array.from(e.target.files) as File[];

  uploadFile(filesToUpload[0]);
};

const removeImage = (file: UploadedFile) => {
  if (file) {
    // eslint-disable-next-line no-param-reassign
    file.action = "delete";
  }
};

const dropIsActive = ref(false);

const dragOver = () => {
  dropIsActive.value = true;
};

const dragLeave = () => {
  dropIsActive.value = false;
};

const onFilesDrop = (e: any) => {
  const filesToUpload = Array.from(e.dataTransfer.files) as File[];

  uploadFile(filesToUpload[0]);

  dropIsActive.value = false;
};

const uploadFile = (file: File) => {
  if (file.size > props.maxFileSize!) {
    toast.error(
      trans("custom-app", "File size is too big!")
      // trans("custom-app", "validation.max.file", {
      //   attribute: "obrázok",
      //   max: props.maxSizeInKb,
      //   unit: "Kb",
      // })
    );
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
      files.value = [
        {
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
          file,
        },
      ];
    })
    .catch((e) => {
      console.error(e?.message);
    });
};

const switchFile = (uuid: string, e: HTMLInputElement) => {
  const fileToSwitch = (Array.from(e.target.files) as File[])[0];

  if (fileToSwitch) {
    if (fileToSwitch.size > props.maxFileSize!) {
      toast.error(
        trans("custom-app", "File size is too big!")
        // trans("custom-app", "validation.max.file", {
        //   attribute: "obrázok",
        //   max: props.maxFileSize,
        //   unit: "Kb",
        // })
      );
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
            id: null,
            uuid: uuidv4(),
            collection_name: "images",
            action: "add",
            path: response.data.path,
            size: fileToSwitch.size,
            file_name: fileToSwitch.name,
            custom_properties: {
              name: fileToSwitch.name,
              alt: "",
            },
            file: fileToSwitch,
          });
        }
      })
      .catch((e) => {
        console.error(e?.message);
      });
  }
};
</script>
