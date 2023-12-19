<?php

namespace App\Http\Controllers;
use App\Helpers\ApiResponse;
use App\Services\Unit\StoreService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $service = new StoreService(['name' => $request->name]);
        $apiResponse = new ApiResponse();

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success create unit"])->success();
    }
}
