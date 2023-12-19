<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AclPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            "medicine-create" => "Medicine Create",
            "medicine-incoming-create" => "Medicine Incoming Create",
            "medicine-outgoing-create" => "Medicine Outgoing Create",
            "medicine-outgoing-index" => "Medicine Outgoing Index",
        ];

        foreach ($arr AS $key => $name){
            \App\Models\AclPermission::create(
                [
                    'name' => $name,
                    'code' => $key,
                ]
            );
        }
    }
}
