import { IconButton } from "craftable-pro/Components";
import { TrashIcon, PencilIcon } from "@heroicons/vue/24/solid";

export default {
  title: "Components/IconButton",
  component: IconButton,
  argTypes: {
    icon: {
      control: {
        type: null,
      },
      defaultValue: TrashIcon,
    },
    rounded: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    color: {
      options: [
        "primary",
        "secondary",
        "gray",
        "warning",
        "danger",
        "info",
        "success",
      ],
      defaultValue: "primary",
    },
    variant: {
      options: ["solid", "outline", "ghost"],
      defaultValue: "solid",
    },
    type: {
      options: ["button", "submit", "reset"],
      defaultValue: "button",
    },
    size: {
      options: ["xs", "sm", "md", "lg", "xl"],
      defaultValue: "md",
    },
    disabled: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    default: {
      type: "string",
      defaultValue: "Button",
    },
    leftIcon: {
      control: {
        type: null,
      },
    },
    rightIcon: {
      control: {
        type: null,
      },
    },
  },
};

const Template = (args) => ({
  components: { IconButton },
  setup() {
    return { args };
  },
  template:
    '<IconButton :icon="TrashIcon" v-bind="args">{{ args.default }}</IconButton>',
});

export const Default = Template.bind({});

export const Ghost = Template.bind({});
Ghost.args = {
  variant: "ghost",
  color: "danger",
};

export const Rounded = Template.bind({});
Rounded.args = {
  variant: "ghost",
  color: "danger",
  rounded: true,
};
