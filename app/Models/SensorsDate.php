<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorsDate extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = ['sensor_id', 'light_data', 'soil_moisture_data'];

    public function sensor() {
        return $this->belongsTo(Sensor::class);
    }
}
