<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'age', 'course', 'faculty_id', 'telegramID', 'about', 'phone'];

    // Визначаємо зв'язок багато-до-багатьох
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interests_users', 'user_id', 'interest_id');
    }

    public function images()
    {
        return $this->hasMany(Images::class, 'user_id', 'telegramID');
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
