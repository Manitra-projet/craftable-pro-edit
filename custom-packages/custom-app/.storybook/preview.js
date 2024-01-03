import "./styles.css";
import { app } from "@storybook/vue3";
import { i18nVue } from "laravel-vue-i18n";
import Toast from "@brackets/vue-toastification";
import { Notification } from "../resources/js/Components";
import { autoAnimatePlugin } from "@formkit/auto-animate/vue";
import "../node_modules/@brackets/vue-toastification/dist/index.css";

app.use(autoAnimatePlugin).use(Toast, {
  transition: "Vue-Toastification__fade",
  rootComponent: Notification,
});

export const parameters = {
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/,
    },
  },
  options: {
    storySort: {
      order: ["Intro", "File structure", "Customization", "Translations"],
    },
  },
};

app.use(i18nVue, {
  resolve: (lang) => import(`../stubs/lang/${lang}.json`),
});
