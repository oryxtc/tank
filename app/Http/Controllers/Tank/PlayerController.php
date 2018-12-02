<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/1
 * Time: 21:53
 */

namespace App\Http\Controllers\Tank;


use App\Http\Controllers\Controller;
use App\Http\Models\TankModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    private $team;
    private $enemyTeam;
    private $teamId;
    private $enemyTeamId;
    private $mapRowLen;
    private $mapColLen;

    private $newMap = [];


    public function __construct()
    {
        $request           = Request()->all();
        $this->team        = $request['team'];
        $this->enemyTeam   = $this->team === 'tB' ? 'tC' : 'tB';
        $this->teamId      = substr($this->team, -1);
        $this->enemyTeamId = $this->teamId === 'B' ? 'C' : 'B';
        $this->mapRowLen   = $request['view']['rowLen'];
        $this->mapColLen   = $request['view']['colLen'];
    }

    public function init(Request $request)
    {
        $input = $request->all();
        return $this->apiReturn(null, "/init");
    }

    public function action(Request $request)
    {
        $requestData = $request->all();
        $mapData     = $requestData['view']['map'];

        foreach ($mapData as $rowKey => $rowData) {
            $this->newMap[$rowKey] = $rowData;
            foreach ($rowData as $colKey => $val) {
                $this->newMap[$rowKey][$colKey] = [];
                if (!isset($this->newMap[$rowKey][$colKey]['weights'])) {
                    $this->newMap[$rowKey][$colKey]['weights'] = 0;
                }
                //TODO 计算坦克对上下左右权重影响
                //                switch ($val){
                //                    case 'M1'
                //                }
                $this->newMap[$rowKey][$colKey]['weights'] += TankModel::$elementWeights[$val];
            }
        }

        $teamTanks = $requestData[$this->team];
        foreach ($teamTanks as $tankKey => $tank) {
            $lengthMax = $tank['yidong'];
            $rangeMax  = $tank['shecheng'];
            $viewMax   = $tank['shiye'];
            //赋值返回值
            $responseData[$tankKey] = [
                'tId' => $tank['tId']];
            //计算该坦克上下左右权重

        }
        Log::info($this->newMap);

        $responseData = [
            [
                'tId'       => "{$this->teamId}1",
                'direction' => 'DOWN',
                'type'      => 'MOVE',
                'length'    => 1,
                'useGlod'   => true],
            [
                'tId'       => "{$this->teamId}2",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->teamId}3",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->teamId}4",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],
            [
                'tId'       => "{$this->teamId}5",
                'direction' => 'DOWN',
                'type'      => 'FIRE',
                'length'    => 1,
                'useGlod'   => false],];

        return $this->apiReturn($responseData, '/action');
    }
}