<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criterion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function combine() {
        return $this->hasMany(Combine::class);
    }

    public function getRouteKeyName() {
        return 'id';
    }
}
