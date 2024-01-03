import { TextInput } from "craftable-pro/Components";
import { TrashIcon, PencilIcon } from "@heroicons/vue/24/solid";

export default {
  title: "Components/Form/TextInput",
  component: TextInput,
  argTypes: {
    name: {
      type: "string",
      defaultValue: "input",
    },
    label: {
      type: "string",
      defaultValue: "Optional label",
    },
    placeholder: {
      type: "string",
      defaultValue: "Placeholder",
    },

    type: {
      options: ["text", "email", "password", "number", "tel", "url"],
      defaultValue: "text",
    },
    disabled: {
      type: "boolean",
      defaultValue: false,
    },
    required: {
      type: "boolean",
      defaultValue: false,
    },
    clearable: {
      type: "boolean",
      defaultValue: false,
    },
    size: {
      options: ["sm", "md", "lg"],
      defaultValue: "md",
    },
    leadingAddon: {
      type: "string",
      defaultValue: "",
    },
    trailingAddon: {
      type: "string",
      defaultValue: "",
    },
  },
};

const Template = (args) => ({
  components: { TextInput, TrashIcon, PencilIcon },
  setup() {
    return { args };
  },
  template: '<TextInput v-bind="args" />',
});

export const Default = Template.bind({});
export const Password = Template.bind({});
Password.args = {
  name: "password",
  type: "password",
};

export const WithAddons = Template.bind({});
WithAddons.args = {
  leadingAddon: "www.",
  trailingAddon: ".com",
  placeholder: "Enter your domain",
};

export const WithIcons = Template.bind({});
WithIcons.args = {
  leftIcon: TrashIcon,
  rightIcon: PencilIcon,
};

export const WithAddonsAndIcons = Template.bind({});
WithAddonsAndIcons.args = {
  leftIcon: TrashIcon,
  rightIcon: PencilIcon,
  leadingAddon: "www.",
  trailingAddon: ".com",
};

export const Clearable = Template.bind({});
Clearable.args = {
  clearable: true,
};
