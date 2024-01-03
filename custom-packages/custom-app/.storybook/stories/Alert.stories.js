import { Alert } from "craftable-pro/Components";

export default {
  title: "Components/Alert",
  component: Alert,
  argTypes: {
    type: {
      options: ["warning", "info", "success", "danger"],
      defaultValue: "warning",
    },
    title: {
      type: "string",
      defaultValue: "",
    },
    default: {
      type: "string",
      defaultValue: "Body of a alert slot",
    },
  },
};

const Template = (args) => ({
  components: { Alert },
  setup() {
    return { args };
  },
  template:
    '<Alert v-bind="args"><template v-if="args.title" #title>{{ args.title }}</template>{{ args.default }}</Alert>',
});

export const Default = Template.bind({});
export const WithTitle = Template.bind({});
WithTitle.args = {
  title: "Some Optional Title",
};
