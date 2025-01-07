<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class Order extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'address',
        'city',
        'payment_method',
        'has_paid',
        'order_date',
        'id_paket', // Foreign key to Package
    ];

    // Cast field has_paid menjadi boolean
    protected $casts = [
        'has_paid' => 'boolean',
    ];
    
    /**
     * Get the package that owns the order.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'id_paket');
    }
    
}
