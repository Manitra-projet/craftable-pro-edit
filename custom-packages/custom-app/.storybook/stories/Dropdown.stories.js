import { Dropdown, Button } from "craftable-pro/Components";

// More on default export: https://storybook.js.org/docs/vue/writing-stories/introduction#default-export
export default {
  title: "Components/Dropdown",
  component: Dropdown,
};

// More on component templates: https://storybook.js.org/docs/vue/writing-stories/introduction#using-args
export const Default = (args) => ({
  // Components used in your story `template` are defined in the `components` object
  components: { Dropdown, Button },
  // The story's `args` need to be mapped into the template through the `setup()` method
  setup() {
    return {};
  },
  // And then the `args` are bound to your component with `v-bind="args"`
  template: `
    <Dropdown v-bind='args'>
      <template #button>
        <Button>Open menu</Button>
      </template>
      <template #content>
        Slot for dropdown content.
      </template>
    </Dropdown>
  `,
});
