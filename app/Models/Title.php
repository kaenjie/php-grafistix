<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Title extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'poto_1',
        'poto_2',
        'deskripsi'
    ];

    /**
     * Accessor for poto_1
     *
     * @return Attribute
     */
    protected function poto_1(): Attribute
    {
        return Attribute::make(
            get: fn ($poto_1) => $poto_1 ? url('/storage/title/' . $poto_1) : null,
        );
    }

    /**
     * Accessor for poto_2
     *
     * @return Attribute
     */
    protected function poto_2(): Attribute
    {
        return Attribute::make(
            get: fn ($poto_2) => $poto_2 ? url('/storage/title/' . $poto_2) : null,
        );
    }

    /**
     * Define the one-to-many relationship with Photo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
