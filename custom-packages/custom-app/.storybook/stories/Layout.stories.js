import AuthenticatedLayout from "craftable-pro/Layouts/Authenticated.vue";
import GuestLayout from "craftable-pro/Layouts/Guest.vue";
import { Default as PageHeader } from "./PageHeader.stories";

// More on default export: https://storybook.js.org/docs/vue/writing-stories/introduction#default-export
export default {
  title: "Components/Layout",
  component: AuthenticatedLayout,
  argTypes: {
    default: {
      type: "string",
      defaultValue: "",
    },
  },
};

// More on component templates: https://storybook.js.org/docs/vue/writing-stories/introduction#using-args
export const Authenticated = (args) => ({
  // Components used in your story `template` are defined in the `components` object
  components: { AuthenticatedLayout, PageHeader },
  // The story's `args` need to be mapped into the template through the `setup()` method
  setup() {
    return { args };
  },
  // And then the `args` are bound to your component with `v-bind="args"`
  template: `<AuthenticatedLayout v-bind='args'><template v-if='args.default'>${args.default}</template></AuthenticatedLayout>`,
});

export const Guest = (args) => ({
  // Components used in your story `template` are defined in the `components` object
  components: { GuestLayout },
  // The story's `args` need to be mapped into the template through the `setup()` method
  setup() {
    return { args };
  },
  // And then the `args` are bound to your component with `v-bind="args"`
  template: `<GuestLayout v-bind='args' ><template v-if='args.default'>${args.default}</template></GuestLayout>`,
});
