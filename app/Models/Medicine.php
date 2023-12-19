<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    use ActivityLog;

    protected $fillable = [
        'name',
        'kode_dpho',
        'clinic_id',
        'stock',
    ];
}
