<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanTimenotavailable extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturers_id');
    }

    public function day()
    {
        return $this->belongsTo(Day::class, 'days_id');
    }

    public function time()
    {
        return $this->belongsTo(Time::class, 'times_id');
    }
}
