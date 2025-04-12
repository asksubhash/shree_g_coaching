<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenCode extends Model
{
    use HasFactory;
    protected $table = 'gen_codes';
    protected $guarded = [];
    public static function getGenCodeUsingGroup($groupName)
    {
        return GenCode::select('gen_codes.gen_code', 'gen_codes.description', 'gen_codes.serial_no', 'gcg.group_name')
            ->leftJoin('gen_code_groups as gcg', 'gcg.id', '=', 'gen_codes.gen_code_group_id')
            ->where('gen_codes.status', 1)
            ->where('gcg.group_name', $groupName)->orderBy('gen_codes.serial_no', 'ASC')->get();
    }
}
