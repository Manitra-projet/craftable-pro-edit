import {
  ButtonGroup,
  Button,
  IconButton,
  Dropdown,
} from "craftable-pro/Components";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  ChevronDownIcon,
} from "@heroicons/vue/24/solid";

export default {
  title: "Components/ButtonGroup",
  component: ButtonGroup,
  argTypes: {
    color: {
      options: [
        "primary",
        "secondary",
        "gray",
        "warning",
        "danger",
        "info",
        "success",
      ],
      defaultValue: "gray",
    },
    variant: {
      options: ["solid", "outline", "ghost"],
      defaultValue: "outline",
    },
    size: {
      options: ["xs", "sm", "md", "lg", "xl"],
      defaultValue: "md",
    },
  },
};

const Template = (args) => ({
  components: { ButtonGroup, Button },
  setup() {
    return { args };
  },
  template: `
  <ButtonGroup v-bind="args">
    <Button>Years</Button>
    <Button>Months</Button>
    <Button>Days</Button>
  </ButtonGroup>`,
});

export const Default = Template.bind({});

const WithIconsTemplate = (args) => ({
  components: { ButtonGroup, IconButton, ChevronLeftIcon, ChevronRightIcon },
  setup() {
    const leftIconButtonArgs = {
      icon: ChevronLeftIcon,
    };

    const rightIconButtonArgs = {
      icon: ChevronRightIcon,
    };

    return { args, leftIconButtonArgs, rightIconButtonArgs };
  },
  template: `
  <ButtonGroup v-bind="args">
    <IconButton v-bind="leftIconButtonArgs" />
    <IconButton v-bind="rightIconButtonArgs" />
  </ButtonGroup>`,
});

export const WithIcons = WithIconsTemplate.bind({});

const WithDropdownTemplate = (args) => ({
  components: {
    ButtonGroup,
    Button,
    IconButton,
    Dropdown,
    ChevronDownIcon,
  },
  setup() {
    const dropdownIconButtonArgs = {
      icon: ChevronDownIcon,
    };

    return { args, dropdownIconButtonArgs };
  },
  template: `
  <ButtonGroup v-bind="args" color="primary" variant="solid">
    <Button>Save changes</Button>
    <Dropdown>
      <template #button>
        <IconButton v-bind="dropdownIconButtonArgs" class="rounded-l-none" />
      </template>
      <template #content>
        Slot for dropdown content.
      </template>
    </Dropdown>
  </ButtonGroup>`,
});

export const WithDropdown = WithDropdownTemplate.bind({});

const WithCustomButtonPropsTemplate = (args) => ({
  components: { ButtonGroup, Button },
  setup() {
    return { args };
  },
  template: `
  <ButtonGroup v-bind="args">
    <Button>Years</Button>
    <Button variant="solid" color="secondary">Months</Button>
    <Button>Days</Button>
  </ButtonGroup>`,
});

export const WithCustomButtonProps = WithCustomButtonPropsTemplate.bind({});
