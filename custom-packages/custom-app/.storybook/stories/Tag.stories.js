import { Tag } from "craftable-pro/Components";
import { ArrowUpRightIcon } from "@heroicons/vue/20/solid";

export default {
  title: "Components/Tag",
  component: Tag,
  argTypes: {
    color: {
      options: [
        "success",
        "info",
        "warning",
        "danger",
        "amber",
        "purple",
        "cyan",
        "gray",
      ],
      defaultValue: "amber",
    },
    size: {
      options: ["sm", "md"],
      defaultValue: "md",
    },
    showDot: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    dissmisable: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    rounded: {
      control: { type: "boolean" },
      defaultValue: false,
    },
    icon: {
      defaultValue: undefined,
    },
    default: {
      type: "string",
      defaultValue: "Tag label",
    },
  },
};

const Template = (args) => ({
  components: { Tag },
  setup() {
    return { args };
  },
  template: '<Tag v-bind="args">{{ args.default }}</Tag>',
});

export const Default = Template.bind({});

export const Dissmisable = Template.bind({});
Dissmisable.args = {
  dissmisable: true,
};

export const WithDot = Template.bind({});
WithDot.args = {
  showDot: true,
};

export const WithIcon = Template.bind({});
WithIcon.args = {
  icon: ArrowUpRightIcon,
};

export const Rounded = Template.bind({});
Rounded.args = {
  rounded: true,
};

export const WithIconRounded = Template.bind({});
WithIconRounded.args = {
  icon: ArrowUpRightIcon,
  rounded: true,
};
