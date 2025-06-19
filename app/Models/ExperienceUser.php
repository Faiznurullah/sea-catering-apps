<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ExperienceUser extends Model
{
    use HasFactory;

    protected $table = 'experience_users';
    protected $fillable = [
        'user_id',
        'name',
        'review',
        'star',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
