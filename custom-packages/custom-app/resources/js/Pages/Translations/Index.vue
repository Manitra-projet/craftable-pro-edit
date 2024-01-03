<template>
  <PageHeader :title="$t('custom-app', 'Translations')">
    <div class="flex">
      <ExportModal
        @toggle-open="exportModalOpened = !exportModalOpened"
        :open="exportModalOpened"
        :locales="locales"
        v-can="'custom-app.translation.export'"
      />
      <ImportModal
        @toggle-open="importModalOpened = !importModalOpened"
        :open="importModalOpened"
        :locales="locales"
        v-can="'custom-app.translation.import'"
      />
      <ButtonGroup>
        <Button
          @click="
            () => {
              action(
                'post',
                `/admin/translations/publish`,
                {},
                {
                  onSuccess: () => {
                    reload();
                  },
                }
              );
            }
          "
          v-can="'custom-app.translation.publish'"
        >
          {{ $t("custom-app", "Publish translations") }}
        </Button>
        <Dropdown
          noContentPadding
          v-can:any="[
            'custom-app.translation.export',
            'custom-app.translation.import',
            'custom-app.translation.rescan',
          ]"
        >
          <template #button>
            <IconButton :icon="ChevronDownIcon" class="rounded-l-none" />
          </template>

          <template #content>
            <div class="py-1">
              <DropdownItem
                @click="
                  () => {
                    action('post', `/admin/translations/rescan`);
                    toast.warning(
                      $t('custom-app', 'Scanning translations...')
                    );
                  }
                "
                v-can="'custom-app.translation.rescan'"
              >
                {{ $t("custom-app", "Re-scan translations") }}
              </DropdownItem>
              <DropdownItem
                @click="exportModalOpened = true"
                v-can="'custom-app.translation.export'"
              >
                {{ $t("custom-app", "Export") }}
              </DropdownItem>
              <DropdownItem
                @click="importModalOpened = true"
                v-can="'custom-app.translation.import'"
              >
                {{ $t("custom-app", "Import") }}
              </DropdownItem>
            </div>
          </template>
        </Dropdown>
      </ButtonGroup>
    </div>
  </PageHeader>

  <PageContent>
    <Listing
      :data="data"
      :baseUrl="route('custom-app.translations.index')"
      :withBulkSelect="false"
    >
      <template #actions>
        <FiltersDropdown
          :activeFiltersCount="activeFiltersCount"
          :resetFilters="resetFilters"
        >
          <Multiselect
            v-model="filtersForm.group"
            :options="groups"
            :label="$t('custom-app', 'Groups')"
            mode="tags"
            name="groups"
          />
        </FiltersDropdown>
      </template>
      <template #tableHead>
        <ListingHeaderCell sortBy="group">
          {{ $t("custom-app", "Group") }}
        </ListingHeaderCell>

        <ListingHeaderCell sortBy="key">
          {{ $t("custom-app", "Default") }}
        </ListingHeaderCell>

        <ListingHeaderCell>
          {{ ($page.props as PageProps).auth.user.locale }}
        </ListingHeaderCell>

        <ListingHeaderCell>
          {{ $t("custom-app", "Last update") }}
        </ListingHeaderCell>

        <ListingHeaderCell></ListingHeaderCell>
      </template>
      <template #tableRow="{ item, action }">
        <ListingDataCell>
          {{ item.group }}
        </ListingDataCell>

        <ListingDataCell>
          <div class="max-w-sm overflow-hidden text-ellipsis">
            {{ item.key }}
          </div>
        </ListingDataCell>

        <ListingDataCell>
          <div class="max-w-sm overflow-hidden text-ellipsis">
            {{ item.text[($page.props as PageProps).auth.user.locale] }}
          </div>
        </ListingDataCell>

        <ListingDataCell>
          {{ dayjs(item.updated_at).format("DD.MM.YYYY HH:mm") }}
        </ListingDataCell>

        <ListingDataCell>
          <div class="flex items-center justify-end gap-3">
            <EditTranslationModal
              :language-line="item"
              :locales="locales"
              v-can="'custom-app.translation.edit'"
            ></EditTranslationModal>
          </div>
        </ListingDataCell>
      </template>
    </Listing>
  </PageContent>
</template>

<script lang="ts" setup>
import { EllipsisVerticalIcon } from "@heroicons/vue/24/solid";
import {
  Button,
  Listing,
  ListingDataCell,
  ListingHeaderCell,
  Multiselect,
  PageHeader,
  PageContent,
  IconButton,
  Dropdown,
  FiltersDropdown,
  DropdownItem,
  ButtonGroup,
} from "custom-app/Components";
import { PaginatedCollection } from "custom-app/types/pagination";
import type { LanguageLine } from "custom-app/types/models";
import type { PageProps } from "custom-app/types/page";
import { useAction } from "custom-app/hooks/useAction";
import EditTranslationModal from "custom-app/Pages/Translations/Components/EditTranslationModal.vue";
import ExportModal from "custom-app/Pages/Translations/Components/ExportModal.vue";
import ImportModal from "custom-app/Pages/Translations/Components/ImportModal.vue";
import { useToast } from "@brackets/vue-toastification";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useListingFilters } from "custom-app/hooks/useListingFilters";
import dayjs from "dayjs";
import { ChevronDownIcon } from "@heroicons/vue/24/outline";

interface Props {
  data: PaginatedCollection<LanguageLine>;
  groups: string[];
  locales: string[];
}

const toast = useToast();
const { action } = useAction();
const exportModalOpened = ref<boolean>(false);
const importModalOpened = ref<boolean>(false);

defineProps<Props>();

const { filtersForm, resetFilters, activeFiltersCount } = useListingFilters(
  route("custom-app.translations.index"),
  {
    group: (usePage().props as PageProps)?.filter?.group ?? null,
  }
);

const reload = () => {
  window.location.reload();
};
</script>
