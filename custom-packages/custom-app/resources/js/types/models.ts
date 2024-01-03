export type Model = {
  id: number;
  // TODO: maybe dont have this in this type? Will every model have this attribute? I dunno...
  resource_url: string;
};

export type ModelWithTimestamps = Model & {
  created_at: string;
  updated_at: string;
};

// TODO: just an example how model types could work
export type CraftableProUser = ModelWithTimestamps & {
  first_name: string;
  last_name: string;
  initials: string;
  avatar: string;
  email: string;
  email_verified_at: string;
  locale: string;
  active: boolean;
  media_details: Record<
    string,
    {
      max_number_of_files: number;
      max_file_size: number;
      accept: Array<string>;
    }
  >;
  roles: Array<Role>;
};

export type Tag = ModelWithTimestamps & {
  name: string;
  slug: string;
  type: string;
  order_column: string;
};

export type LanguageLine = {
  id: number;
  group: string;
  key: string;
  text: string;
  created_at: string;
};

export type Role = ModelWithTimestamps & {
  id: number;
  name: string;
  guard_name: string;
};
