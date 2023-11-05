<?php

namespace Database\Seeders;


use App\Enums\Status;
use App\Models\Language;
use Illuminate\Database\Seeder;


class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $englishLanguageArray = [
            'name'       => 'English',
            'code'       => 'en',
            'status'     => Status::ACTIVE
        ];

        $banglaLanguageArray = [
            'name'       => 'Bangla',
            'code'       => 'bn',
            'status'     => Status::ACTIVE
        ];

        $germanLanguageArray = [
            'name'       => 'German',
            'code'       => 'de',
            'status'     => Status::ACTIVE
        ];

        $englishLanguage = Language::create($englishLanguageArray);
        if(file_exists(public_path('/images/language/english.png'))) {
            $englishLanguage->addMedia(public_path('/images/language/english.png'))->preservingOriginal()->toMediaCollection('language');
        }

        $banglaLanguage = Language::create($banglaLanguageArray);
        if(file_exists(public_path('/images/language/bangla.png'))) {
            $banglaLanguage->addMedia(public_path('/images/language/bangla.png'))->preservingOriginal()->toMediaCollection('language');
        }

        $germanLanguage = Language::create($germanLanguageArray);
        if(file_exists(public_path('/images/language/german.png'))) {
            $germanLanguage->addMedia(public_path('/images/language/german.png'))->preservingOriginal()->toMediaCollection('language');
        }

    }
}
