<?php

namespace CustomPackages\CustomApp\Translations\Repositories;

use CustomPackages\CustomApp\Translations\LanguageLine;

class LanguageLineRepository
{
    /**
     * @param $group
     * @param $key
     */
    public function createLanguageLineIfDoesntExist($group, $key, $language = null, $text = null): LanguageLine | null
    {
        if (empty(trim($key))) {
            return null;
        }

        /** @var LanguageLine $translation */
        $languageLine = LanguageLine::withTrashed()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        // because Laravel & MySQL are case-insensitive by default, let's double check we have the right $languageLine
        if ($languageLine && $languageLine->key === $key) {
            $languageLine->restore();

            if ($language && $text) {
                $languageLine->text = array_merge([$language => $text], $languageLine->text);
                $languageLine->save();
            }
        } else {
            $languageLine = LanguageLine::make([
                'group' => $group,
                'key' => $key,
                'text' => ($text && $language) ? [$language => $text] : [],
            ]);

            $languageLine->save();
        }

        return $languageLine;
    }

    public function getGroups()
    {
        return LanguageLine::query()
            ->groupBy('group')
            ->pluck('group');
    }

    public function deleteLanguageLines()
    {
        LanguageLine::query()->delete();
    }
}
