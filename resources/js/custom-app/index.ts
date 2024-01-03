import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import { autoAnimatePlugin } from "@formkit/auto-animate/vue";
import Toast, { POSITION } from "@brackets/vue-toastification";
import "@brackets/vue-toastification/dist/index.css";
import { ZiggyVue } from "ziggy/src/js/vue";
import { AuthenticatedLayout, GuestLayout } from "custom-app/Layouts";
import { Notification } from "custom-app/Components";
import {
  i18nVue,
  loadTranslations,
} from "custom-app/plugins/laravel-vue-i18n";
import { can } from "custom-app/plugins/can";
import { PageProps } from "custom-app/types/page";
import {AppPlugin} from '../plugin/app-plugin'


const envConfig = import.meta.env;
const appName = "Custom App";
const lang = document.documentElement.lang
  ? document.documentElement.lang.replace("-", "_")
  : "en";

createInertiaApp({
  title: (title) => {
    const titleElement = document.querySelector("title");

    if (titleElement && !titleElement.hasAttribute("inertia")) {
      titleElement.remove();
    }

    return title ? `${title} - ${appName}` : appName;
  },
  progress: { color: "#4B5563" },
  resolve: async (name) => {
    const pages = import.meta.glob("./Pages/**/*.vue");
    const page = (await pages[`./Pages/${name}.vue`]()).default;
    if (page.layout === undefined) {
      if (name.startsWith("Auth/")) {
        page.layout = GuestLayout;
      } else {
        page.layout = AuthenticatedLayout;
      }
    }

    return page;
  },
  setup({ el, App, props, plugin }) {
    loadTranslations(
      `${envConfig.VITE_BASE_URL}/public/lang/${
        (props.initialPage.props.auth as PageProps["auth"])?.user?.locale ??
        lang
      }/custom-app.json`,
      (translations: JSON) => {
        const _createApp = createApp({ render: () => h(App, (()=>{
          var url_edit = props.initialPage.url;
          const url = String(props.initialPage.url).split('/');
          if(url.length > 1){
            url_edit = Array(...url.filter((item,index,arr)=> {
              return item === '' || !arr.toSpliced(0,index+1).includes(item);
            })).join('/');
          }
          return{
            ...props,
            initialPage:{
              ...props.initialPage,
              url: url_edit
            }
          }
        })()) })
          .use(plugin)
          .use(Toast, {
            transition: "Vue-Toastification__fade",
            rootComponent: Notification,
            position: POSITION.BOTTOM_RIGHT,
          })
          .use(i18nVue, {
            resolve: (lang: string) => {
              return translations;
            },
          })
          .use(autoAnimatePlugin)
          .use(ZiggyVue)
          .component("Link", Link)
          .directive("can", can);

          /*** */
        _createApp.use(AppPlugin);
          /*** */
        _createApp.mount(el);
        return _createApp;
      }
    );
  },
});
