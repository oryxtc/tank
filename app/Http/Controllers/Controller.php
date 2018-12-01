<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiReturn($data, $action, $code = "0", $msg = "succeeded", $ok = true)
    {
        $responseData = [
            "code"   => $code,
            "action" => $action,
            "msg"    => "succeeded",
            "ok"     => $ok];
        if ($data !== null) {
            $responseData['data'] = $data;
        }
        return response()->json($responseData);
    }
}
