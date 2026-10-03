<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'mobile', 'whatsapp', 'email', 'role', 'father_name', 'grandfather_name', 'date_of_birth', 'address', 'occupation', 'photo'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function documents() { return $this->hasMany(MemberDocument::class); }
}
