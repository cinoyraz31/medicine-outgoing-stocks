<?php

namespace App\Http\Controllers;
use App\Helpers\ApiResponse;
use App\Services\Medicine\OutgoingStoreService;
use App\Services\Medicine\OutgoingListService;
use App\Services\Medicine\IncomeStoreService;
use App\Services\Medicine\StoreService;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;

class MedicineController extends Controller
{
    use CheckPermission;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function outgoingList(Request $request, ApiResponse $apiResponse)
    {
        if(! $this->checkACL("medicine-outgoing-index")){
            return $apiResponse->error("Forbidden")->clientError(403);
        }

        $service = new OutgoingListService([
            "search" => $request->search,
            "perPage" => $request->perPage,
            "page" => $request->page,
            "isDpho" => $request->isDpho,
            "fromDate" => $request->fromDate,
            "toDate" => $request->toDate,
        ]);

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->json('MedicineOutgoingIndex', $service->data);;
    }

    public function store(Request $request, ApiResponse $apiResponse)
    {
        if(! $this->checkACL("medicine-create")){
            return $apiResponse->error("Forbidden")->clientError(403);
        }

        $service = new StoreService($request->all());

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success create medicine"])->success();
    }

    public function incoming(Request $request)
    {
        if(! $this->checkACL("medicine-incoming-create")){
            return $apiResponse->error("Forbidden")->clientError(403);
        }

        $service = new IncomeStoreService($request->all());
        $apiResponse = new ApiResponse();

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success added medicine stock income"])->success();
    }

    public function outgoing(Request $request)
    {
        if(! $this->checkACL("medicine-outgoing-create")){
            return $apiResponse->error("Forbidden")->clientError(403);
        }

        $service = new OutgoingStoreService($request->all());
        $apiResponse = new ApiResponse();

        $service->run();
        if ($service->isError()) {
            return $apiResponse->error($service->errorMessage)->clientError($service->httpCode);
        }

        return $apiResponse->meta(["message" => "Success added medicine outgoing"])->success();
    }
}
