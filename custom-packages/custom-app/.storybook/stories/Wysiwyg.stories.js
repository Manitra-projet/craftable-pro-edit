import { Wysiwyg } from "craftable-pro/Components";

export default {
  title: "Components/Form/Wysiwyg",
  component: Wysiwyg,
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
    disabled: {
      type: "boolean",
      defaultValue: false,
    },
    required: {
      type: "boolean",
      defaultValue: false,
    },
  },
};

const Template = (args) => ({
  components: { Wysiwyg },
  setup() {
    return { args };
  },
  template: '<Wysiwyg v-bind="args" />',
});

export const Default = Template.bind({});
