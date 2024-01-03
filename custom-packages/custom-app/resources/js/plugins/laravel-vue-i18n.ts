import axios from "axios";
import {
  i18nVue as i18nVueOriginal,
  trans as transOriginal,
  wTrans as wTransOriginal,
  transChoice as transChoiceOriginal,
  wTransChoice as wTransChoiceOriginal,
} from "laravel-vue-i18n";
import { ReplacementsInterface } from "laravel-vue-i18n/dist/interfaces/replacements";
import { ComputedRef, Plugin } from "vue";

export function loadTranslations(url: string, callback: CallableFunction) {
  axios
    .get(`${url}?t=${Date.now().toString()}`) // Add timestamp to prevent caching
    .then((res) => {
      callback(res.data);
    })
    .catch((err) => {
      callback({});
    });
}

export function trans(
  group: string,
  key: string,
  replacements?: ReplacementsInterface
): string;

export function trans(
  key: string,
  replacements?: ReplacementsInterface
): string;

export function trans(
  a: string,
  b?: string | ReplacementsInterface,
  c?: ReplacementsInterface
): string {
  if (typeof b === "string") {
    return transOriginal(`${a}.${b}`, c);
  }
  return transOriginal(a, b);
}

export function wTrans(
  group: string,
  key: string,
  replacements?: ReplacementsInterface
): ComputedRef<string>;

export function wTrans(
  key: string,
  replacements?: ReplacementsInterface
): ComputedRef<string>;

export function wTrans(
  a: string,
  b?: string | ReplacementsInterface,
  c?: ReplacementsInterface
): ComputedRef<string> {
  if (typeof b === "string") {
    return wTransOriginal(`${a}.${b}`, c);
  }
  return wTransOriginal(a, b);
}

export function transChoice(
  group: string,
  key: string,
  number: number,
  replacements?: ReplacementsInterface
): string;

export function transChoice(
  key: string,
  number: number,
  replacements?: ReplacementsInterface
): string;

export function transChoice(
  a: string,
  b: string | number,
  c: any,
  d?: ReplacementsInterface
): string {
  if (typeof b === "string") {
    return transChoiceOriginal(`${a}.${b}`, c, d);
  }
  return transChoiceOriginal(a, b, c);
}

export function wTransChoice(
  group: string,
  key: string,
  number: number,
  replacements?: ReplacementsInterface
): ComputedRef<string>;

export function wTransChoice(
  key: string,
  number: number,
  replacements?: ReplacementsInterface
): ComputedRef<string>;

export function wTransChoice(
  a: string,
  b: string | number,
  c: any,
  d?: ReplacementsInterface
): ComputedRef<string> {
  if (typeof b === "string") {
    return wTransChoiceOriginal(`${a}.${b}`, c, d);
  }
  return wTransChoiceOriginal(a, b, c);
}

export const i18nVue = {
  install: (app, options) => {
    i18nVueOriginal.install?.(app, options);

    app.config.globalProperties.$t = trans;
    app.config.globalProperties.$tChoice = transChoice;
  },
} as Plugin;
