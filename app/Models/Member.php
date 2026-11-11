<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'name_assamese', 'mobile', 'whatsapp', 'email', 'role', 'father_name', 'father_name_assamese', 'grandfather_name', 'date_of_birth', 'address', 'occupation', 'photo'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    protected static function booted(): void
    {
        static::creating(function (Member $member) {
            if ($member->sl_no === null) {
                $member->sl_no = ((int) static::query()->max('sl_no')) + 1;
            }
        });
    }

    public function documents() { return $this->hasMany(MemberDocument::class); }
}
