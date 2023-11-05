<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;


class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        Settings::group('notification')->set([
            'notification_fcm_secret_key'          => $envService->getValue(
                'DEMO'
            ) ? 'AAAAxGHw9lE:APA91bHKj6OsrD6EhnG5p26oTiQkXvOxTZwZEfVuuuipyUSNM-a8NB_CugVwfvvaosOvWgFAhQJOLMvxtv7e3Sw8DYpaWKwJIN3kjyIPoNRAe541sBz3x7E6sXZkA-ebueqnQiqNtbdP' : '',
            'notification_fcm_public_vapid_key'    => $envService->getValue(
                'DEMO'
            ) ? 'BKAvKJbnB3QATdp8n1aUo_uhoNK3exVKLVzy7MP8VKydjjzthdlAWdlku6LQISxm4zA7dWoRACI9AHymf4V64kA' : '',
            'notification_fcm_api_key'             => $envService->getValue(
                'DEMO'
            ) ? 'AIzaSyDg1xBSwmHKV0usIKxTFL5a6fFTb4s3XVM' : '',
            'notification_fcm_auth_domain'         => $envService->getValue(
                'DEMO'
            ) ? 'foodking-inilabs.firebaseapp.com' : '',
            'notification_fcm_project_id'          => $envService->getValue('DEMO') ? 'foodking-inilabs' : '',
            'notification_fcm_storage_bucket'      => $envService->getValue(
                'DEMO'
            ) ? 'foodking-inilabs.appspot.com' : '',
            'notification_fcm_messaging_sender_id' => $envService->getValue('DEMO') ? '843456771665' : '',
            'notification_fcm_app_id'              => $envService->getValue(
                'DEMO'
            ) ? '1:843456771665:web:fb1e3115e9e17ee1582a70' : '',
            'notification_fcm_measurement_id'      => $envService->getValue('DEMO') ? 'G-GSJPS921XW' : '',
        ]);

        $envService->addData([
            'FCM_SECRET_KEY' => $envService->getValue(
                'DEMO'
            ) ? 'AAAAxGHw9lE:APA91bHKj6OsrD6EhnG5p26oTiQkXvOxTZwZEfVuuuipyUSNM-a8NB_CugVwfvvaosOvWgFAhQJOLMvxtv7e3Sw8DYpaWKwJIN3kjyIPoNRAe541sBz3x7E6sXZkA-ebueqnQiqNtbdP' : '',
        ]);
        Artisan::call('optimize:clear');
    }
}
