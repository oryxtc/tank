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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    public $team;
    public $enemyTeam;
    public $teamId;
    public $enemyTeamId;
    public $mapRowLen;
    public $mapColLen;
    public $boos;
    public $request;

    public $newMap = [];


    public function __construct()
    {
        $request           = Request()->all();
        $this->request     = $request;
        $this->boos        = $request['tA'];
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
        Cache::put('start_date', time(), 20);
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
        $newMap       = $this->newMap;
        $responseData = [];
        foreach ($newMap as $newRowKey => $newRoWData) {
            foreach ($newRoWData as $newColKey => $newItem) {
                if (preg_match("/{$this->teamId}\d/", $newItem['element'])) {
                    //获取当前坦克信息
                    $tankData = $teamTanks[$newItem['element']];
                    //优先攻击
                    $noteData = (new TankModel())->computeAttack(array_merge($tankData, $newItem), $newRowKey, $newColKey, $newMap);
                    if (empty($noteData)) {
                        $noteData = (new TankModel())->computeRandom(array_merge($tankData, $newItem), $newRowKey, $newColKey, $newMap, true);
                    }
                    //次要移动
                    if (empty($noteData)) {
                        $noteData = (new TankModel())->computeRoute(array_merge($tankData, $newItem), $newRowKey, $newColKey, $newMap);
                    }
                    //最后随机
                    if (empty($noteData)) {
                        $noteData = (new TankModel())->computeRandom(array_merge($tankData, $newItem), $newRowKey, $newColKey, $newMap);
                    }
                    $useGlod        = (new TankModel())->computeGlod(array_merge($tankData, $newItem));
                    $responseData[] = [
                        'tId'       => $tankData['tId'],
                        'direction' => $noteData['direction'],
                        'type'      => $noteData['type'],
                        'length'    => $noteData['length'],
                        'useGlod'   => $useGlod];
                }
            }
        }
        //判断是否有坦克死亡
        $tidArr = array_column($responseData, 'tId');
        for ($i = 1; $i <= 5; $i++) {
            if (!in_array("{$this->teamId}{$i}", $tidArr)) {
                $responseData[] = [
                    'tId'       => "{$this->teamId}{$i}",
                    'direction' => "WAIT",
                    'type'      => "FIRE",
                    'length'    => "1",
                    'useGlod'   => true];
            }
        }
        //        Log::info($responseData);
        return $this->apiReturn($responseData, '/action');
    }
}