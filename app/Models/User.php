<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
{
    protected $table = 'user';

    public function getJWTIdentifier()
    {
        auth()->attempt();
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}