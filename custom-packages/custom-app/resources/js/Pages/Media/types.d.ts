export type Media = {
  id: number;
  model_type: string;
  model_id: number;
  collection_name: string;
  name: string;
  file_name: string;
  mime_type: string;
  disk: string;
  conversions_disk: string;
  size: number;
  custom_properties: {
    name: string;
    extension: string;
    size: number;
    alt: string;
  };
  created_at: string;
  updated_at: string;
  original_url: string;
  preview_url: string;
};
