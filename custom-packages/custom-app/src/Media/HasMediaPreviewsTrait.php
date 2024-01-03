<?php

namespace CustomPackages\CustomApp\Media;

use Spatie\MediaLibrary\Conversions\ConversionCollection;

/**
 * @property-read bool $autoProcessMedia
 */
trait HasMediaPreviewsTrait
{
    /**
     * @param string $mediaCollectionName
     * @return mixed
     */
    public function getThumbs200ForCollection(string $mediaCollectionName)
    {
        $mediaCollection = $this->getMediaCollection($mediaCollectionName);

        return $this->getMedia($mediaCollectionName)->filter(static function ($medium) use ($mediaCollectionName, $mediaCollection) {
            //We also want all files (PDF, Word, Excell etc.)
            if (! $mediaCollection->isImage()) {
                return true;
            }

            return ConversionCollection::createForMedia($medium)->filter(static function ($conversion) use ($mediaCollectionName) {
                return $conversion->shouldBePerformedOn($mediaCollectionName);
            })->filter(static function ($conversion) {
                return $conversion->getName() === 'preview';
            })->count() > 0;
        })->map(static function ($medium) use ($mediaCollection) {
            return [
                'id' => $medium->id,
                'url' => $medium->getUrl(),
                'thumb_url' => $mediaCollection->isImage() ? $medium->getUrl('preview') : $medium->getUrl(),
                'type' => $medium->mime_type,
                'mediaCollection' => $mediaCollection->getName(),
                'name' => $medium->hasCustomProperty('name') ? $medium->getCustomProperty('name') : $medium->file_name,
                'size' => $medium->size,
            ];
        });
    }

    /**
     * Register preview with the default size of 200x200 for all media collections
     */
    public function autoRegisterPreviews($size = 200): void
    {
        $this->addMediaConversion('preview')
            ->width($size)
            ->height($size)
            ->fit('crop', $size, $size)
            ->optimize()
            ->nonQueued()
            ->performOnCollections(...$this->getRegisteredMediaCollections()->map->getName()->toArray());
    }
}
