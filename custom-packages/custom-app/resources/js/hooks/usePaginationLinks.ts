import { computed } from "vue";
import { Pagination, PaginationLink } from "../types/pagination";

const emptyLink: PaginationLink = {
  label: "...",
  url: null,
  active: false,
};

export function usePaginationLinks(props: {
  pagination: Pagination;
  onEachSide: number;
}) {
  const links = computed(() => {
    const currentPageIndex = props.pagination?.links.findIndex(
      (page) => page.active
    );

    const linksWithoutArrows = props.pagination?.links.slice(1, -1);

    const links = [];

    if (!props.pagination?.links) {
      return links;
    }

    links.push(linksWithoutArrows[0]);

    if (linksWithoutArrows.length > 1) {
      if (
        linksWithoutArrows[0].label !==
        linksWithoutArrows[Math.max(0, currentPageIndex - props.onEachSide - 1)]
          .label
      ) {
        links.push(emptyLink);
      }

      links.push(
        ...linksWithoutArrows.slice(
          Math.max(1, currentPageIndex - props.onEachSide),
          Math.min(
            linksWithoutArrows.length - 1,
            currentPageIndex + props.onEachSide - 1
          )
        )
      );

      if (
        linksWithoutArrows[linksWithoutArrows.length - 1].label !==
        linksWithoutArrows[
          Math.min(
            linksWithoutArrows.length - 1,
            currentPageIndex + props.onEachSide - 1
          )
        ].label
      ) {
        links.push(emptyLink);
      }

      links.push(linksWithoutArrows[linksWithoutArrows.length - 1]);
    }

    return links;
  });

  return links;
}
