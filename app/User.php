<?php

namespace Laratter;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'first_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the dashboard link of the authenticated user.
     *
     * @return string
     */
    public function getDashboard()
    {
        if (auth()->id() == 1) {
            return route('admin.dashboard');
        }

        return route('user.dashboard');
    }

    /**
     * Generate the code for the user.
     *
     * @return string
     */
    public function generateCode()
    {
        $lastUser = $this->withTrashed()->latest()->limit(1)->first();

        if (! $lastUser) {
            return 'LTU-1';
        }

        $code = last(explode('-', $lastUser->code));

        return 'LTU-' . ++$code;
    }
}
