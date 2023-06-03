<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = ['name', 'image', 'location'];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function plants() {
        return $this->hasMany(Plant::class);
    }
}
