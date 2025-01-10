<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price'];

    public function options()
    {
        return $this->hasMany(OptionPackage::class, 'package_id ');
    }
}
