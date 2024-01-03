<?php

namespace CustomPackages\CustomApp\Translations;

use CustomPackages\CustomApp\Settings\GeneralSettings;
use CustomPackages\CustomApp\Translations\Repositories\LanguageLineRepository;
use Illuminate\Contracts\Translation\Translator;

class TranslationsListingDataProcessor
{
    public function __construct(private LanguageLineRepository $languageLineRepository)
    {
    }

    public function getProcessedData($data): array
    {
        $locales = collect(app(GeneralSettings::class)->available_locales);

        $data->map(function ($translation) use ($locales) {
            $locales->each(function ($locale) use ($translation) {
                /** @var LanguageLine $translation */
                $translation->setTranslation($locale, $this->getCurrentTransForTranslation($translation, $locale));
            });

            return $translation->getTranslation(app()->getLocale());
        });

        return ([
            'data' => $data,
            'locales' => $locales,
            'groups' => $this->languageLineRepository->getGroups(),
        ]);
    }

    /**
     * @param LanguageLine $translation
     * @param $locale
     * @return array|Translator|string|null
     */
    private function getCurrentTransForTranslation(LanguageLine $translation, $locale)
    {
        return $translation->text[$locale] ?? $translation->key;
    }
}
