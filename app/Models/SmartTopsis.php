<?php

namespace App\Models;

use App\Models\Criterion;
use App\Models\Alternative;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmartTopsis extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function alternative() {
        return $this->belongsTo(Alternative::class);
    }
}
