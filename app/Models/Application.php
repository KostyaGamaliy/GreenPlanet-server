<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = ['sender_id', 'company_name', 'company_description', 'company_image', 'documents', 'location'];
}
