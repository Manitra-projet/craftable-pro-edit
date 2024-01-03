import { RadioGroup } from "craftable-pro/Components";
import { ref } from "vue";

export default {
  title: "Components/Form/RadioGroup",
  component: RadioGroup,
  argTypes: {
    name: {
      type: "string",
      defaultValue: "input",
    },
    label: {
      type: "string",
      defaultValue: "Optional label",
    },
    options: {
      type: "array",
      defaultValue: [
        {
          label: "Option 1",
          value: "option-1",
        },
        {
          label: "Option 2",
          value: "option-2",
        },
        {
          label: "Option 3",
          value: "option-3",
        },
      ],
    },
    default: {
      type: "string",
      defaultValue: "",
    },
  },
};

const Template = (args) => ({
  components: { RadioGroup },
  setup() {
    return { args };
  },
  template:
    '<RadioGroup v-bind="args"><template v-if="args.default">{{ args.default }}</template></RadioGroup>',
});

export const Default = Template.bind({});
export const WithSlot = Template.bind({});
WithSlot.args = {
  default: "Some optional slot is here",
};
