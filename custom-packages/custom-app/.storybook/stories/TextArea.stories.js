import { TextArea } from "craftable-pro/Components";

export default {
  title: "Components/Form/TextArea",
  component: TextArea,
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
  components: { TextArea },
  setup() {
    return { args };
  },
  template: '<TextArea v-bind="args" />',
});

export const Default = Template.bind({});
