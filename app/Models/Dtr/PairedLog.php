<?php

namespace App\Models\Dtr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairedLog extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'time_in', 'time_out', 'date'];
}
