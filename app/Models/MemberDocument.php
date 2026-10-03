<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    protected $fillable = ['title', 'original_name', 'path'];
    public function member() { return $this->belongsTo(Member::class); }
}
