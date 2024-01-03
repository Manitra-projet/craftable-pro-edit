import { ImageEditor } from "craftable-pro/Components";

export default {
  title: "ImageEditor",
  component: ImageEditor,
};

const Template = (args) => ({
  components: { ImageEditor },
  setup() {
    return { args };
  },
  template: `<ImageEditor v-bind="args" />`,
});

export const Default = Template.bind({});
