<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar'
    ];

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public static function getUserFromEmail ($email) {
        $users = self::where('email', $email)->get();
        if (count($users)) { return $users[0]; }

        return [];
    }

    public static function convertName($name)
    {
        if (strlen($name) <= 10) { return $name; }
        $str = '';
        for ($i = 0; $i < strlen($name); $i++) {
            if ($i < 10) { $str .= $name[$i]; }
            else {
                $str .= '...';
                break;
            }
        }

        return $str;
    }
}
