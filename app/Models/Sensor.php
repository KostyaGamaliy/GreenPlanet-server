<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = ['name', 'plant_id'];

    public function plant() {
        return $this->belongsTo(Plant::class);
    }

    public function sensorsDates() {
        return $this->hasMany(SensorsDate::class);
    }
}
