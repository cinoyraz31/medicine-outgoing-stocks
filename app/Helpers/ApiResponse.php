<?php
namespace App\Helpers;

class ApiResponse {
    public $data = [];
    public $meta = [];
    public $message = '';
    public $errors = [];
    private $cacheIt = null;
    private $cacheKey = null;
    private $cacheSecDuration = 90;

    public function data($data) {
        $this->data = $data;
        return $this;
    }

    public function meta($meta) {
        $this->meta = $meta;
        return $this;
    }

    public function message($message) {
        $this->message = $message;
        return $this;
    }

    public function error($detail, $title = '', $code = '', $source = '') {
        $error = [];
        $error['detail'] = $detail;
        if ( $title != '' ) $error['title'] = $title ;
        if ( $code != '' ) $error['code'] = $code ;
        if ( $source != '') {
            $error['source'] = $source;
            if (method_exists($source, '__toString')) {
                $error['source'] = $source->__toString();
            }
        };
        $this->error = array_push($this->errors, $error);
        return $this;
    }

    public function success($status = 200) {
        $response = [];
        if ($this->meta != []) {
            $response['meta'] = $this->meta;
            if ($this->message != '') {
                $response['meta']['message'] = $this->message;
            }
        } else if ($this->message != '') {
            $response['meta']['message'] = $this->message;
        }

        $response['data'] = $this->data;

        return response(json_encode($response), $status)->header('Content-Type', 'application/json');
    }

    public function internalError($status = 500) {
        if ($this->errors == []) $this->defaultError();
        $response['errors'] = $this->errors;

        return response(json_encode($response), $status)->header('Content-Type', 'application/json');
    }

    public function clientError($status = 400) {
        if ($this->errors == []) $this->defaultError();
        $response['errors'] = $this->errors;

        return response(json_encode($response), $status)->header('Content-Type', 'application/json');
    }

    private function defaultError() {
        $this->error('Ups, something wrong here', '', 1);
    }

    public function json(String $viewPath, $data = [])
    {
        $content = include app_path(). '/Responses/'. $viewPath . '.json.php';
        $contentJson = json_encode($content);

        return response($contentJson)->header('Content-Type', 'application/json');
    }
}
