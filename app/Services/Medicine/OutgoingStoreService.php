<?php

namespace App\Services\Medicine;
use App\Models\Medicine;
use App\Models\MedicineBatchCalculateResult;
use App\Models\MedicineOutgoing;
use App\Models\Unit;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OutgoingStoreService extends BaseService
{
    private $medicine;
    private $unit;
    private $medicalBatchCalculateResults;
    public $rules = [
        'medicineId' => 'required|numeric',
        'unitId' => 'required|numeric',
        'quantity' => 'required|numeric|min:1'
    ];

    public $ruleMessages = [
        'medicineId.required' => 'medicineId is required',
        'medicineId.numeric' => 'medicineId must numeric',
        'unitId.required' => 'unitId is required',
        'unitId.numeric' => 'unitId must numeric',
        'quantity.required' => 'quantity is required',
        'quantity.numeric' => 'quantity must numeric',
        'quantity.min' => 'quantity min 1',
    ];

    public function execute()
    {
        if(! $this->customValidation()) return;

        if(!empty($this->medicalBatchCalculateResults)){
            try {
                DB::beginTransaction();
                $quantity = $this->request['quantity'];

                foreach ($this->medicalBatchCalculateResults AS $medicalBatchCalculateResult){

                    if($medicalBatchCalculateResult->stock > $quantity){
                        $outgoingQ = $quantity;
                        $medicalBatchCalculateResult->stock -= $quantity;
                    } else {
                        $outgoingQ = $medicalBatchCalculateResult->stock;
                        $medicalBatchCalculateResult->stock = 0;
                    }
                    $quantity -= $outgoingQ;

                    $medicineOutgoing = new MedicineOutgoing();
                    $medicineOutgoing->clinic_id = $medicalBatchCalculateResult->clinic_id;
                    $medicineOutgoing->medicine_id = $medicalBatchCalculateResult->medicine_id;
                    $medicineOutgoing->unit_id = $this->unit->id;
                    $medicineOutgoing->quantity = $outgoingQ;
                    $medicineOutgoing->batch_no = $medicalBatchCalculateResult->batch_no;
                    $medicineOutgoing->notes = "lorem ipsum";
                    $medicineOutgoing->exp_date = $medicalBatchCalculateResult->exp_date;
                    $medicineOutgoing->save();

                    $medicalBatchCalculateResult->save();

                    if($quantity <= 0){
                        break;
                    }
                }
                $this->medicine->stock -= $this->request["quantity"];
                $this->medicine->save();
                DB::commit();
                return false;
            }
            catch(Exception $e) {
                DB::rollback();
                $this->errorMessage = "Failed to save transaction";
                $this->httpCode = Response::HTTP_BAD_REQUEST;
                return false;
            }
        }
        $this->errorMessage = "Batch not found";
        $this->httpCode = Response::HTTP_BAD_REQUEST;
        return false;
    }

    private function customValidation()
    {
        if(! $this->checkMedicineIsExist()) return ;
        if(! $this->checkUnitIsExist()) return ;
        if(! $this->isThereStock()) return ;

        return true;
    }

    private function isThereStock()
    {
        $query = MedicineBatchCalculateResult::select(
            'medicine_batch_calculate_results.*',
            'medicine_incomings.exp_date AS exp_date'
        )
            ->where('medicine_batch_calculate_results.medicine_id', $this->request['medicineId'])
            ->join('medicine_incomings', 'medicine_incomings.batch_no', '=', 'medicine_batch_calculate_results.batch_no')
            ->where("medicine_batch_calculate_results.clinic_id", auth()->user()['clinic_id'])
            ->where("medicine_batch_calculate_results.stock", '>', 0);

        if($query->sum('stock') < $this->request["quantity"]){
            $this->errorMessage = "Medicine stock not enough";
            $this->httpCode = Response::HTTP_BAD_REQUEST;
            return false;
        }
        $this->medicalBatchCalculateResults = $query->lockForUpdate()
            ->orderBy("created_at", "ASC")
            ->get();
        return true;
    }

    private function checkMedicineIsExist()
    {
        $this->medicine = Medicine::where("id", $this->request['medicineId'])
            ->lockForUpdate()->first();

        if(empty($this->medicine)){
            $this->errorMessage = "Medicine not found";
            $this->httpCode = Response::HTTP_BAD_REQUEST;
            return false;
        }

        return true;
    }

    private function checkUnitIsExist()
    {
        $this->unit = Unit::find($this->request['unitId']);

        if(empty($this->unit)){
            $this->errorMessage = "Unit not found";
            $this->httpCode = Response::HTTP_BAD_REQUEST;
            return false;
        }

        return true;
    }
}
