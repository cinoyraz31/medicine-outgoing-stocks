<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAclPermission extends Model
{
    use HasFactory, ActivityLog, SoftDeletes;

    protected $fillable = [
        'user_id',
        'acl_permission_id',
    ];
}
