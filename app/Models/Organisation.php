<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;
     protected $fillable = [
        'id', 'name', 'description'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'organisation_user');
    }
}
