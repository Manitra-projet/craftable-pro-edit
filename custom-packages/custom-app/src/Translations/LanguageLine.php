<?php

namespace CustomPackages\CustomApp\Translations;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Spatie\TranslationLoader\LanguageLine as ParentLanguageLine;

class LanguageLine extends ParentLanguageLine
{
    use SoftDeletes;

    public static function getTranslationsForGroup(string $locale, string $group): array
    {
        if (Cache::has(static::getCacheKey($group, $locale))) {
            return Cache::get(static::getCacheKey($group, $locale));
        }

        $translations = static::query()
            ->where('group', $group)
            ->get()
            ->reduce(function ($lines, self $languageLine) use ($group, $locale) {
                $translation = $languageLine->getTranslation($locale);

                if ($translation !== null && $group === '*') {
                    // Make a flat array when returning json translations
                    $lines[$languageLine->key] = $translation;
                } elseif ($translation !== null && $group !== '*') {
                    // Make a nested array when returning normal translations
                    Arr::set($lines, $languageLine->key, $translation);
                }

                return $lines;
            }) ?? [];

        Cache::put(static::getCacheKey($group, $locale), $translations);

        return Cache::get(static::getCacheKey($group, $locale));
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getTranslation(string $locale): ?string
    {
        if (! isset($this->text[$locale])) {
            $fallback = config('app.fallback_locale');

            // This is needed, because laravel by default returns group.key and we want only key
            return $this->text[$fallback] ?? $this->key;
        }

        return $this->text[$locale];
    }
}
