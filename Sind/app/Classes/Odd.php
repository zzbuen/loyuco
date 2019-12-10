<?php
/**
 * Created by PhpStorm.
 * User: 皮皮奇
 * Date: 2018/4/17
 * Time: 13:58
 * 传入赔率 得到总奖金
 */

namespace App\Classes;


class Odd
{
    /*非序列化玩法*/
    public function theOne($odds,$winNum,$fanDian){
        if($fanDian == 'up') return $odds[$winNum][1];
        if($fanDian == 'down') return $odds[$winNum][0];
        return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);

    }
    public function ds($odds,$winNum,$fanDian){
        $total = 0;
        foreach ($winNum as $item){
            if($fanDian == 'up') $total += $odds[$item][1];
            elseif($fanDian == 'down') $total += $odds[$item][0];
            else $total += $odds[$item][1] - ($odds[$item][2]*$fanDian*10);
        }
        return $total;
    }
    /*五星直选单式*/
//    public function odds_010101($odds,$winNum,$fanDian){
//        return $this->theOne($odds,$winNum,$fanDian);
//    }
//    public function odds_010102($odds,$winNum,$fanDian){
//
//    }
    /*五星组合*/
    public function odds_010103($odds,$winNum,$fanDian){
        $total = 0;
        switch ($winNum){
            case 1:
                $winNum = '五星组合五';
                if($fanDian == 'up') return $odds[$winNum][1];
                if($fanDian == 'down') return $odds[$winNum][0];
                return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 2:
                $winNum = '五星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合五';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                return  $total;
                break;
            case 3:
                $winNum = '五星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合五';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+=$odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 4:
                $winNum = '五星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合五';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 5:
                $winNum = '五星组合一';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '五星组合五';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            default:
                return 0;
        }
        return $total;

    }
    /*龙虎和*/
    public function odds_010114($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*总和大小单双*/
    public function odds_010115($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    /*总和组合大小单双*/
    public function odds_010116($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*四星组合*/
    public function odds_010203($odds,$winNum,$fanDian){
        $total = 0;
        switch ($winNum){
            case 1:
                $winNum = '四星组合四';
                if($fanDian == 'up') return $odds[$winNum][1];
                if($fanDian == 'down') return $odds[$winNum][0];
                return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 2:

                $winNum = '四星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);

                $winNum = '四星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                return  $total;
                break;
            case 3:
                $winNum = '四星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '四星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '四星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+=$odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 4:
                $winNum = '四星组合一';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '四星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '四星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '四星组合四';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif ($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;

            default:
                return 0;
        }
        return $total;

    }
    /*前三组合*/
    public function odds_010303($odds,$winNum,$fanDian){
        $total = 0;
        switch ($winNum){
            case 1:
                $winNum = '三星组合三';
                if($fanDian == 'up') return $odds[$winNum][1];
                if($fanDian == 'down') return $odds[$winNum][0];
                return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 2:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);

                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                return  $total;
                break;
            case 3:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合一';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+=$odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            default:
                return 0;
        }
        return $total;

    }
    /*三星混合组选*/
    public function odds_010310($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010311($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选包胆*/
    public function odds_010312($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010314($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组合*/
    public function odds_010403($odds,$winNum,$fanDian){
        $total = 0;
        switch ($winNum){
            case 1:
                $winNum = '三星组合三';
                if($fanDian == 'up') return $odds[$winNum][1];
                if($fanDian == 'down') return $odds[$winNum][0];
                return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 2:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);

                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                return  $total;
                break;
            case 3:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合一';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+=$odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            default:
                return 0;
        }
        return $total;

    }
    /*三星混合组选*/
    public function odds_010410($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010411($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选包胆*/
    public function odds_010412($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010414($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组合*/
    public function odds_010503($odds,$winNum,$fanDian){
        $total = 0;
        switch ($winNum){
            case 1:
                $winNum = '三星组合三';
                if($fanDian == 'up') return $odds[$winNum][1];
                if($fanDian == 'down') return $odds[$winNum][0];
                return $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            case 2:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);

                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                return  $total;
                break;
            case 3:
                $winNum = '三星组合三';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合二';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+= $odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                $winNum = '三星组合一';
                if($fanDian == 'up') $total+= $odds[$winNum][1];
                elseif($fanDian == 'down') $total+=$odds[$winNum][0];
                else $total += $odds[$winNum][1] - ($odds[$winNum][2]*$fanDian*10);
                break;
            default:
                return 0;
        }
        return $total;

    }
    /*三星混合组选*/
    public function odds_010510($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010511($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选包胆*/
    public function odds_010512($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_010514($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*任选三 混合组选*/
    public function odds_011208($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    /*三星组选和值*/
    public function odds_011209($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011401($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011402($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011403($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011404($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011405($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011406($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011407($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011408($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011409($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011410($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011501($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011502($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011503($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011504($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011505($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011506($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011507($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011508($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011509($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011510($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011601($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011701($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_011702($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011801($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011802($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011803($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011804($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011805($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011806($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011807($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011808($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_011809($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_020501($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_030101($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_030102($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_030201($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_030501($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_030901($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031001($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031101($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031201($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031301($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031401($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_031501($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_040901($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_040902($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_040903($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_050108($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_050109($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_050601($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_050701($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_050801($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_060201($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_070101($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_070102($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_070103($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_070104($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_070201($odds,$winNum,$fanDian){
        return $this->ds($odds,$winNum,$fanDian);
    }
    public function odds_070202($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_080101($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_080102($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
    public function odds_080103($odds,$winNum,$fanDian){
        return $this->theOne($odds,$winNum,$fanDian);
    }
}