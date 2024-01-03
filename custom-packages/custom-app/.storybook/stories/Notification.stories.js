import { Notification } from "craftable-pro/Components";
import { useToast } from "@brackets/vue-toastification";
import { watch } from "vue";
import MDXDocumentation from "./Notification.mdx";

export default {
  title: "Components/Notification",
  component: Notification,
  parameters: {
    docs: {
      page: MDXDocumentation,
    },
  },
  argTypes: {
    type: {
      options: ["warning", "info", "success", "error", "default"],
      control: {
        type: "select",
      },
      defaultValue: "default",
    },
    text: {
      type: "string",
      defaultValue: "Some default title",
    },
    description: {
      type: "string",
      defaultValue: "... with some optional description",
    },
  },
};

const Template = (args) => ({
  components: { Notification },
  setup() {
    console.log(args);
    const toast = useToast();

    toast(args.text, {
      type: args.type,
      description: args.description,
    });

    watch(
      () => args,
      () =>
        toast(args.text, {
          type: args.type,
          description: args.description,
        }),
      { deep: true }
    );

    return { args };
  },
  template: "",
});

export const Default = Template.bind({});
