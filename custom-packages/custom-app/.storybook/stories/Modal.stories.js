import { Modal, Button } from "craftable-pro/Components";
import { ref } from "vue";

export default {
  title: "Components/Modal",
  component: Modal,
  argTypes: {
    type: {
      options: ["warning", "info", "success", "danger"],
      defaultValue: "danger",
    },
    open: {
      type: "boolean",
      defaultValue: true,
    },
    centered: {
      type: "boolean",
      defaultValue: false,
    },
    alignButtons: {
      options: ["left", "right"],
      defaultValue: "left",
    },
    title: {
      type: "string",
      defaultValue: "Some title goes here",
    },
    content: {
      type: "string",
      defaultValue: "Some content text goes here",
    },
  },
};

const Template = (args) => ({
  components: { Modal, Button },
  setup() {
    return { args };
  },
  template: `
  <Modal v-bind="args">
    <template v-if="args.title" #title>{{ args.title }}</template>
    <template v-if="args.content" #content>{{ args.content }}</template>
    <template #buttons="{ setIsOpen }">
        <Button>
          Delete
        </Button>
        <Button @click.prevent="() => setIsOpen()" color="gray" variant="outline">
          Cancel
        </Button>
      </template>
  </Modal>
`,
});

export const Default = Template.bind({});

export const RightAlignedButtons = Template.bind({});
RightAlignedButtons.args = {
  alignButtons: "right",
};

export const Centered = Template.bind({});
Centered.args = {
  centered: true,
};

export const Success = Template.bind({});
Success.args = {
  type: "success",
};

export const Warning = Template.bind({});
Warning.args = {
  type: "warning",
};

export const Info = Template.bind({});
Info.args = {
  type: "info",
};
