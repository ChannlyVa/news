<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contactus extends Model
{
    use HasFactory;
    protected $fillable = [
        'contactuse_name',
        'contactuse_description',
    ];
}
