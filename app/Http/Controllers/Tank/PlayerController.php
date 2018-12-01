<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/1
 * Time: 21:53
 */

namespace App\Http\Controllers\Tank;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function init(Request $request)
    {
        $input = $request->all();
        return $this->apiReturn(null, "/init");
    }

    public function action(Request $request)
    {
        $input = $request->all();

    }
}