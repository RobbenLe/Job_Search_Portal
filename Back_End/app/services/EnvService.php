<?php

namespace App\Services;

class EnvService
{
    // initialize env variables
    static function Init()
    {
        // normally these settings would be stored in an .env file

        // database
        $_ENV["DB_HOST"] = "mysql";
        $_ENV["DB_NAME"] = "job_portal";
        $_ENV["DB_USER"] = "root";
        $_ENV["DB_PASSWORD"] = "secret123";
        $_ENV["DB_CHARSET"] = "utf8mb4";
        // env flag
        $_ENV["ENV"] = "LOCAL";
        // jwt secret
        $_ENV["JWT_SECRET"] = "8RXVjZIyszZEZSyb6h2C6xdNnH3FD2eh";
    }
}
