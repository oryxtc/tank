<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/2
 * Time: 10:46
 */

namespace App\Http\Models;


use App\Http\Controllers\Tank\PlayerController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TankModel
{

    public static $elementWeights = [
        'M1' => 0,
        'M2' => 20000,
        'M3' => 10,
        'M4' => -350,
        'M5' => -350,
        'M6' => -350,
        'M7' => -350,
        'M8' => -350,
        'A1' => 50,
        'B1' => 80,
        'B2' => 20,
        'B3' => 40,
        'B4' => 40,
        'B5' => 60,
        'C1' => 80,
        'C2' => 20,
        'C3' => 40,
        'C4' => 40,
        'C5' => 60,];

    public static $elementFlog = [
        'M1' => 1,
        'M2' => 1,
        'M3' => 1,
        'M4' => 8,
        'M5' => 8,
        'M6' => 8,
        'M7' => 8,
        'M8' => 8,
        'A1' => 16,
        'B1' => 2,
        'B2' => 2,
        'B3' => 2,
        'B4' => 2,
        'B5' => 2,
        'C1' => 4,
        'C2' => 4,
        'C3' => 4,
        'C4' => 4,
        'C5' => 4,];

    public static $BTanks=[
        'B1'=>[
            "name"=> "K2黑豹",
            "gongji"=> 3,
            "shengming"=> 5,
            "shengyushengming"=> 5,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'B2'=>[
            "name"=> "T-90",
            "gongji"=> 1,
            "shengming"=> 10,
            "shengyushengming"=> 10,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'B3'=>[
            "name"=> "K2黑豹",
            "gongji"=> 3,
            "shengming"=> 5,
            "shengyushengming"=> 5,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'B4'=>[
            "name"=> "阿马塔",
            "gongji"=> 1,
            "shengming"=> 4,
            "shengyushengming"=> 4,
            "yidong"=> 2,
            "shecheng"=> 1,
            "shiye"=> 2
        ],
        'B5'=>[
            "name"=> "99主战坦克",
            "gongji"=> 1,
            "shengming"=> 3,
            "shengyushengming"=> 3,
            "yidong"=> 1,
            "shecheng"=> 3,
            "shiye"=> 1
        ],
    ];

    public static $CTanks=[
        'C1'=>[
            "name"=> "K2黑豹",
            "gongji"=> 3,
            "shengming"=> 5,
            "shengyushengming"=> 5,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'C2'=>[
            "name"=> "T-90",
            "gongji"=> 1,
            "shengming"=> 10,
            "shengyushengming"=> 10,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'C3'=>[
            "name"=> "K2黑豹",
            "gongji"=> 3,
            "shengming"=> 5,
            "shengyushengming"=> 5,
            "yidong"=> 1,
            "shecheng"=> 1,
            "shiye"=> 1
        ],
        'C4'=>[
            "name"=> "阿马塔",
            "gongji"=> 1,
            "shengming"=> 4,
            "shengyushengming"=> 4,
            "yidong"=> 2,
            "shecheng"=> 1,
            "shiye"=> 2
        ],
        'C5'=>[
            "name"=> "99主战坦克",
            "gongji"=> 1,
            "shengming"=> 3,
            "shengyushengming"=> 3,
            "yidong"=> 1,
            "shecheng"=> 3,
            "shiye"=> 1
        ],
    ];

    public function computeAttack($tank, $row, $col, $map)
    {
        $requestData    = Request()->all();
        $enemyTeam      = (new PlayerController())->enemyTeam;
        $enemyTeamId    = (new PlayerController())->enemyTeamId;
        $enemyTeamTanks = $requestData[$enemyTeam]['tanks'];
        $enemyTeamTanks = array_column($enemyTeamTanks, null, 'tId');
        $lengthMax      = $tank['yidong'];
        $rangeMax       = $tank['shecheng'];
        $area           = [
            'DOWN'  => [],
            'RIGHT' => [],
            'LEFT'  => [],
            'UP'    => [],];
        $tempArea       = [];
        foreach ($area as $key => $item) {
            if ($key === 'UP') {
                for ($i = 1; $i <= $rangeMax; $i++) {
                    $elementRow = $row - $i;
                    if ($elementRow < 0) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否有物体能被射击
                    $canAttack=$this->canAttack($tank,$noteArea,$enemyTeamId,$i);
                    if (!$canAttack) {
                        continue;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'FIRE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $tempArea[] = $noteArea;
                    unset($noteArea);

                }
            } elseif ($key === 'RIGHT') {
                for ($i = 1; $i <= $rangeMax; $i++) {
                    $elementCol = $col + $i;
                    if ($elementCol >= count($map[0], 0)) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否有物体能被射击
                    $canAttack=$this->canAttack($tank,$noteArea,$enemyTeamId,$i);
                    if (!$canAttack) {
                        continue;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'FIRE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $tempArea[] = $noteArea;
                    unset($noteArea);
                }
            } elseif ($key === 'DOWN') {
                for ($i = 1; $i <= $rangeMax; $i++) {
                    $elementRow = $row + $i;
                    if ($elementRow >= count($map, 0)) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否有物体能被射击
                    $canAttack=$this->canAttack($tank,$noteArea,$enemyTeamId,$i);
                    if (!$canAttack) {
                        continue;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'FIRE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $tempArea[] = $noteArea;
                    unset($noteArea);
                }
            } else if ($key === 'LEFT') {
                for ($i = 1; $i <= $rangeMax; $i++) {
                    $elementCol = $col - $i;
                    if ($elementCol < 0) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否有物体能被射击
                    $canAttack=$this->canAttack($tank,$noteArea,$enemyTeamId,$i);
                    if (!$canAttack) {
                        continue;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'FIRE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $tempArea[] = $noteArea;
                    unset($noteArea);
                }
            }
        }
        if (empty($tempArea)) {
            return [];
        }
        $areaWeightsMax = $tempArea[0];
        return $areaWeightsMax;
    }

    public function canAttack($tank,$noteArea,$enemyTeamId,$shecheng){
        $boos = (new PlayerController())->boos;
        $teamId=(new PlayerController())->teamId;
        if ($noteArea['element'] === 'A1' && $boos['tanks'][0]['shengyushengming'] <= $tank['gongji']) {
            $canAttack = true;
        } else {
            $canAttack = preg_match("/{$enemyTeamId}\d/", $noteArea['element']);
            if($canAttack){
                $keyTanks="{$enemyTeamId}Tanks";
                $tanksData=self::$$keyTanks;
                //攻击小于敌方不打
                if($tank['gongji']<$tanksData[$noteArea['element']]['gongji']){
                    $canAttack=false;
                }
                //如果敌方是守 那只让我方守打
                if(preg_match("/{$enemyTeamId}2/", $noteArea['element'])){
                    if($tank['tId']!=="{$teamId}2"){
                        $canAttack=false;
                    }
                }
                //我方远射程 与敌方坦克距离小于等于1
                if($tank['tId']==="{$teamId}5" && $shecheng<=1){
                    $canAttack=false;
                }
            }
        }
        return $canAttack;
    }

    /**
     * 计算路线
     * @param $tank
     * @param $row
     * @param $col
     * @param $map
     * @return array
     */
    public function computeRoute($tank, $row, $col, $map)
    {
        $lengthMax = $tank['yidong'];
        $area      = [
            'DOWN'  => [],
            'RIGHT' => [],
            'LEFT'  => [],
            'UP'    => [],];
        $tempArea  = [];
        foreach ($area as $key => $item) {
            if ($key === 'UP') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementRow = $row - $i;
                    if ($elementRow < 0) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $lengthMax;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);

                }
            } elseif ($key === 'RIGHT') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementCol = $col + $i;
                    if ($elementCol >= count($map[0], 0)) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $lengthMax;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            } elseif ($key === 'DOWN') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementRow = $row + $i;
                    if ($elementRow >= count($map, 0)) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $lengthMax;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            } else if ($key === 'LEFT') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementCol = $col - $i;
                    if ($elementCol < 0) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $lengthMax;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            }
        }

        if (empty($tempArea)) {
            return [];
        }
        //排序权重最大的
        usort($tempArea, function ($a, $b) {
            if ($a['weights'] == $b['weights']) {
                return 0;
            }
            return ($a['weights'] < $b['weights']) ? 1 : -1;
        });
        $weightsMax = $tempArea[0]['weights'];
        //过滤剩下相同最大权重值
        $tempArea = array_filter($tempArea, function ($item) use ($weightsMax) {
            return $item['weights'] == $weightsMax;
        });
        //排序方向权重最大情况
        usort($tempArea, function ($a, $b) {
            if ($a['directionWeights'] == $b['directionWeights']) {
                return 0;
            }
            return ($a['directionWeights'] < $b['directionWeights']) ? 1 : -1;
        });
        $directionWeightsMax = $tempArea[0]['directionWeights'];
        if ($directionWeightsMax == 0) {
            return [];
        }
        $areaWeightsMax = $tempArea[0];
        return $areaWeightsMax;
    }

    public function computeDirectWeights($row, $col, $direction, $map)
    {
        $weights = 0;
        if ($direction === 'UP') {
            for ($i = $row; $i >= 0; $i--) {
                if (preg_match("/[B,C]\d/", $map[$i][$col]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$i][$col]['weights'];
                }
            }
        } elseif ($direction === 'RIGHT') {
            for ($i = $col; $i < count($map[0], 0); $i++) {
                if (preg_match("/[B,C]\d/", $map[$row][$i]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$row][$i]['weights'];
                }
            }
        } elseif ($direction === 'DOWN') {
            for ($i = $row; $i < count($map, 0); $i++) {
                if (preg_match("/[B,C]\d/", $map[$i][$col]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$i][$col]['weights'];
                }
            }
        } elseif ($direction === 'LEFT') {
            for ($i = $col; $i >= 0; $i--) {
                if (preg_match("/[B,C]\d/", $map[$row][$i]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$row][$i]['weights'];
                }
            }
        }
        return $weights;
    }

    /**
     * 随机路线
     * @param $tank
     * @param $row
     * @param $col
     * @param $map
     * @return array|mixed
     */
    public function computeRandom($tank, $row, $col, $map, $findGlod = false)
    {
        $lengthMax    = $tank['yidong'];
        $flagObstacle = false;
        $area         = [
            'DOWN'  => [],
            'RIGHT' => [],
            'LEFT'  => [],
            'UP'    => [],];
        $tempArea     = [];
        foreach ($area as $key => $item) {
            if ($key === 'UP') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementRow = $row - $i;
                    if ($elementRow < 0) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10;
                    if (($tank['flag'] | $noteArea['flag']) >= 10) {
                        $flagObstacle = true;
                    }
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);

                }
            } elseif ($key === 'RIGHT') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementCol = $col + $i;
                    if ($elementCol >= count($map[0], 0)) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if (($tank['flag'] | $noteArea['flag']) >= 10) {
                        $flagObstacle = true;
                    }
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            } elseif ($key === 'DOWN') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementRow = $row + $i;
                    if ($elementRow >= count($map, 0)) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if (($tank['flag'] | $noteArea['flag']) >= 10) {
                        $flagObstacle = true;
                    }
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            } else if ($key === 'LEFT') {
                for ($i = 1; $i <= $lengthMax; $i++) {
                    $elementCol = $col - $i;
                    if ($elementCol < 0) {
                        break;
                    }
                    $noteArea = $map[$row][$elementCol];
                    //判断是否能移动到此位置
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10 || ($tank['flag'] | $noteArea['flag']) == $tank['flag'];
                    if (($tank['flag'] | $noteArea['flag']) >= 10) {
                        $flagObstacle = true;
                    }
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $row;
                    $noteArea['col']       = $elementCol;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            }
        }
        //如果四周走不通 就原地开火
        if (empty($tempArea)) {
            return [
                'type'      => 'FIRE',
                'direction' => 'DOWN',
                'length'    => 1,];
        }
        //排序方向权重最大情况
        if ($flagObstacle === true && $findGlod === false) {
            $areaWeightsMax = $this->randomDirection($tempArea);
        } else {
            //排序权重最大的
            usort($tempArea, function ($a, $b) {
                if ($a['directionWeights'] == $b['directionWeights']) {
                    return 0;
                }
                return ($a['directionWeights'] < $b['directionWeights']) ? 1 : -1;
            });
            $areaWeightsMax = $tempArea[0];
        }
        if ($findGlod === true && $areaWeightsMax['directionWeights'] >= 10000) {
            return $areaWeightsMax;
        } elseif ($findGlod === true && $areaWeightsMax['directionWeights'] < 10000) {
            return [];
        }
        return $areaWeightsMax;
    }

    public function computeRandomDirectWeights($row, $col, $direction, $map)
    {
        $teamId  = (new PlayerController())->teamId;
        $weights = 0;
        if ($map[$row][$col]['element'] === 'M2') {
            $weights += 30000;
        }
        if ($direction === 'UP') {
            for ($i = $row; $i >= 0; $i--) {
                for ($k = 0; $k < count($map[0], 0); $k++)
                    if (preg_match("/{$teamId}\d{1}/", $map[$i][$k]['element'])) {
                        //                    $weights -= 30;
                    } else if (preg_match("/M[4-8]{1}/", $map[$i][$k]['element'])) {

                    } else {
                        $weights += $map[$i][$k]['weights'];
                    }
            }
        } elseif ($direction === 'RIGHT') {
            for ($i = $col; $i < count($map[0], 0); $i++) {
                for ($k = 0; $k < count($map, 0); $k++)
                    if (preg_match("/{$teamId}\d{1}/", $map[$k][$i]['element'])) {
                        //                    $weights -= 30;
                    } else if (preg_match("/M[4-8]{1}/", $map[$k][$i]['element'])) {

                    } else {
                        $weights += $map[$k][$i]['weights'];
                    }
            }
        } elseif ($direction === 'DOWN') {
            for ($i = $row; $i < count($map, 0); $i++) {
                for ($k = 0; $k < count($map[0], 0); $k++)
                    if (preg_match("/{$teamId}\d{1}/", $map[$i][$k]['element'])) {
                        //                    $weights -= 30;
                    } else if (preg_match("/M[4-8]{1}/", $map[$i][$k]['element'])) {

                    } else {
                        $weights += $map[$i][$k]['weights'];
                    }
            }
        } elseif ($direction === 'LEFT') {
            for ($i = $col; $i >= 0; $i--) {
                for ($k = 0; $k < count($map, 0); $k++)
                    if (preg_match("/{$teamId}\d{1}/", $map[$k][$i]['element'])) {
                        //                    $weights -= 30;
                    } else if (preg_match("/M[4-8]{1}/", $map[$k][$i]['element'])) {

                    } else {
                        $weights += $map[$k][$i]['weights'];
                    }
            }
        }
        return $weights;
    }


    public function computeGlod($tank)
    {
        $shengming        = $tank['shengming'];
        $shengyushengming = $tank['shengyushengming'];
        if ($shengyushengming == 0 | $shengyushengming == 1) {
            return true;
        }
        $startData = Cache::get('start_date');
        if ((time() - $startData) > 5 * 60 - 30) {
            return true;
        }
        return false;
    }

    public function randomDirection($tempArea)
    {
        $randomNum       = rand(0, count($tempArea, 0) - 1);
        $randomDirection = $tempArea[$randomNum];
        return $randomDirection;
    }
}