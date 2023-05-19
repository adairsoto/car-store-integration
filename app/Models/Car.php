<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['model', 'make', 'price', 'manuf_year', 'model_year', 'mileage', 'city' , 'description'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
