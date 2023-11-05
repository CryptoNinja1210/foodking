<?php


return [
    'title' => 'Food King ইনস্টলার',
    'next'  => 'পরবর্তী ধাপ',

    'welcome' => [
        'templateTitle' => 'স্বাগত',
        'title'         => 'Food King ইনস্টলার',
        'message'       => 'সহজ ইনস্টলেশন এবং সেটআপ উইজার্ড।',
        'next'          => 'প্রয়োজনীয়তা পরীক্ষা করুন',
    ],

    'requirement' => [
        'templateTitle' => 'ধাপ 1 | সার্ভারের প্রয়োজনীয়তা',
        'title'         => 'সার্ভারের প্রয়োজনীয়তা',
        'next'          => 'অনুমতি পরীক্ষা করুন',
        'version'       => 'সংস্করণ',
        'required'      => 'প্রয়োজনীয়'
    ],

    'permission' => [
        'templateTitle'       => 'ধাপ 2 | অনুমতি',
        'title'               => 'অনুমতি',
        'next'                => 'সাইট সেটআপ',
        'permission_checking' => 'অনুমতি পরীক্ষা করা হচ্ছে'
    ],

    'site' => [
        'templateTitle' => 'ধাপ 3 | সাইট সেটআপ',
        'title'         => 'সাইট সেটআপ',
        'next'          => 'ডাটাবেস সেটআপ',
        'label'         => [
            'app_name' => 'অ্যাপের নাম',
            'app_url'  => 'অ্যাপ ইউআরএল',
        ]
    ],
    'database' => [
        'templateTitle' => 'ধাপ 4 | ডাটাবেস সেটআপ',
        'title'         => 'ডাটাবেস সেটআপ',
        'next'          => 'চূড়ান্ত সেটআপ',
        'fail_message'  => 'ডাটাবেসের সাথে সংযোগ করা যায়নি।',
        'label'         => [
            'database_connection' => 'ডাটাবেস সংযোগ',
            'database_host'       => 'ডাটাবেস হোস্ট',
            'database_port'       => 'ডাটাবেস পোর্ট',
            'database_name'       => 'ডাটাবেসের নাম',
            'database_username'   => 'ডাটাবেস ব্যবহারকারীর নাম',
            'database_password'   => 'ডাটাবেস পাসওয়ার্ড',
        ]
    ],
    'final' => [
        'templateTitle'   => 'ধাপ 5 | চূড়ান্ত সেটআপ',
        'title'           => 'চূড়ান্ত সেটআপ',
        'success_message' => 'অ্যাপ্লিকেশনটি সফলভাবে ইনস্টল করা হয়েছে।',
        'login_info'      => 'লগইন তথ্য',
        'email'           => 'ইমেইল',
        'password'        => 'পাসওয়ার্ড',
        'email_info'      => 'admin@example.com',
        'password_info'   => '123456',
        'next'            => 'শেষ',
    ],

    'installed' => [
        'success_log_message' => 'Food King ইনস্টলার সফলভাবে ইনস্টল করা হয়েছে',
        'update_log_message'  => 'Food King ইনস্টলার সফলভাবে আপডেট হয়েছে',
    ],
];