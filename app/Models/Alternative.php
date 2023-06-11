<?php

namespace App\Models;

use App\Models\Combine;
use App\Models\SmartTopsis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alternative extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function combine() {
        return $this->hasMany(Combine::class);
    }

    public function smartTopsis() {
        return $this->hasOne(SmartTopsis::class);
    }

    public function getRouteKeyName() {
        return 'id';
    }
}
