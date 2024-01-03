export type CraftableProUserForm = CraftableProUserPasswordForm &
  CraftableProUserProfileForm & {
    locale: string;
    active: boolean;
    role_id: number | null;
  };

export type CraftableProUserPasswordForm = {
  password: string;
  password_confirmation: string;
};

export type CraftableProUserProfileForm = {
  first_name: string;
  last_name: string;
  email: string;
  locale: string;
  avatar: [];
};


export type CraftableProUserInviteUserForm = {
    email: string
    role_id: string,
}

export type InviteUserForm = {
    first_name: string;
    last_name: string;
    email: string;
    locale: string;
    password: string;
    password_confirmation: string;
    avatar: [];
};
