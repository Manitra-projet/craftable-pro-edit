import { DatePicker } from "craftable-pro/Components";

export default {
  title: "Components/Form/DatePicker",
  component: DatePicker,
  argTypes: {
    mode: {
      options: ["date", "dateTime"],
      defaultValue: "date",
    },
    label: {
      type: "string",
    },
    name: {
      type: "string",
      required: true,
    },
    rules: {
      type: "string",
    },
    required: {
      type: "boolean",
      defaultValue: false,
    },
    disabled: {
      type: "boolean",
      defaultValue: false,
    },
  },
};

const Template = (args) => ({
  components: { DatePicker },
  setup() {
    return { args };
  },
  template: '<DatePicker v-bind="args" />',
});

export const Default = Template.bind({});

export const WithTime = Template.bind({});
WithTime.args = {
  mode: "dateTime",
};
