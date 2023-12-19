<?php

namespace App\Services\Medicine;
use App\Models\Medicine;
use App\Models\MedicineBatchCalculateResult;
use App\Models\MedicineIncoming;
use App\Services\BaseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class IncomeStoreService extends BaseService
{
    private $medicine = null;
    private $medicineIncome = null;
    public $rules = [
        'medicineId' => 'required|numeric',
        'quantity' => 'required|numeric|min:1',
        'batchNo' => 'required|string|unique:medicine_incomings,batch_no',
        'expDate' => 'required|date_format:Y-m-d'
    ];

    public $ruleMessages = [
        'medicineId.required' => 'medicineId is required',
        'medicineId.numeric' => 'medicineId must numeric',
        'quantity.required' => 'quantity is required',
        'quantity.numeric' => 'quantity must numeric',
        'quantity.min' => 'quantity min 1',
        'batchNo.required' => 'batchNo is required',
        'batchNo.string' => 'batchNo must string',
        'batchNo.unique' => 'batchNo is exist',
        'expDate.required' => 'expDate is required',
        'expDate.date_format' => 'expDate must formated Y-m-d',
    ];

    public function execute()
    {
        if(! $this->customValidation()) return;

        try {
            DB::beginTransaction();
            $this->saveMedicineIncoming();
            $this->saveMedicineBatchCalculateResult();

            $this->medicine->stock += $this->medicineIncome->quantity;
            $this->medicine->save();

            DB::commit();
        }
        catch(Exception $e) {
            DB::rollback();
            $this->errorMessage = "Failed to save transaction";
            return false;
        }
    }

    private function saveMedicineBatchCalculateResult()
    {
        $batchCalculateResult = new MedicineBatchCalculateResult();
        $batchCalculateResult->medicine_id = $this->medicineIncome->medicine_id;
        $batchCalculateResult->clinic_id = $this->medicineIncome->clinic_id;
        $batchCalculateResult->stock = $this->medicineIncome->quantity;
        $batchCalculateResult->batch_no = $this->medicineIncome->batch_no;
        $batchCalculateResult->save();
    }

    private function saveMedicineIncoming()
    {
        $this->medicineIncome = new MedicineIncoming();
        $this->medicineIncome->medicine_id = $this->request['medicineId'];
        $this->medicineIncome->quantity = $this->request['quantity'];
        $this->medicineIncome->batch_no = $this->request['batchNo'];
        $this->medicineIncome->exp_date = $this->request['expDate'];
        $this->medicineIncome->clinic_id = auth()->user()['clinic_id'];
        $this->medicineIncome->save();
    }

    private function customValidation()
    {
        if(! $this->checkMedicineIsExist()) return ;

        return true;
    }

    private function checkMedicineIsExist()
    {
        $this->medicine = Medicine::where("id", $this->request['medicineId'])->lockForUpdate()->first();

        if(empty($this->medicine)){
            $this->errorMessage = "Medicine not found";
            $this->httpCode = Response::HTTP_BAD_REQUEST;
            return false;
        }

        return true;
    }
}
