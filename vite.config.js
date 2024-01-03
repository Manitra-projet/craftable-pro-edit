import { defineConfig, loadEnv, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";
import tailwindcss from "tailwindcss";

export default defineConfig({
    plugins: [
        splitVendorChunkPlugin(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                "resources/js/custom-app/index.ts",
                "resources/css/custom-app.css",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss({
                    config: "./custom-app.tailwind.config.js",
                }),
            ],
        },
    },
    resolve: {
        alias: {
            "@": resolve(__dirname, "./resources/js"),
            "custom-app": resolve(
                __dirname,
                "./vendor/brackets/custom-app/resources/js"
            ),
            ziggy: resolve(__dirname, "./vendor/tightenco/ziggy"),
        },
    },
});
