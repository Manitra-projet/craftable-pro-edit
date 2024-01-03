import { VisitOptions } from "@inertiajs/core";
import { useForm, usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import pick from "lodash/pick";
import { watch } from "vue";
import { PageProps } from "../types/page";

export function useListingSearch(url: string, customOptions?: VisitOptions) {
  const searchForm = useForm({
    search: (usePage().props as PageProps)?.filter?.search ?? "",
  });

  const submitSearch = () => {
    searchForm
      .transform((data) => {
        return {
          ...route().params,
          page: 1,
          filter: {
            ...route().params.filter,
            search: data.search,
          },
        };
      })
      .get(url, {
        ...customOptions,
        preserveState: true,
        preserveScroll: true,
      });
  };

  const resetSearch = () => {
    searchForm.search = "";
  };

  watch(
    () => pick(searchForm, ["search"]),
    debounce(() => submitSearch(), 500)
  );

  return { searchForm, submitSearch, resetSearch };
}
