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
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    const TEAM_NAME = 'tB';
    private $team = null;

    public function __construct()
    {
        $request = Request()->all();
        Log::info($request);
        if ($request['team'] === self::TEAM_NAME) {
            $this->team = 'B';
        } else {
            $this->team = 'C';
        }
    }

    public function init(Request $request)
    {
        $input = $request->all();
        return $this->apiReturn(null, "/init");
    }

    public function action(Request $request)
    {
        $input        = $request->all();
        $responseData = [
            [
                'tId'       => "{$this->team}1",
                'direction' => 'DOWN',
                'type'      => 'MOVE',
                'length'    => 1,
                'useGlod'   => true],
            [
                'tId'       => "{$this->team}2",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->team}3",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->team}4",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->team}5",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],];

        return $this->apiReturn($responseData, '/action');
    }
}