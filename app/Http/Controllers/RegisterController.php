<?php

namespace App\Http\Controllers;
use App\Helpers\ApiResponse;
use App\Services\Register\StoreService;
use Illuminate\Http\Request;
class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $service = new StoreService($request->all());
        $apiResponse = new ApiResponse();

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success create user"])->success();
    }
}
