<?php

namespace App\Traits;

use App\Models\UserAclPermission;

trait CheckPermission
{
    private function checkACL(string $code = "")
    {
        $count = UserAclPermission::join("acl_permissions", "acl_permissions.id", "=", "user_acl_permissions.acl_permission_id")
            ->where("user_acl_permissions.user_id", auth()->id())
            ->where("acl_permissions.code", $code)
            ->count();

        return $count > 0 ? true : false;
    }
}
