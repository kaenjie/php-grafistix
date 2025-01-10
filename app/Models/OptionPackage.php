<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'name',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'package_id');
    }
}
