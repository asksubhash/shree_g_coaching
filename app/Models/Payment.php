<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getPaymentIdUsingStudentAppNo($applicationNumber)
    {
        $query = Payment::select('*');
        $query->where('student_application_no', $applicationNumber);
        $query->orderBy('id', 'DESC');

        $data = $query->first();
        if ($data) {
            return $data->id;
        } else {
            return false;
        }
    }

    public static function getStudentPaymentData($id)
    {
        $query = Payment::select('*');
        $query->where('id', $id);

        return $query->first();
    }
}
