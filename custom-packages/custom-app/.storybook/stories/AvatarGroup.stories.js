import { AvatarGroup, Avatar, Tooltip } from "craftable-pro/Components";

export default {
  title: "Components/AvatarGroup",
  component: AvatarGroup,
  argTypes: {
    default: {
      type: "string",
      defaultValue: `
        <Avatar src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" />
        <Avatar src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" />
        <Avatar src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" />
        <Avatar src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" />
      `,
    },
    size: {
      options: ["xs", "sm", "md", "lg", "xl"],
      defaultValue: "md",
    },
    shape: {
      options: ["circle", "square"],
      defaultValue: "circle",
    },
    additionalCount: {
      type: "number",
    },
  },
};

const Template = (args) => ({
  components: { AvatarGroup, Avatar, Tooltip },
  setup() {
    return { args };
  },
  template: `
  <div class="flex items-center justify-center mt-32">
    <AvatarGroup v-bind="args">
      ${args.default}
    </AvatarGroup>
  </div>
  `,
});

export const Default = Template.bind({});
export const Squared = Template.bind({});
Squared.args = {
  shape: "square",
};

export const WithName = Template.bind({});
WithName.args = {
  default: `
    <Avatar name="Janci Timoransky" />
    <Avatar name="Stanley Varga" />
    <Avatar name="Branci Zavracky" />
    <Avatar name="Pali Perdik" />
  `,
};

export const WithTooltips = Template.bind({});
WithTooltips.args = {
  default: `
    <Tooltip position="top">
      <template #button>
        <Avatar name="Stanley Varga" />
      </template>
      <template #content>
        Stanley Varga
      </template>
    </Tooltip>
    <Tooltip position="top" noPadding>
      <template #button>
        <Avatar name="Bilbo Pytlik" />
      </template>
      <template #content>
        <div class="relative w-screen flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-pink-500 hover:bg-gray-50">
          <div class="flex-shrink-0">
            <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="">
          </div>
          <div class="min-w-0 flex-1">
            <span class="absolute inset-0" aria-hidden="true"></span>
            <p class="text-sm font-medium text-gray-900">Leslie Abbott</p>
            <p class="truncate text-sm text-gray-500">Co-Founder / CEO</p>
          </div>
        </div>
      </template>
    </Tooltip>
    <Tooltip position="top">
      <template #button>
        <Avatar name="Branci Zavracky" />
      </template>
      <template #content>
        Branci Zavracky
      </template>
    </Tooltip>
  `,
};

export const WithLimit = Template.bind({});
WithLimit.args = {
  default: `
    <Avatar name="Janci Timoransky" />
    <Avatar name="Stanley Varga" />
    <Avatar name="Branci Zavracky" />
    <Avatar name="Pali Perdik" />
  `,
  additionalCount: 3,
};
