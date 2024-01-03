import { Accordion } from "craftable-pro/Components";

export default {
  title: "Components/Accordion",
  component: Accordion,
  argTypes: {
    title: {
      type: "string",
      defaultValue: "Accordion title",
    },
    content: {
      type: "string",
      defaultValue: "Body of an accordion",
    },
  },
};

const Template = (args) => ({
  components: { Accordion },
  setup() {
    return { args };
  },
  template: `<Accordion v-bind="args">
      <template #title>{{ args.title }}</template>
      <template #content>{{ args.content }}</template>
    </Accordion>`,
});

export const Default = Template.bind({});
