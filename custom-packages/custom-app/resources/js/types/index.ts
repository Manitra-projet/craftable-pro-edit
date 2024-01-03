import { Ref } from "vue";
import type { useForm } from "@inertiajs/vue3";

export type ComponentVariant = "success" | "info" | "warning" | "danger";
export type ButtonType = "button" | "submit" | "reset";
export type ButtonColor = ComponentVariant | "primary" | "secondary" | "gray";
export type ButtonVariant = "solid" | "outline" | "ghost" | "link";
export type SizesType = "xs" | "sm" | "md" | "lg" | "xl";
export type ShapesType = "square" | "circle";
export type TagVariantsType = "solid" | "outline";
export type TagColorsType =
  | ComponentVariant
  | "primary"
  | "secondary"
  | "amber"
  | "purple"
  | "cyan"
  | "gray";
export type DatePickerMode = "date" | "dateTime";

export type Tab = {
  key: string | number;
  label: string | Ref;
  href?: string;
  active?: boolean;
};

export type UploadedFile = {
  id: number | null;
  uuid: string;
  collection_name: string;
  file_name: string;
  path?: string;
  preview_url?: string;
  original_url?: string;
  action?: string;
  size?: number;
  custom_properties: Record<string, string & Record<string, string>>;
  file?: File;
  base64?: string;
};

export type InertiaForm<T extends Record<string, unknown>> = ReturnType<
  typeof useForm<T>
>;
