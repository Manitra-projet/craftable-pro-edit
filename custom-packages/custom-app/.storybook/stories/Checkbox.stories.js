import { Checkbox } from "craftable-pro/Components";
import { ref } from "vue";

export default {
  title: "Components/Form/Checkbox",
  component: Checkbox,
  argTypes: {
    name: {
      type: "string",
      defaultValue: "input",
    },
    label: {
      type: "string",
      defaultValue: "Optional label",
    },
    checked: {
      type: "boolean",
      defaultValue: false,
    },
    indeterminate: {
      type: "boolean",
      defaultValue: false,
    },
  },
};

const Template = (args) => ({
  components: { Checkbox },
  setup() {
    const checked = ref(args.checked ?? false);

    return { args, checked };
  },
  template: '<Checkbox v-bind="args" v-model="checked" />',
});

export const Default = Template.bind({});
export const Indeterminate = Template.bind({});
Indeterminate.args = {
  indeterminate: true,
};

export const Checked = Template.bind({});
Checked.args = {
  checked: true,
};
