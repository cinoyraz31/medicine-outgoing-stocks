<?php

namespace App\Services\Medicine;
use App\Models\MedicineOutgoing;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class OutgoingListService extends BaseService
{
    public $rules = [
        "page"  => 'nullable|numeric',
        "perPage" => 'nullable|numeric',
        "fromDate" => 'nullable|date_format:Y-m-d',
        "toDate" => 'nullable|date_format:Y-m-d',
        "isDpho" => "nullable|boolean"
    ];

    public $ruleMessages = [
        "page.numeric"  => "page should be numeric",
        "perPage.numeric" => "perPage should be numeric",
        "fromDate.date_format" => "fromDate must data type date `Y-m-d`",
        "toDate.date_format" => "toDate must data type date `Y-m-d`",
        "isDpho.boolean" => "isDpho must data type boolean",
    ];

    public function constructAdapter()
    {
        $this->request['perPage'] = is_numeric($this->request['perPage']) ? $this->request['perPage'] : 15;
        $this->request['fromDate'] = !empty($this->request['fromDate']) ? $this->request['fromDate'] : '0001-01-01';
        $this->request['toDate'] = !empty($this->request['toDate']) ? $this->request['toDate'] : '9999-12-31';
    }

    public function execute()
    {
        $search = !empty($this->request["search"]) ? $this->request["search"] : null;
        $fromDate = !empty($this->request["fromDate"]) ? $this->request["fromDate"] : null;
        $toDate = !empty($this->request["toDate"]) ? $this->request["toDate"] : null;

        $query = MedicineOutgoing::select(
            'medicine_outgoings.*',
            'units.name AS unit_name',
            'medicines.name AS medicine_name',
            'medicines.kode_dpho AS medicine_kode_dpho'
        )
            ->join('medicines', 'medicines.id', '=', 'medicine_outgoings.medicine_id')
            ->join('units', 'units.id', '=', 'medicine_outgoings.unit_id')
            ->where("medicine_outgoings.clinic_id", auth()->user()['clinic_id'])
            ->whereBetween(DB::raw('DATE(medicine_outgoings.created_at)'), [$fromDate, $toDate])
            ->when($search, function($query, $search){
                $query->where('medicines.name', 'like', '%' . $search . '%');
            });

        if(isset($this->request['isDpho'])){
            if(empty($this->request['isDpho'])){
                $query = $query->whereNull('medicines.kode_dpho');
            } else {
                $query = $query->whereNotNull('medicines.kode_dpho');
            }
        }
        $this->data = $query->orderBy("medicine_outgoings.created_at", "DESC")
            ->paginate($this->request['perPage'])
            ->toArray();
    }
}
