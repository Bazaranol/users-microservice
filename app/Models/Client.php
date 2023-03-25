<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $tables = 'clients';

    protected $fillable = [
        'firstName',
        'lastName',
        'login',
        'password',
        'isBlocked'
    ];
}
