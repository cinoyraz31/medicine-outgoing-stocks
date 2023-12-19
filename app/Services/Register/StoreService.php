<?php

namespace App\Services\Register;
use App\Services\BaseService;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class StoreService extends BaseService
{
    public $rules = [
        'name' => 'required|string|max:250',
        'email' => 'required|email|max:250|unique:users',
        'password' => 'required|min:8',
        'clinicId' => 'required|numeric'
    ];

    public $ruleMessages = [
        'name.required' => 'name is required',
        'name.string' => 'name must string',
        'name.max' => 'name max 250 character',
        'email.required' => 'email address is required',
        'email.email' => 'email address must format email',
        'email.max' => 'email address max 250 character',
        'email.unique' => 'email address is registered',
        'password.required' => 'password is required',
        'password.min:8' => 'password min 8 character',
        'clinicId.required' => 'clinicId is required',
        'clinicId.numeric' => 'clinicId must numeric',
    ];

    public function execute()
    {
        if(! $this->customValidation()) return;

        $user = new User();
        $user->name = $this->request['name'];
        $user->email = $this->request['email'];
        $user->clinic_id = $this->request['clinicId'];
        $user->password = Hash::make($this->request['password']);
        $user->save();
    }

    private function customValidation()
    {
        if(! $this->checkClinicIsExist()) return ;

        return true;
    }

    private function checkClinicIsExist()
    {
        $clinic = Clinic::find($this->request["clinicId"]);

        if(empty($clinic)){
            $this->errorMessage = "Clinic not found";
            $this->httpCode = Response::HTTP_BAD_REQUEST;
            return false;
        }
        return true;
    }
}
