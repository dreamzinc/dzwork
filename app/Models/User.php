<?php

namespace App\Models;

use DzWork\Core\Model;

class User extends Model {
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    // Example of custom method
    public static function findByEmail($email) {
        return static::where('email', $email)->first();
    }
}
