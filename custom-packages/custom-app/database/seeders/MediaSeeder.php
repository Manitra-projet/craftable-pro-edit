<?php

namespace Database\Seeders;

use CustomPackages\CustomApp\Models\UnassignedMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('local')->put('file.txt', 'Hello World');
        UnassignedMedia::create()
            ->addMedia(Storage::disk('local')->path('file.txt'))
            ->withCustomProperties([
                "name" => "An important text file",
                "extension" => "txt",
                "size" => rand(10, 100) * 1024,
                "alt" => "An important text file",
            ])
            ->toMediaCollection('default');

        collect(range(1, 10))->each(function () {
            $fullName = fake()->firstName . ' ' . fake()->lastName;
            UnassignedMedia::create()
                ->addMediaFromUrl("https://i.pravatar.cc/800")
                ->withCustomProperties([
                    "name" => $fullName,
                    "extension" => "jpg",
                    "size" => rand(10, 100) * 1024,
                    "alt" => $fullName,
                ])
                ->toMediaCollection('default');
        });
    }
}
