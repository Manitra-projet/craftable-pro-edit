<?php

namespace CustomPackages\CustomApp\Media;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\InteractsWithMedia as ParentHasMediaTrait;

trait InteractsWithMedia
{
    use ParentHasMediaTrait;

    /**
     * Register new Media Collection
     *
     * Adds new collection to model and set its name.
     *
     * @param $name
     *
     * @return MediaCollection
     */
    public function addMediaCollection($name): MediaCollection
    {
        $mediaCollection = MediaCollection::create($name);

        $this->mediaCollections[] = $mediaCollection;

        return $mediaCollection;
    }

    /**
     * Get the user's media details.
     *
     * @return Attribute
     */
    protected function mediaDetails(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getRegisteredMediaCollections()->keyBy('name')->map(function ($mediaCollection) {
                return [
                    'accept' => $mediaCollection->acceptsMimeTypes,
                    'max_number_of_files' => $mediaCollection->collectionSizeLimit,
                    'max_file_size' => $mediaCollection->maxFileSize,
                ];
            })
        );
    }
}
