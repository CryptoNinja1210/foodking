<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Language;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $sliders = [
        'Slider One',
        'Slider Two',
        'Slider Three',
    ];

    public function run()
    {
        $envService = new EnvEditor();
        if($envService->getValue('DEMO')) {
            foreach ($this->sliders as $slider) {
                $sliderObject = Slider::create([
                    'title'       => $slider,
                    'description' => $slider,
                    'status'      => Status::ACTIVE,
                ]);
                if(file_exists(public_path('/images/seeder/slider/' . strtolower(str_replace(' ', '_', $slider)) . '.png'))) {
                    $sliderObject->addMedia(public_path('/images/seeder/slider/' . strtolower(str_replace(' ', '_', $slider)) . '.png'))->preservingOriginal()->toMediaCollection('slider');
                }
            }
        }
    }
}
