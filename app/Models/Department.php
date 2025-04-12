<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Department extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $table = 'departments';

    protected $fillable = [
        'department_name',
        'created_by',
    ];
}
