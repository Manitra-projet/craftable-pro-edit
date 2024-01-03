import { Plugin } from "vue";

export const AppPlugin = {
  install: (app, options) => {
    app.config.globalProperties.$manitra = 'manitra';
  },
} as Plugin;