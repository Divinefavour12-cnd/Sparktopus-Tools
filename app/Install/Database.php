<?php

namespace App\Install;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class Database
{
    public function setup($data)
    {
        $this->checkDatabaseConnection($data);
        $this->setEnvVariables($data);
        $this->migrateAndSeedDatabase();
    }

    private function checkDatabaseConnection($data)
    {
        $this->setupDatabaseConnectionConfig($data);

        DB::connection('pgsql')->reconnect();
        DB::connection('pgsql')->getPdo();
    }

    private function setupDatabaseConnectionConfig($data)
    {
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => $data['host'],
            'database.connections.pgsql.port' => $data['port'],
            'database.connections.pgsql.database' => $data['database'],
            'database.connections.pgsql.username' => $data['username'],
            'database.connections.pgsql.password' => $data['password'],
        ]);
    }

    private function setEnvVariables($data)
    {
        $env = DotenvEditor::load();

        //database credentials
        $env->setKey('DB_CONNECTION', 'pgsql');
        $env->setKey('DB_HOST', $data['host']);
        $env->setKey('DB_PORT', $data['port']);
        $env->setKey('DB_DATABASE', $data['database']);
        $env->setKey('DB_USERNAME', $data['username']);
        $env->setKey('DB_PASSWORD', $data['password']);

        $env->save();
    }

    private function migrateAndSeedDatabase()
    {
        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
    }
}
