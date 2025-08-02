<?php

namespace App\Models;

use App\Models\Friend;
use App\Models\Message;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withPivot('status')
            ->wherePivot('status', 'accepted');
    }

    public function hasPendingFriendRequests()
    {
        return $this->friendRequests()->exists();
    }

    public function friendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')
            ->where('status', 'pending');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->where('read', false);
    }

    public function isOnline()
    {
        // Defina o tempo limite para considerar o usuário como online (por exemplo, 5 minutos)
        $onlineThreshold = now()->subMinutes(5);

        // Verifique se a última atividade do usuário está dentro do limite de tempo
        return $this->last_online_at >= $onlineThreshold;
    }

    use HasFactory;

    // Outras propriedades e métodos do modelo...

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
