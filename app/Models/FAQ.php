<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    // Menentukan nama tabel (opsional jika nama tabel sudah sesuai dengan konvensi)
    protected $table = 'faq';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = ['question', 'answer'];
}
