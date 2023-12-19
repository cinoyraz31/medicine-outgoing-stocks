<?php

namespace App\Services\Clinic;
use App\Services\BaseService;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;
class StoreService extends BaseService
{
    public $rules = [
        'name' => 'required|string|max:250',
    ];

    public $ruleMessages = [
        'name.required' => 'name is required',
        'name.string' => 'name must string',
        'name.max' => 'name max 250 character',
    ];

    public function execute()
    {
        $clinic = new Clinic();
        $clinic->name = $this->request['name'];
        $clinic->save();
    }
}
