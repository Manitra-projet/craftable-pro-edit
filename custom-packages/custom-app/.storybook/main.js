const postcss = require("postcss");
const path = require("path");
const { mergeConfig } = require("vite");

module.exports = {
  stories: [
    "./stories/**/*.stories.@(js|jsx|ts|tsx|mdx)",
    "./docs/**/*.stories.@(mdx)",
  ],
  addons: [
    "@storybook/addon-controls",
    {
      name: "@storybook/addon-postcss",
      options: {
        postcssLoaderOptions: {
          implementation: postcss,
        },
      },
    },
    {
      name: "@storybook/addon-docs",
      options: {
        vueDocgenOptions: {
          alias: {
            "@": path.resolve(__dirname, "../"),
          },
        },
      },
    },
  ],
  framework: "@storybook/vue3",
  core: {
    builder: "@storybook/builder-vite",
  },
  typescript: {
    check: false,
    checkOptions: {},
  },
  async viteFinal(config) {
    // Merge custom configuration into the default config
    return mergeConfig(config, {
      base: process.env.BASE_PATH || config.base,
      resolve: {
        alias: {
          "@/craftable-pro": path.resolve(__dirname, "../stubs/resources/js"),
          "craftable-pro": path.resolve(__dirname, "../resources/js"),
        },
      },
    });
  },
};
