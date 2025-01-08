<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'judul',
        'deskripsi',
        'image',
    ];
}
