<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, ActivityLog, SoftDeletes;

    protected $fillable = [
        'name',
        'kode_dpho',
        'clinic_id',
        'stock',
    ];
}
