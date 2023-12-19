<?php

namespace App\Services\Unit;
use App\Services\BaseService;
use App\Models\Unit;
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
        $unit = new Unit();
        $unit->name = $this->request['name'];
        $unit->save();
    }
}
