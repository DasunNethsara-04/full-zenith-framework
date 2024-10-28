<?php

namespace ZenithPHP\App\Models;

use ZenithPHP\Core\Model\Model;

class User extends Model
{
    protected string $table_name = 'users';

    public static function verifyCredentials(string $username, string $password): int|bool
    {
        // TODO: Implement verifyCredentials() method.
        return 1;
    }
}
