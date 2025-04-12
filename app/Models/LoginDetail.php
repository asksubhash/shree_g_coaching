<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Authentication;

class LoginDetail extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'ip_address', 'login_datetime', 'status', 'current_status', 'created_at', 'updated_at'];
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

    public function userAuth()
    {
        return $this->belongsTo(Authentication::class, 'user_id','user_id');
    }
}
