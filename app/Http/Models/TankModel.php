<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/2
 * Time: 10:46
 */

namespace App\Http\Models;


use App\Http\Controllers\Tank\PlayerController;
use Illuminate\Support\Facades\Log;

class TankModel
{

    public static $elementWeights = [
        'M1' => 0,
        'M2' => 100,
        'M3' => 10,
        'M4' => -50,
        'M5' => -50,
        'M6' => -50,
        'M7' => -50,
        'M8' => -50,
        'A1' => 10,
        'B1' => 20,
        'B2' => 20,
        'B3' => 10,
        'B4' => 10,
        'B5' => 10,
        'C1' => 20,
        'C2' => 20,
        'C3' => 10,
        'C4' => 10,
        'C5' => 10,];

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
                    $canAttack = preg_match("/{$enemyTeamId}\d/", $noteArea['element']);
                    if (!$canAttack) {
                        continue;
                    }
                    //判断该物体是否已经死亡
                    if ($enemyTeamTanks[$noteArea['element']]['shengyushengming'] == 0) {
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
                    $canAttack = preg_match("/{$enemyTeamId}\d/", $noteArea['element']);
                    if (!$canAttack) {
                        continue;
                    }
                    //判断该物体是否已经死亡
                    if ($enemyTeamTanks[$noteArea['element']]['shengyushengming'] == 0) {
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
                    $canAttack = preg_match("/{$enemyTeamId}\d/", $noteArea['element']);
                    if (!$canAttack) {
                        continue;
                    }
                    //判断该物体是否已经死亡
                    if ($enemyTeamTanks[$noteArea['element']]['shengyushengming'] == 0) {
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
                    $canAttack = preg_match("/{$enemyTeamId}\d/", $noteArea['element']);
                    if (!$canAttack) {
                        continue;
                    }
                    //判断该物体是否已经死亡
                    if ($enemyTeamTanks[$noteArea['element']]['shengyushengming'] == 0) {
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
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10;
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
        if ($weightsMax == 0) {
            return [];
        }
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
        $areaWeightsMax = $tempArea[0];
        return $areaWeightsMax;
    }

    public function computeDirectWeights($row, $col, $direction, $map)
    {
        $weights = 0;
        if ($direction === 'UP') {
            for ($i = $row - 1; $i >= 0; $i--) {
                if (preg_match("/[B,C]\d/", $map[$i][$col]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$i][$col]['weights'];
                }
            }
        } elseif ($direction === 'RIGHT') {
            for ($i = $col + 1; $i < count($map[0], 0); $i++) {
                if (preg_match("/[B,C]\d/", $map[$row][$i]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$row][$i]['weights'];
                }
            }
        } elseif ($direction === 'DOWN') {
            for ($i = $row + 1; $i < count($map, 0); $i++) {
                if (preg_match("/[B,C]\d/", $map[$i][$col]['element'])) {
                    $weights -= 30;
                } else {
                    $weights += $map[$i][$col]['weights'];
                }
            }
        } elseif ($direction === 'LEFT') {
            for ($i = $col - 1; $i >= 0; $i--) {
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
    public function computeRandom($tank, $row, $col, $map)
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
                    $notMove = ($tank['flag'] | $noteArea['flag']) == 6 || ($tank['flag'] | $noteArea['flag']) >= 10;
                    if ($notMove) {
                        break;
                    }
                    $noteArea['row']       = $elementRow;
                    $noteArea['col']       = $col;
                    $noteArea['type']      = 'MOVE';
                    $noteArea['length']    = $lengthMax;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $tempArea[] = $noteArea;
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
                    $tempArea[] = $noteArea;
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
                    $tempArea[] = $noteArea;
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
                    $tempArea[] = $noteArea;
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
        //随机一个方向
        $randomKey      = array_rand($tempArea, 1);
        $areaWeightsMax = $tempArea[$randomKey];
        Log::info($randomKey);
        return $areaWeightsMax;
    }
}