<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = ['name', 'image', 'watering_time', 'company_id', 'sensor_id', 'description'];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function sensor() {
        return $this->hasOne(Sensor::class);
    }
}
