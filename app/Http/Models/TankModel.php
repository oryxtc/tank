<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/2
 * Time: 10:46
 */

namespace App\Http\Models;


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
        'A1' => 2500,
        'B1' => 800,
        'B2' => 200,
        'B3' => 400,
        'B4' => 400,
        'B5' => 600,
        'C1' => 800,
        'C2' => 200,
        'C3' => 400,
        'C4' => 400,
        'C5' => 600,];

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

    public static $BTanks = [
        'B1' => [
            "name"      => "K2黑豹",
            "gongji"    => 3,
            "shengming" => 5,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'B2' => [
            "name"      => "T-90",
            "gongji"    => 1,
            "shengming" => 10,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'B3' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'B4' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'B5' => [
            "name"      => "99主战坦克",
            "gongji"    => 1,
            "shengming" => 3,
            "yidong"    => 1,
            "shecheng"  => 3,
            "shiye"     => 1],];

    public static $CTanks = [
        'C1' => [
            "name"      => "K2黑豹",
            "gongji"    => 3,
            "shengming" => 5,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'C2' => [
            "name"      => "T-90",
            "gongji"    => 1,
            "shengming" => 10,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'C3' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'C4' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'C5' => [
            "name"      => "99主战坦克",
            "gongji"    => 1,
            "shengming" => 3,
            "yidong"    => 1,
            "shecheng"  => 3,
            "shiye"     => 1]];

    public static $tanks = [
        'B1' => [
            "name"      => "K2黑豹",
            "gongji"    => 3,
            "shengming" => 5,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'B2' => [
            "name"      => "T-90",
            "gongji"    => 1,
            "shengming" => 10,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'B3' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'B4' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'B5' => [
            "name"      => "99主战坦克",
            "gongji"    => 1,
            "shengming" => 3,
            "yidong"    => 1,
            "shecheng"  => 3,
            "shiye"     => 1],
        'C1' => [
            "name"      => "K2黑豹",
            "gongji"    => 3,
            "shengming" => 5,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'C2' => [
            "name"      => "T-90",
            "gongji"    => 1,
            "shengming" => 10,
            "yidong"    => 1,
            "shecheng"  => 1,
            "shiye"     => 1],
        'C3' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'C4' => [
            "name"      => "阿马塔",
            "gongji"    => 1,
            "shengming" => 4,
            "yidong"    => 2,
            "shecheng"  => 1,
            "shiye"     => 2],
        'C5' => [
            "name"      => "99主战坦克",
            "gongji"    => 1,
            "shengming" => 3,
            "yidong"    => 1,
            "shecheng"  => 3,
            "shiye"     => 1]];


    public static $request;
    public static $tempArea;
    public static $boss;
    public static $team;
    public static $teamId;
    public static $enemyTeam;
    public static $enemyTeamId;
    public        $flagObstacle = false;

    public function __construct()
    {
        self::$request   = Request()->all();
        self::$boss      = self::$request['tA'];
        self::$team      = self::$request['team'];
        self::$teamId    = substr(self::$team, -1);
        self::$enemyTeam = self::$team === 'tB' ? 'tC' : 'tB';;
        self::$enemyTeamId = self::$teamId === 'B' ? 'C' : 'B';;
    }

    public function computeAttack($tank, $row, $col, $map)
    {
        $rangeMax = $tank['shecheng'];
        $area     = [
            'DOWN'  => [],
            'RIGHT' => [],
            'LEFT'  => [],
            'UP'    => [],];
        $tempArea = [];
        foreach ($area as $key => $item) {
            if ($key === 'UP') {
                for ($i = 1; $i <= $rangeMax; $i++) {
                    $elementRow = $row - $i;
                    if ($elementRow < 0) {
                        break;
                    }
                    $noteArea = $map[$elementRow][$col];
                    //判断是否有物体能被射击
                    $canAttack = $this->canAttack($tank, $noteArea);
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
                    $canAttack = $this->canAttack($tank, $noteArea);
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
                    $canAttack = $this->canAttack($tank, $noteArea);
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
                    $canAttack = $this->canAttack($tank, $noteArea);
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

    public function canAttack($tank, $noteArea)
    {
        $canAttack   = false;
        $enemyTeamId = self::$enemyTeamId;
        $boos        = self::$boss;
        if ($noteArea['element'] === 'A1' && $boos['tanks'][0]['shengyushengming'] <= $tank['gongji']) {
            $canAttack = true;
        } else if (preg_match("/{$enemyTeamId}\d/", $noteArea['element'])) {
            $canAttack = true;
            $keyTanks  = "{$enemyTeamId}Tanks";
            $tanksData = self::$$keyTanks;
            //攻击小于敌方不打
            //                if($tank['gongji']<$tanksData[$noteArea['element']]['gongji']){
            //                    $canAttack=false;
            //                }
            //                //如果敌方是守 那只让我方守打
            //                if($gold==0 && preg_match("/{$enemyTeamId}2/", $noteArea['element'])){
            //                    if($tank['tId']!=="{$teamId}2"){
            //                        $canAttack=false;
            //                    }
            //                }
            //                //我方远射程 与敌方坦克距离小于等于1
            //                if($tank['tId']==="{$teamId}5" && $shecheng<=1){
            //                    $canAttack=false;
            //                }
        } elseif ($noteArea['element'] === 'A1') {
            $canAttack = true;
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
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['weights'] = $this->computeDirectWeights($tank, $noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]          = $noteArea;
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
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['weights'] = $this->computeDirectWeights($tank, $noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]          = $noteArea;
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
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['weights'] = $this->computeDirectWeights($tank, $noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]          = $noteArea;
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
                    $noteArea['length']    = $i;
                    $noteArea['direction'] = $key;
                    //方向权重
                    $noteArea['weights'] = $this->computeDirectWeights($tank, $noteArea['row'], $noteArea['col'], $key, $map);
                    $tempArea[]          = $noteArea;
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
        if ($tempArea[0]['weights'] <= 0) {
            return [];
        }
        return $tempArea[0];
    }

    private function computeDirectWeightsChild($weights, $noteArea, $tank, $distance)
    {
        $teamId      = self::$teamId;
        $enemyTeamId = self::$enemyTeamId;
        if (preg_match("/{$enemyTeamId}1/", $noteArea['element']) && preg_match("/{$teamId}[3-5]/", $tank['element']) && $distance == 1) {//如果对方是攻击强
            $weights -= $noteArea['weights'] * 3;
        } elseif (preg_match("/{$enemyTeamId}2/", $noteArea['element']) && preg_match("/{$teamId}[1]/", $tank['element']) && $distance == 1) { //对方防守强
            $weights -= $noteArea['weights'] * 2;
        } elseif (preg_match("/{$enemyTeamId}[3-4]/", $noteArea['element'])) {   //对方移动快
            $weights += $noteArea['weights'] * ($distance / 10);
        } elseif (preg_match("/{$enemyTeamId}5/", $noteArea['element']) && preg_match("/{$teamId}[1-4]/", $tank['element']) && $distance > 1) {//对方射程远
            $weights -= $noteArea['weights'] * 2;
        } elseif (preg_match("/{$enemyTeamId}5/", $noteArea['element']) && preg_match("/{$teamId}[1-4]/", $tank['element']) && $distance = 1) { //对方射程远
            $weights += $noteArea['weights'] * 2;
        } elseif (preg_match("/{$teamId}5/", $tank['element']) && preg_match("/{$enemyTeamId}[1-4]/", $noteArea['element']) && $distance > 1) { //我方射程远
            $weights += $noteArea['weights'] * 2;
        } else if (preg_match("/{$enemyTeamId}[1-5]/", $noteArea['element']) && $distance == 1) {
            $weights -= $noteArea['weights'] * ($distance / 10);
        } else if (preg_match("/{$teamId}[1-5]/", $noteArea['element']) && $distance < 8) {
            $weights -= $noteArea['weights'] * ($distance / 10);
        } else {
            $weights += $noteArea['weights'];
        }
        return $weights;
    }

    public function computeDirectWeights($tank, $row, $col, $direction, $map)
    {
        $weights = 0;
        $distance=1;
        if ($direction === 'UP') {
            $distance = abs($tank['row'] - $row);
        } elseif ($direction === 'RIGHT') {
            $distance = abs($tank['col'] - $col);
        } elseif ($direction === 'DOWN') {
            $distance = abs($tank['row'] - $row);
        } elseif ($direction === 'LEFT') {
            $distance = abs($tank['col'] - $col);
        }
        $weights  = $this->computeDirectWeightsChild($weights, $map[$row][$col], $tank, $distance);
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
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea, $key, $map, $tank);
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
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea, $key, $map, $tank);
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
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea, $key, $map, $tank);
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
                    $noteArea['directionWeights'] = $this->computeRandomDirectWeights($noteArea, $key, $map, $tank);
                    $tempArea[]                   = $noteArea;
                    unset($noteArea);
                }
            }
        }
        self::$tempArea     = $tempArea;
        $this->flagObstacle = $flagObstacle;
        //如果四周走不通 就原地开火
        if (empty($tempArea)) {
            return [
                'type'      => 'FIRE',
                'direction' => array_keys($area)[rand(0, 3)],
                'length'    => 1,];
        }
        //排序权重最大的
        usort($tempArea, function ($a, $b) {
            if ($a['directionWeights'] == $b['directionWeights']) {
                return 0;
            }
            return ($a['directionWeights'] < $b['directionWeights']) ? 1 : -1;
        });
        $areaWeightsMax = $tempArea[0];
        if ($areaWeightsMax['directionWeights'] >= 10000) {
            return $areaWeightsMax;
        } elseif ($areaWeightsMax['directionWeights'] < 10000) {
            return [];
        }
        return $areaWeightsMax;
    }

    /**
     * 最终路线
     * @return mixed
     */
    public function finalRoute()
    {
        $tempArea = self::$tempArea;
        //排序方向权重最大情况
        if ($this->flagObstacle === true) {
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
        return $areaWeightsMax;
    }

    public function computeRandomDirectWeights($noteArea, $direction, $map, $tank)
    {
        $row=$noteArea['row'];
        $col=$noteArea['col'];
        $length=$noteArea['length'];
        $weights = 0;
        if ($map[$row][$col]['element'] === 'M2') {
            $weights += 30000;
        }
        if ($direction === 'UP') {
            for ($i = $row; $i >= 0; $i--) {
                for ($k = 0; $k < count($map[0], 0); $k++)
                    $weights = $this->computeRandomDirectWeightsChild($weights, $map[$i][$k], $tank, $length);
            }
        } elseif ($direction === 'RIGHT') {
            for ($i = $col; $i < count($map[0], 0); $i++) {
                for ($k = 0; $k < count($map, 0); $k++)
                    $weights = $this->computeRandomDirectWeightsChild($weights, $map[$k][$i], $tank, $length);
            }
        } elseif ($direction === 'DOWN') {
            for ($i = $row; $i < count($map, 0); $i++) {
                for ($k = 0; $k < count($map[0], 0); $k++)
                    $weights = $this->computeRandomDirectWeightsChild($weights, $map[$i][$k], $tank, $length);
            }
        } elseif ($direction === 'LEFT') {
            for ($i = $col; $i >= 0; $i--) {
                for ($k = 0; $k < count($map, 0); $k++)
                    $weights = $this->computeRandomDirectWeightsChild($weights, $map[$k][$i], $tank, $length);
            }
        }
        return $weights;
    }

    public function computeRandomDirectWeightsChild($weights, $noteArea, $tank, $length)
    {
        $teamId      = self::$teamId;
        $enemyTeamId = self::$enemyTeamId;
        if (preg_match("/{$teamId}[3-4]/", $tank['element'])) { //我方移动
            $weights += ($noteArea['weights'] + 400 * ($length - 1));
        } elseif (preg_match("/{$teamId}\d/", $noteArea['element'])) {
            $weights += 30;
        } else if (preg_match("/{$enemyTeamId}\d/", $noteArea['element'])) {
            $weights += $noteArea['weights'];
        } else if (preg_match("/M[4-8]{1}/", $noteArea['element'])) {

        } else if (preg_match("/M2/", $noteArea['element'])) {
            $weights += $noteArea['weights'];
        } else {
            $weights += $noteArea['weights'];
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