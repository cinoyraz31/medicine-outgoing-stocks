<?php

$resp = [
    'meta' => [],
    'data' => []
];

$resp['meta']['pagination'] = [
    'total' => (int)$data['total'],
    'current' => (int)$data['current_page'],
    'perPage' => (int)$data['per_page'],
    'next' => $data['next_page_url'],
    'prev' => $data['prev_page_url']
];

if(count($data['data'])){
    foreach ($data['data'] AS $medicineOutgoing){
        $resp["data"][] = [
            "id" => $medicineOutgoing['id'],
            "medicineId" => $medicineOutgoing['medicine_id'],
            "batchNo" => $medicineOutgoing['batch_no'],
            "expDate" => $medicineOutgoing['exp_date'],
            "quantity" => $medicineOutgoing['quantity'],
            "date" => $medicineOutgoing['created_at'],
            "unit" => [
                "id" => $medicineOutgoing['unit_id'],
                "name" => $medicineOutgoing['unit_name'],
            ],
            "medicine" => [
                "id" => $medicineOutgoing['medicine_id'],
                "name" => $medicineOutgoing['medicine_name'],
                "kodeDpho" => $medicineOutgoing['medicine_kode_dpho'],
            ]
        ];
    }
}

$json = $resp;
return $json;
