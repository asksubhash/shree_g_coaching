<?php

namespace App\Models;

use App\Models\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Category extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $table = 'categories';
    protected $guarded = [];

    public static function getDepartmentBasedCategories($departmentIds)
    {
        return Category::whereIn('department_id', $departmentIds)->where('record_status', 1)->get();
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class, 'id', 'category_id');
    }
}
