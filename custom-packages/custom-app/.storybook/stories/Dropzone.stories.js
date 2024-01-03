import { Dropzone, TextInput } from "craftable-pro/Components";

export default {
  title: "Components/Form/Dropzone",
  component: Dropzone,
  argTypes: {
    name: {
      type: "string",
      defaultValue: "input",
    },
    label: {
      type: "string",
      defaultValue: "Optional label",
    },
    inputs: {},
    onlyThumbs: {
      type: "boolean",
      defaultValue: false,
    },
    maxNumberOfFiles: {
      type: "number",
      defaultValue: 1,
    },
    accept: {
      type: "array",
      defaultValue: "image/png,image/jpeg,.gif",
    },
    maxSizeInKB: {
      type: "number",
      defaultValue: 1024,
    },
  },
};

const Template = (args) => ({
  components: { Dropzone, TextInput },
  setup() {
    return { args };
  },
  template: `<Dropzone v-bind="args"><template #inputs="{ file }" v-if="args.inputs">${args.inputs}</template></Dropzone>`,
});

export const Default = Template.bind({});

export const WithCustomInputs = Template.bind({});

WithCustomInputs.args = {
  inputs: `<TextInput
        size="sm"
        v-model="file.meta_data.name"
        label="Name"
        name="name"
      />
      <TextInput
        size="sm"
        v-model="file.meta_data.alt"
        label="Alt text"
        name="alt"
      />`,
};

export const WithThumbsOnly = Template.bind({});

WithThumbsOnly.args = {
  onlyThumbs: true,
};
