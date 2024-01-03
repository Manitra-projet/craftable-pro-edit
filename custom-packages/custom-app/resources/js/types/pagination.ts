export type PaginationLink = {
  label: string;
  url: string | null;
  active: boolean;
};
export interface Pagination {
  total: number;
  per_page: number;
  current_page: number;
  last_page: number;
  first_page_url: string | null;
  last_page_url: string | null;
  next_page_url: string | null;
  prev_page_url: string | null;
  path: string;
  from: number;
  to: number;
  links: Array<PaginationLink>;
}
export interface PaginatedCollection<T> extends Pagination {
  data: Array<T>;
}
