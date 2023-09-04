<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $guard = "student";

    protected $fillable = [
        'section_id',
        'class_id',
        'name',
        'email',
        'address',
        'phone_number',
    ];

    public function class ()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
