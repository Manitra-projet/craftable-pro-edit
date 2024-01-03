import { SelectInput } from "craftable-pro/Components";
import { ref } from "vue";

export default {
  title: "Components/Form/SelectInput",
  component: SelectInput,
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
      defaultValue: "",
    },
    disabled: {
      type: "boolean",
      defaultValue: false,
    },
    size: {
      options: ["sm", "md", "lg"],
      defaultValue: "md",
    },
    options: {
      type: "array",
      defaultValue: ["Option 1", "Option 2", "Option 3"],
    },
  },
};

const Template = (args) => ({
  components: { SelectInput },
  setup() {
    return { args };
  },
  template: '<SelectInput v-bind="args" />',
});

export const Default = Template.bind({});
export const WithPlaceholder = Template.bind({});
WithPlaceholder.args = {
  placeholder: "Select an option",
};
