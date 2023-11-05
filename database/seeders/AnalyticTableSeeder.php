<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Analytic;
use App\Models\AnalyticSection;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class AnalyticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Analytic::create([
                'name'   => 'Google',
                'status' => Status::ACTIVE,
            ]);

            AnalyticSection::create([
                'analytic_id' => 1,
                'name'        => 'GA4',
                'data'        => "<!-- Google tag (gtag.js) -->
                        <script async src=\"https://www.googletagmanager.com/gtag/js?id=G-2FC340CGN0\"></script>
                        <script>
                          window.dataLayer = window.dataLayer || [];
                          function gtag(){dataLayer.push(arguments);}
                          gtag('js', new Date());

                          gtag('config', 'G-2FC340CGN0');
                        </script>",
                'section'     => \App\Enums\AnalyticSection::HEAD
            ]);
        }
    }
}
