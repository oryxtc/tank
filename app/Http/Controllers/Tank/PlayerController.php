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
        Log::info($request);
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
                //当前区域元素
                $this->newMap[$rowKey][$colKey]['element'] = $val;
                //当前区域元素二进制值
                $this->newMap[$rowKey][$colKey]['flag'] = TankModel::$elementFlog[$val];
                //当前区域权重
                if (!isset($this->newMap[$rowKey][$colKey]['weights'])) {
                    $this->newMap[$rowKey][$colKey]['weights'] = 0;
                }
                $this->newMap[$rowKey][$colKey]['weights'] += TankModel::$elementWeights[$val];
            }
        }
        $teamTanks = $requestData[$this->team]['tanks'];
        $teamTanks = array_column($teamTanks, null, 'tId');
        //计算路线&射击
        $newMap = $this->newMap;
        foreach ($newMap as $newRowKey => $newRoWData) {
            foreach ($newRoWData as $newColKey => $newItem) {
                if (preg_match("/{$this->teamId}\d/", $newItem['element'])) {
                    //获取当前坦克信息
                    $tankData = $teamTanks[$newItem['element']];
                    $noteData = (new TankModel())->computeRoute(array_merge($tankData,$newItem), $newRowKey, $newColKey, $newMap);
                    $responseData[]=[
                        'tId'       => $tankData['tId'],
                        'direction' => $noteData['direction'],
                        'type'      => $noteData['type'],
                        'length'    => 1,
                        'useGlod'   => false
                    ];
                }
            }
        }
        Log::info('$responseData',$responseData);


//        $responseData = [
//            [
//                'tId'       => "{$this->teamId}1",
//                'direction' => 'DOWN',
//                'type'      => 'MOVE',
//                'length'    => 1,
//                'useGlod'   => true
//            ],
//            [
//                'tId'       => "{$this->teamId}2",
//                'direction' => 'DOWN',
//                'type'      => 'FIRE',
//                'length'    => 1,
//                'useGlod'   => false],
//            [
//                'tId'       => "{$this->teamId}3",
//                'direction' => 'DOWN',
//                'type'      => 'FIRE',
//                'length'    => 1,
//                'useGlod'   => false],
//            [
//                'tId'       => "{$this->teamId}4",
//                'direction' => 'DOWN',
//                'type'      => 'FIRE',
//                'length'    => 1,
//                'useGlod'   => false],
//            [
//                'tId'       => "{$this->teamId}5",
//                'direction' => 'DOWN',
//                'type'      => 'FIRE',
//                'length'    => 1,
//                'useGlod'   => false],];

        return $this->apiReturn($responseData, '/action');
    }
}