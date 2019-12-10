<?php
/**
 * pk10注数计算
 * Created by 信.
 * Date: 2018/5/3
 */
namespace App\Classes;
class Pk10{
    /**
     * 作用：定位胆
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03,01 02 03,,,05,06,07,08,09,10"
     * @return int  总注数
     */
    public function bn_040101($bet_number){
        $total_number   = 0;
        $bet_number_arr = explode(",",$bet_number);
        if(count($bet_number_arr) != 10){
            return $total_number;
        };
        foreach ($bet_number_arr as $key=>$value){
            if($value!=""){
                $length = substr_count($value," ")+1;
                $total_number += $length;
            }
        }
        return $total_number;
    }


    /**
     * 作用：前五 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"
     * @return int  总注数
     */
    public function bn_040201(){

    }



    /**
     * 作用：前四 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"
     * @return int  总注数
     */
    public function bn_040301(){
        $bet_number = "01 02 03 04 05 06 07 08 09 10,01 02 03 04 05 06 07 08 09 10,01 02 03 04 05,01 02 03 04 05";
        $bets=explode(',', $bet_number);
        if(count($bets)!=3) return 0;
        $bets[0] =  explode(' ',$bets[0]);
        $bets[1] =  explode(' ',$bets[1]);
        $bets[2] =  explode(' ',$bets[2]);
        $bets[3] =  explode(' ',$bets[3]);
        $sect = count($bets[0]) *  count($bets[1]) *  count($bets[2]) *  count($bets[3]) -
            (count(array_intersect( $bets[0], $bets[1])) * count($bets[2])
                + count(array_intersect( $bets[0], $bets[2])) * count($bets[1])
                + count(array_intersect( $bets[1], $bets[2])) * count($bets[0]))
            + count(array_intersect($bets[0],$bets[1],$bets[2])) * 2;
        return $sect;
    }





    /**
     * 作用：前三 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"
     * @return int  总注数
     */
    public function bn_040401(){

    }



    /**
     * 作用：前二 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"01 02 03 04 05 06 07 08 09 10,01 02 03 04 05 06 07 08 09 10"
     * @return int  总注数
     */
    public function bn_040501($bet_number){
        $total_number   = 0;
        $bet_number_arr = explode(",",$bet_number);
        $one            = $bet_number_arr[0];
        $two            = $bet_number_arr[1];
        $one_arr        = explode(" ",$one);
        $two_arr        = explode(" ",$two);
        foreach ($one_arr as $key=>$value){
            if(strpos($two,$value) !== false){
                $total_number++;
            }
        }
        $total_number = count($one_arr)*count($two_arr) - $total_number;
        return $total_number;
    }



    /**
     * 作用：前一 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04 05 06 07 08 09 10"
     * @return int  总注数
     */
    public function bn_040601($bet_number){
        $total_number   = substr_count($bet_number," ")+1;
        return $total_number;
    }


    /**
     * 作用：龙虎冠军
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："龙,虎"
     * @return int  总注数
     */
    public function bn_040701($bet_number){
        $total_number = substr_count($bet_number,",")+1;
        return $total_number;
    }


    /**
     * 作用：大小单双
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number  "投注号码"大小单双,大小单双,大小单双,大小单双"
     * @return int  总注数
     */
    public function bn_040801($bet_number){
        $total_number   = 0;
        $bet_number_arr = explode(",",$bet_number);
        foreach ($bet_number_arr as $key=>$value){
            $length = strlen($value);
            $total_number += $length;
        }
        return $total_number;
    }



    /**
     * 作用：和值
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"01,02,03,04"
     * @return int  总注数
     */
    public function bn_040901($bet_number){
        $total_number   = substr_count($bet_number,",")+1;
        return $total_number;
    }


}
