import { Multiselect, Avatar } from "craftable-pro/Components";
import MDXDocumentation from "./Multiselect.mdx";

export default {
  title: "Components/Form/Multiselect",
  component: Multiselect,
  parameters: {
    docs: {
      page: MDXDocumentation,
    },
  },
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
    mode: {
      options: ["single", "multiple", "tags"],
      defaultValue: "multiple",
    },
    options: {
      type: "array",
      defaultValue: ["Option 1", "Option 2", "Option 3"],
    },
  },
};

const Template = (args) => ({
  components: { Multiselect, Avatar },
  setup() {
    return { args };
  },
  template: `<Multiselect v-bind="args"><template v-if="args.option" #option="{option}">${args.option}</template></Multiselect>`,
});

export const Default = Template.bind({});
export const SingleSelect = Template.bind({});
SingleSelect.args = {
  mode: "single",
};

export const WithArrayOfObjectsAsOptions = Template.bind({});
WithArrayOfObjectsAsOptions.args = {
  options: [
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
};

export const WithCustomOptionSlot = Template.bind({});
WithCustomOptionSlot.args = {
  option:
    "<div class='flex items-center'><Avatar src='https://robohash.org/asdas' size='sm' class='mr-3' />Option</div>",
};
