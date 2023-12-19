<?php

namespace App\Helpers;

use Tymon\JWTAuth\JWT;

class AuthHelper
{
    public function __construct()
    {
        $this->key = env('JWT_SECRET');
    }

    public function encode($data)
    {
        $expired = json_decode(env('EXPIRE_TOKEN_D_H_M_S', "[60, 0, 0, 0]"));
        $expiredInSecond = 0;
        if ($expired[0] !== 0) { // expire days
            $expiredInSecond += $expired[0] * 24 * 60 * 60;
        }
        if ($expired[1] !== 0) { // expire hours
            $expiredInSecond += $expired[1] * 60 * 60;
        }
        if ($expired[2] !== 0) { // expire minutes
            $expiredInSecond += $expired[2] * 60;
        }
        if ($expired[2] !== 0) { // expire seconds
            $expiredInSecond += $expired[3];
        }
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 0 ; //not before in seconds
        $expire_claim = $issuedat_claim + $expiredInSecond; // expire time in seconds

        if (is_string($data)) {
            $dataArray = \json_decode($data, true);
            $dataArray['exp'] = $expire_claim;
            $data = json_encode($dataArray);
        } else {
            $data['exp'] = $expire_claim;
            $data = json_encode($data);
        }

        JWT:
    }
}
