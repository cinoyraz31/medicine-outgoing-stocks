<?php

namespace App\Traits;

use App\Models\Log;

trait ActivityLog
{
    private $logs = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if(auth()->user()){
                $model->activityLog("Create", $model);
            }
        });

        static::updated(function ($model) {
            if(auth()->user()){
                $model->activityLog("Update", $model);
            }
        });

        static::deleted(function ($model) {
            if(auth()->user()){
                $model->activityLog("Delete", $model);
            }
        });
    }

    private function activityLog($action, $model)
    {
        $user = auth()->user();
        $userEmail = $user['email'];
        $namespaces = explode('\\', get_class($model));
        $getOriginal = $model->getOriginal();
        $getAttributes = $model->getAttributes();
        $id = $getAttributes[$model->primaryKey];
        $module = array_pop($namespaces);

        switch($action){
            case "Create":
                $model->saveActivityLog($action, $userEmail, $id, $module, $model->messageCreated($module, $getAttributes), $getAttributes, "");
                break;

            case "Update":
                $model->saveActivityLog($action, $userEmail, $id, $module, $model->messageUpdated($getAttributes, $getOriginal), $getAttributes, $getOriginal);
                break;

            case "Delete":
                $model->saveActivityLog($action, $userEmail, $id, $module, $model->messageDeleted($module), "", $getOriginal);
                break;
        }
    }

    private function saveActivityLog($action, $email, $id, $moduleName, $message, $newData, $oldData)
    {
        if(!empty($message)){
            $log = new Log;
            $log->method_action = $action;
            $log->email = $email;
            $log->module_id = $id;
            $log->module_name = $moduleName;
            $log->description = $message;
            $log->old_data = json_encode($oldData);
            $log->new_data = json_encode($newData);
            $log->save();
        }
    }

    private function messageCreated($modelName, $getAttributes=[])
    {
        if(count($getAttributes) > 0){
            foreach($getAttributes as $key => $value){
                $this->logs[] = [
                    "field" => $key,
                    "oldValue" => "",
                    "newValue" => $getAttributes[$key]
                ];
            }
        }
        return "Membuat ".strtolower($modelName)." baru";
    }

    private function messageUpdated($getAttributes = [], $getOriginal = [])
    {
        $result = [];
        if(count($getAttributes) > 0 && count($getOriginal) > 0){
            foreach($getAttributes as $key => $value){
                if($getAttributes[$key] != $getOriginal[$key]){
                    $this->logs[] = [
                        "field" => $key,
                        "oldValue" => $getOriginal[$key],
                        "newValue" => $getAttributes[$key]
                    ];
                    if (!in_array($key, ["updated_at", "created_at"])) {
                        $key = strtolower(str_replace("_", " ",$key));
                        $value = $value ?? "empty";

                        $result[] = "$key ke $value";
                    }
                }
            }

            if(count($result) > 0){
                return "Merubah ". implode(", ",$result);
            }
        }
        return null;
    }

    private function messageDeleted($modelName)
    {
        return "Menghapus ".strtolower($modelName);
    }
}
