<?php

namespace App\Services\Auth;

use App\Models\AclPermission;
use App\Models\UserAclPermission;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AclSelectedService extends BaseService
{
    private $aclPermissions = [];
    public $rules = [
        'aclPermissions' => 'required|array|min:1',
        'aclPermissions.*' => 'required|string|min:1',
    ];

    public $ruleMessages = [
        'aclPermissions.required' => 'aclPermissions is required',
        'aclPermissions.array' => 'aclPermissions must data type array',
        'aclPermissions.min' => 'aclPermissions min 1 index array',
        'aclPermissions.*.required' => 'array is required',
        'aclPermissions.*.string' => 'must array string',
        'aclPermissions.*.min' => 'array must min 1 index',
    ];

    public function execute()
    {
        if(! $this->customValidation()) return;

        try {
            DB::beginTransaction();
            $userId = auth()->id();

            UserAclPermission::where("user_id", $userId)->delete();

            foreach ($this->aclPermissions as $aclPermission){
                UserAclPermission::create([
                    'user_id' => $userId,
                    'acl_permission_id' => $aclPermission->id
                ]);
            }
            DB::commit();
        }
        catch(Exception $e) {
            DB::rollback();
            $this->errorMessage = "Failed to save transaction";
            return false;
        }
    }

    private function customValidation()
    {
        if(! $this->checkAclExist()) return ;

        return true;
    }

    private function checkAclExist()
    {
        $query = AclPermission::whereIn("code", $this->request["aclPermissions"]);
        $count = $query->count();

        if($count != count($this->request["aclPermissions"])){
            $this->errorMessage = "there is a missing acl code string";
            return false;
        }

        $this->aclPermissions = $query->get();
        return true;
    }
}
