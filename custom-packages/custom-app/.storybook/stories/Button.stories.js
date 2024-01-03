import { Button } from "craftable-pro/Components";
import { TrashIcon, PencilIcon } from "@heroicons/vue/24/solid";

export default {
  title: "Components/Button",
  component: Button,
  argTypes: {
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
    loading: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    loadingText: {
      type: "string",
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
  components: { Button },
  setup() {
    return { args };
  },
  template: '<Button v-bind="args">{{ args.default }}</Button>',
});

export const Default = Template.bind({});

export const Outline = Template.bind({});
Outline.args = {
  variant: "outline",
};

export const Ghost = Template.bind({});
Ghost.args = {
  variant: "ghost",
};

export const WithLeftIcon = Template.bind({});
WithLeftIcon.args = {
  leftIcon: PencilIcon,
};

export const WithRightIcon = Template.bind({});
WithRightIcon.args = {
  rightIcon: TrashIcon,
};
