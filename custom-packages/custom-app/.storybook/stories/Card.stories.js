import { Card, CardHeader, CardFooter, Button } from "craftable-pro/Components";

export default {
  title: "Components/Card",
  component: Card,
  argTypes: {
    header: {
      type: "string",
      defaultValue: null,
    },
    actions: {
      type: "string",
      defaultValue: "",
    },
    default: {
      type: "string",
      defaultValue: "Body of a card slot",
    },
    footer: {
      type: "string",
      defaultValue: null,
    },
    tabs: {
      type: "array",
      defaultValue: [],
    },
    title: {
      type: "string",
      defaultValue: "",
    },
  },
};

const Template = (args) => ({
  components: { Card, Button, CardHeader, CardFooter },
  setup() {
    return { args };
  },
  template: `
    <Card v-bind="args">
      <template v-if="args.header" #header>
        ${args.header}
      </template>
      <template v-if="args.actions" #actions>
        ${args.actions}
      </template>

      {{ args.default }}

      <template v-if="args.footer" #footer>
        ${args.footer}
      </template>
    </Card>`,
});

export const Default = Template.bind({});

export const WithTitle = Template.bind({});
WithTitle.args = {
  title: "This is optional title prop",
};

export const WithActions = Template.bind({});
WithActions.args = {
  title: "This is optional title prop",
  actions: `
    <Button variant="outline" size="sm">Some action</Button>
    <Button size="sm">Some other action</Button>
  `,
};

export const WithTabs = Template.bind({});
WithTabs.args = {
  header: null,
  tabs: [
    {
      key: "tab1",
      label: "Tab 1",
      active: true,
    },
    {
      key: "tab2",
      label: "Tab 2",
    },
  ],
};

export const WithTabsAndActions = Template.bind({});
WithTabsAndActions.args = {
  actions: `
    <Button variant="outline" size="sm">Some action</Button>
    <Button size="sm">Some other action</Button>
  `,
  tabs: [
    {
      key: "tab1",
      label: "Tab 1",
      active: true,
    },
    {
      key: "tab2",
      label: "Tab 2",
    },
  ],
};

export const WithTabsAndTitleAndActions = Template.bind({});
WithTabsAndTitleAndActions.args = {
  title: "This is optional title prop",
  actions: `
    <Button variant="outline" size="sm">Some action</Button>
    <Button size="sm">Some other action</Button>
  `,
  tabs: [
    {
      key: "tab1",
      label: "Tab 1",
      active: true,
    },
    {
      key: "tab2",
      label: "Tab 2",
    },
  ],
};

export const WithCustomHeaderAndFooter = Template.bind({});
WithCustomHeaderAndFooter.args = {
  header: `
    <CardHeader class="bg-secondary-300 flex items-center justify-between">
      <h3>This is custom header</h3>
      <Button variant="outline" size="sm">Some action in custom header</Button>
    </CardHeader>
  `,
  footer: `
    <CardFooter class="bg-secondary-300">
      This is custom footer
    </CardFooter>
  `,
};
