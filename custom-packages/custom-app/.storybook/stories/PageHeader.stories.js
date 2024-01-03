import { PageHeader, Button } from "craftable-pro/Components";

export default {
  title: "Components/PageHeader",
  component: PageHeader,
  argTypes: {
    title: {
      type: "string",
      defaultValue: "Some page title goes here",
    },
    subtitle: {
      type: "string",
      defaultValue: "",
    },
    subtitle: {
      type: "default",
      defaultValue: "",
    },
    sticky: {
      type: "boolean",
      defaultValue: false,
    },
  },
};

const Template = (args) => ({
  components: { PageHeader, Button },
  setup() {
    return { args };
  },
  template: `<PageHeader v-bind="args"><template v-if="args.default">${args.default}</Button></template></PageHeader>`,
});

export const Default = Template.bind({});
export const WithSubtitle = Template.bind({});
WithSubtitle.args = {
  subtitle: "Optional subtitle goes here",
};
export const WithSlot = Template.bind({});
WithSlot.args = {
  subtitle: "Optional subtitle goes here",
  default:
    "<div class='flex gap-x-3'><Button color='gray' variant='outline'>Click me 2</Button> <Button>Click me</Button></div>",
};
