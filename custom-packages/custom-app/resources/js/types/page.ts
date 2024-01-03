export type PageProps = {
  auth: {
    user: User;
    permissions: string[];
  };
  message?: string;
  errors?: {
    [name: string]: string;
  };
  filter?: Record<string, string[]> & {
    search?: string;
  };
  sort?: string;
  settings?: {
    available_locales?: Array<string>;
  };
};
export type User = {
  id: number;
  first_name: number;
  last_name: number;
  email: string;
  initials: string;
  avatar_url: string;
  locale: string;
};

export type Error = {
  name: string;
  message: string;
};
