<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRead extends Model
{
    protected $table = 'users_read';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['user_id', 'name', 'email', 'role'];
    
    public $timestamps = false;
}
