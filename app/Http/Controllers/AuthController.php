<?php

namespace App\Http\Controllers;
use App\Helpers\ApiResponse;
use App\Models\AclPermission;
use App\Services\Auth\AclSelectedService;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $service = new BaseService($request->all());
        $apiResponse = new ApiResponse();
        $service->rules = [
            'email' => 'required|email|max:250',
            'password' => 'required|min:8'
        ];
        $service->ruleMessages = [
            'email.required' => 'email is required',
            'email.email' => 'email must formated [email]',
            'email.max' => 'email address max 250 character',
            'password.required' => 'password is required',
            'password.min' => 'password min 8 character',
        ];
        $service->run();

        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $apiResponse->error(['message' => 'Unauthorized'])->clientError(401);
        }
        return $apiResponse->data($this->respondWithToken($token))->success();
    }

    public function logout()
    {
        auth()->logout();
        $apiResponse = new ApiResponse();
        return $apiResponse->message('Successfully logged out')->success();
    }

    public function aclPermissionIndex(ApiResponse $apiResponse)
    {
        $aclPermissions = AclPermission::select("id", "code", "name")->get()->toArray();
        return $apiResponse->data($aclPermissions)->success();
    }

    public function aclPermissionSelected(Request $request, ApiResponse $apiResponse)
    {
        $service = new AclSelectedService($request->all());
        $service->run();

        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }
        return $apiResponse->success();
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
