<?php
/**
 * Created by PhpStorm.
 * User: oryxtc
 * Date: 2018/12/2
 * Time: 10:46
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Log;

class TankModel
{
    public static $elementWeights = [
        'M1' => 0,
        'M2' => 100,
        'M3' => 10,
        'M4' => -10,
        'M5' => -10,
        'M6' => -10,
        'M7' => -10,
        'M8' => -10,
        'A1' => 20,
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
        'A1' => 2,
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

    public function computeRoute($tank, $row, $col, $map)
    {
        $lengthMax = $tank['yidong'];
        $rangeMax  = $tank['shecheng'];
        $viewMax   = $tank['shiye'];
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
                    $noteArea['row'] = $elementRow;
                    $noteArea['col'] = $col;
                    $noteArea['type']='MOVE';

                    $noteArea['direction']=$key;
                    //方向权重
                    $noteArea['directionWeights']=$this->computeDirectWeights($noteArea['row'], $noteArea['col'],$key,$map);
                    $tempArea[]      = $noteArea;
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
                    $noteArea['row'] = $row;
                    $noteArea['col'] = $elementCol;
                    $noteArea['type']='MOVE';
                    $noteArea['direction']=$key;
                    //方向权重
                    $noteArea['directionWeights']=$this->computeDirectWeights($noteArea['row'], $noteArea['col'],$key,$map);
                    $tempArea[]      = $noteArea;
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
                    $noteArea['row'] = $elementRow;
                    $noteArea['col'] = $col;
                    $noteArea['type']='MOVE';

                    $noteArea['direction']=$key;
                    //方向权重
                    $noteArea['directionWeights']=$this->computeDirectWeights($noteArea['row'], $noteArea['col'],$key,$map);
                    $tempArea[]      = $noteArea;
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
                    $noteArea['row'] = $row;
                    $noteArea['col'] = $elementCol;
                    $noteArea['type']='MOVE';
                    $noteArea['direction']=$key;
                    //方向权重
                    $noteArea['directionWeights']=$this->computeDirectWeights($noteArea['row'], $noteArea['col'],$key,$map);
                    $tempArea[]      = $noteArea;
                    unset($noteArea);
                }
            }
        }
        if(empty($tempArea)){
            return [
                'type'=>'FIRE',
                'direction'=>'DOWN',
            ];
        }
        //排序权重最大的
        usort($tempArea, function ($a, $b) {
            if ($a['weights'] == $b['weights']) {
                return 0;
            }
            return ($a['weights'] < $b['weights']) ? 1 : -1;
        });
        $weightsMax=$tempArea[0]['weights'];
        //过滤剩下相同最大权重值
        $tempArea=array_filter($tempArea,function ($item)use ($weightsMax){
            return $item['weights']==$weightsMax;
        });
        //排序方向权重最大情况
        usort($tempArea, function ($a, $b) {
            if ($a['directionWeights'] == $b['directionWeights']) {
                return 0;
            }
            return ($a['directionWeights'] < $b['directionWeights']) ? 1 : -1;
        });
        $areaWeightsMax =$tempArea[0];
        return $areaWeightsMax;
    }

    public function computeDirectWeights($row,$col,$direction,$map){
        $weights=0;
        if($direction==='UP'){
           for ($i=$row-1;$i>=0;$i--){
               $weights+=$map[$i][$col]['weights'];
           }
        }elseif ($direction==='RIGHT'){
            for ($i=$col+1;$i<count($map[0], 0);$i++){
                $weights+=$map[$row][$i]['weights'];
            }
        }elseif ($direction==='DOWN'){
            for ($i=$row+1;$i<count($map,0);$i++){
                $weights+=$map[$i][$col]['weights'];
            }
        }elseif ($direction==='LEFT'){
            for ($i=$col-1;$i>=0;$i--){
                $weights+=$map[$row][$i]['weights'];
            }
        }
        return $weights;
    }
}