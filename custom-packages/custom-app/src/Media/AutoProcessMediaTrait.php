<?php

namespace CustomPackages\CustomApp\Media;

trait AutoProcessMediaTrait
{
    /**
     * Setup to auto process during saving
     */
    public static function bootAutoProcessMediaTrait(): void
    {
        static::saving(static function ($model) {
            /** @var self $model */
            $model->processMedia(collect(request()->only($model->getRegisteredMediaCollections()->map->getName()->toArray())));
        });
    }
}
