<template>
  <form>
    <div class="text-sm font-medium text-gray-700">
      <p>
        {{
          $t("custom-app", "Found: :found / For review: :toReview", {
            found: numberOfFoundTranslations.toString(),
            toReview: numberOfTranslationsToReview.toString(),
          })
        }}
      </p>
    </div>

    <table class="border-separate border-spacing-3 border-gray-500">
      <thead>
        <tr>
          <th>{{ $t("custom-app", "Group") }}</th>
          <th>{{ $t("custom-app", "Default") }}</th>
          <th>{{ $t("custom-app", "Current value") }}</th>
          <th>{{ $t("custom-app", "Imported value") }}</th>
          <th style="display: none"></th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(item, index) in translationsToImport">
          <tr v-if="item.has_conflict">
            <td style="word-break: break-all">{{ item.group }}</td>
            <td style="word-break: break-all">{{ item.default }}</td>
            <td style="word-break: break-all">
              <input
                :id="'current-' + index + '0'"
                v-model="translationsToImport[index].checkedCurrent"
                :name="'current-' + index"
                :value="true"
                class="import-radio"
                type="radio"
                @change="importedTranslationsChanged"
              />
              <label
                :for="'current-' + index + '0'"
                class="form-check-label label-import"
              >
                {{ translationsToImport[index].current_value }}
              </label>
            </td>
            <td style="word-break: break-all">
              <input
                :id="'current-' + index + '1'"
                v-model="translationsToImport[index].checkedCurrent"
                :name="'current-' + index"
                :value="false"
                class="import-radio"
                type="radio"
                @change="importedTranslationsChanged"
              />
              <label
                :checked="true"
                :for="'current-' + index + '1'"
                class="form-check-label label-import"
              >
                {{ translationsToImport[index][importLanguage.toLowerCase()] }}
              </label>
            </td>
            <td style="display: none"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </form>
</template>

<script lang="ts" setup>
interface Props {
  numberOfFoundTranslations: number;
  numberOfTranslationsToReview: number;
  translationsToImport: [];
  importLanguage: string;
}

const props = defineProps<Props>();
const emit = defineEmits(["importedTranslationsChanged"]);

const importedTranslationsChanged = () => {
  emit("importedTranslationsChanged", props.translationsToImport);
};
</script>
