import { Toggle } from "craftable-pro/Components";
import { ref } from "vue";

export default {
  title: "Components/Form/Toggle",
  component: Toggle,
  argTypes: {
    name: {
      type: "string",
      defaultValue: "input",
    },
    checked: {
      type: "boolean",
      defaultValue: false,
    },
    withIcon: {
      type: "boolean",
      defaultValue: false,
    },
  },
};

const Template = (args) => ({
  components: { Toggle },
  setup() {
    const checked = ref(args.checked ?? false);

    return { args, checked };
  },
  template: '<Toggle v-bind="args" v-model="checked" />',
});

export const Default = Template.bind({});
export const WithIcon = Template.bind({});
WithIcon.args = {
  withIcon: true,
};

export const Checked = Template.bind({});
Checked.args = {
  checked: true,
};
