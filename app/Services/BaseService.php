<?php
namespace App\Services;
use App\Helpers\GeneralHelper;
use Validator;
use Symfony\Component\HttpFoundation\Response;

class BaseService implements IBaseService
{
    public $data;
    public $message;
    public $errorMessage;
    public $validator;
    public $request;
    public $httpCode = Response::HTTP_OK;
    // Laravel Validation rules : https://laravel.com/docs/5.7/validation#custom-error-messages
    public $rules; // define rule Laravel Validator style
    public $ruleMessages; // define rule message Laravel Validator style

    public function __construct($request = null)
    {
        $this->request = $request;
        $this->constructAdapter();
    }

    public function constructAdapter()
    {}

    public function execute()
    {
    }

    public function isError() {
        if (empty($this->errorMessage)) {
            return false;
        }
        return true;
    }

    public function run() {
        if (!empty($this->request)) {
            if (empty($this->ruleMessages)) {
                $this->validator = Validator::make($this->request, $this->rules);
            } else {
                $this->validator = Validator::make($this->request, $this->rules, $this->ruleMessages);
            }
            if ($this->validator->fails()) {
                $this->httpCode = Response::HTTP_BAD_REQUEST;
                $this->errorMessage  = GeneralHelper::formatValidationError($this->validator->errors()->toArray(),' | ');
                return $this;
            } else {
                return $this->execute();
            }
        }
        return $this->execute();
    }
}
