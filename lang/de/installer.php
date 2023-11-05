<?php


return [
    'title' => 'Food King Installer',
    'next'  => 'Nächster Schritt',

    'welcome' => [
        'templateTitle' => 'Willkommen',
        'title'         => 'Food King Installer',
        'message'       => 'Einfacher Installations- und Einrichtungsassistent.',
        'next'          => 'Anforderungen prüfen',
    ],

    'requirement' => [
        'templateTitle' => 'Schritt 1 | Serveranforderungen',
        'title'         => 'Serveranforderungen',
        'next'          => 'Berechtigungen prüfen',
        'version'       => 'version',
        'required'      => 'erforderlich'
    ],

    'permission' => [
        'templateTitle'       => 'Schritt 2 | Berechtigungen',
        'title'               => 'Berechtigungen',
        'next'                => 'Site-Setup',
        'permission_checking' => 'Berechtigungsprüfung'
    ],

    'site' => [
        'templateTitle' => 'Schritt 3 | Site-Setup',
        'title'         => 'Site-Setup',
        'next'          => 'Datenbank-Setup',
        'label'         => [
            'app_name' => 'App-Name',
            'app_url'  => 'App-URL',
        ]
    ],
    'database' => [
        'templateTitle' => 'Schritt 4 | Datenbank-Setup',
        'title'         => 'Datenbank-Setup',
        'next'          => 'Endgültige Einrichtung',
        'fail_message'  => 'Verbindung zur Datenbank konnte nicht hergestellt werden.',
        'label'         => [
            'database_connection' => 'Datenbankverbindung',
            'database_host'       => 'Datenbankhost',
            'database_port'       => 'Datenbankport',
            'database_name'       => 'Datenbankname',
            'database_username'   => 'Datenbankbenutzername',
            'database_password'   => 'Datenbankpasswort',
        ]
    ],
    'final' => [
        'templateTitle'   => 'Schritt 5 | Endgültige Einrichtung',
        'title'           => 'Endgültige Einrichtung',
        'success_message' => 'Anwendung wurde erfolgreich installiert.',
        'login_info'      => 'Anmeldeinformationen',
        'email'           => 'E-Mail',
        'password'        => 'Passwort',
        'email_info'      => 'admin@example.com',
        'password_info'   => '123456',
        'next'            => 'Fertig stellen',
    ],

    'installed' => [
        'success_log_message' => 'Food King-Installationsprogramm erfolgreich INSTALLIERT am ',
        'update_log_message'  => 'Food King Installer erfolgreich AKTUALISIERT am ',
    ],
];