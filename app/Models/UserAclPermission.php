<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserAclPermission extends Model
{
    use HasFactory;
    use ActivityLog;

    protected $fillable = [
        'user_id',
        'acl_permission_id',
    ];
}
