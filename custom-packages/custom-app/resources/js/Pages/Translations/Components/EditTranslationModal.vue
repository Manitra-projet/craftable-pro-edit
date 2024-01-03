<template>
  <Modal>
    <template #trigger="{ setIsOpen }">
      <IconButton
        as="button"
        @click="() => setIsOpen(true)"
        variant="ghost"
        color="gray"
        :icon="PencilSquareIcon"
      />
    </template>

    <template #title> {{ $t("custom-app", "Edit translation") }}</template>
    <template #content>
      <form class="space-y-6" @submit.prevent="submit">
        <div v-for="locale in locales">
          <TextInput
            v-model="form.text[locale]"
            :label="locale"
            name="text"
            type="text"
          />
        </div>
      </form>
    </template>

    <template #buttons="{ setIsOpen }">
      <Button :loading="form.processing" @click.prevent="submit(setIsOpen)">
        {{ $t("custom-app", "Save") }}
      </Button>
      <Button color="gray" variant="outline" @click.prevent="() => setIsOpen()">
        {{ $t("custom-app", "Cancel") }}
      </Button>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import { LanguageLine } from "custom-app/types/models";
import { Button, Modal, TextInput, IconButton } from "custom-app/Components";
import { useForm } from "@inertiajs/vue3";
import { PencilSquareIcon } from "@heroicons/vue/24/outline";
import { useToast } from "@brackets/vue-toastification";
import { trans } from "custom-app/plugins/laravel-vue-i18n";

interface Props {
  languageLine: LanguageLine;
  locales: string[];
}

const props = defineProps<Props>();

const form = useForm({
  text: props.languageLine.text,
});

const toast = useToast();

const submit = (setIsOpen: Function) => {
  form.post(route("custom-app.translations.update", [props.languageLine.id]), {
    onFinish: () => {
      setIsOpen();
      toast.success(
        trans("custom-app", "Translation was successfully updated")
      );
    },
  });
};
</script>
