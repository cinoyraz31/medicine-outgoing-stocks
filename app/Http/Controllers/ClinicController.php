<?php

namespace App\Http\Controllers;
use App\Helpers\ApiResponse;
use App\Services\Clinic\StoreService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function store(Request $request)
    {
        $service = new StoreService(['name' => $request->name]);
        $apiResponse = new ApiResponse();

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success create clinic"])->success();
    }
}
