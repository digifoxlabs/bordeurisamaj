<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDocument extends Model
{
    protected $fillable = ['title', 'description', 'original_name', 'path', 'mime_type', 'size'];
}
