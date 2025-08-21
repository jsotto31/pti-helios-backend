<?php

namespace App\Models\Dtr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacialLog extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'time', 'site', 'device_id', 'processed'];
}
