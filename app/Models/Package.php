<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Get the orders for the package.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_paket');
    }
}
