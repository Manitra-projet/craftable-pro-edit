import { Avatar, Tooltip } from "craftable-pro/Components";

export default {
  title: "Components/Avatar",
  component: Avatar,
  argTypes: {
    src: {
      type: "string",
    },
    name: {
      type: "string",
    },
    size: {
      options: ["xs", "sm", "md", "lg", "xl"],
      defaultValue: "md",
    },
    shape: {
      options: ["circle", "square"],
      defaultValue: "circle",
    },
  },
};

const Template = (args) => ({
  components: { Avatar },
  setup() {
    return { args };
  },
  template: '<Avatar v-bind="args" />',
});

export const Default = Template.bind({});

export const WithName = Template.bind({});
WithName.args = {
  name: "Craftable Pro",
};

export const WithImage = Template.bind({});
WithImage.args = {
  src: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
};

export const Squared = Template.bind({});
Squared.args = {
  src: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
  shape: "square",
};

const WithTooltipTemplate = (args) => ({
  components: { Avatar, Tooltip },
  setup() {
    return { args };
  },
  template: `
  <div class="flex items-center justify-center mt-20">
    <Tooltip position="bottom" :useHover="false">
      <template #button>
        <Avatar v-bind="args" />
      </template>
      <template #content>
        ${args.name}
      </template>
    </Tooltip>
  </div>
  `,
});
export const WithTooltip = WithTooltipTemplate.bind({});
WithTooltip.args = {
  name: "Craftable Pro",
};
