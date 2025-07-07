<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contaceus extends Model
{
    //
    protected $table = 'contactus';
    protected $fillable = [
        'username',
        'email',
        'phone',
        'message',
    ];
}
