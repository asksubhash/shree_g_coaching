<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Designation extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $table = 'designations';

    // public function user()
    // {
    //     return $this->hasOne(User::class, 'designation', 'code');
    // }
}
