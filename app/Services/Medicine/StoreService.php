<?php

namespace App\Services\Medicine;
use App\Models\Medicine;
use App\Services\BaseService;

class StoreService extends BaseService
{
    public $rules = [
        'name' => 'required|string|max:250',
        'kodeDpho' => 'nullable|string|max:16'
    ];

    public $ruleMessages = [
        'name.required' => 'name is required',
        'name.string' => 'name must string',
        'name.max' => 'name max 250 character',
        'kodeDpho.string' => 'kode_dpho must string',
        'kodeDpho.max' => 'kode_dpho max 16 character',
    ];

    public function execute()
    {
        $medicine = new Medicine();
        $medicine->name = $this->request['name'];
        $medicine->kode_dpho = $this->request['kodeDpho'];
        $medicine->clinic_id = auth()->user()['clinic_id'];
        $medicine->stock = 0;
        $medicine->save();
    }
}
