<?php
/**
 * pk10开奖中奖注数计算
 * Created by 信.
 * Date: 2018/5/3
 */
namespace App\Classes;
class Pk10_draw{

    /**
     * 作用：定位胆
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03,01 02 03 04,,04,05,06,07,08,09,10"
     * @param $draw_number   "开奖号码例："01,02,03,04,05,06,07,08,09,10"
     * @return int  总中奖注数
     */
    public function draw_040101($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if($bet_number_arr[$key]!=""){
                if(strpos($bet_number_arr[$key],$value) !== false){
                    $total_number++;
                }
            }
        }
        return $total_number;
    }


    /**
     * 作用：前五 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04,04 05 06 07,08 09 10,01 02,04 03"
     * @param $draw_number   "冠亚季军开奖号码例："10,02,03,04,05"
     * @return int  总注数
     */
    public function draw_040201($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number_arr[$key],$value) !== false){
                $total_number++;
            }
        }
        $total_number = $total_number == 5?1:0;
        return $total_number;
    }



    /**
     * 作用：前四 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04,04 05 06 07,08 09 10,05 06"
     * @param $draw_number   "冠亚季四开奖号码例："10,02,03,04"
     * @return int  总注数
     */
    public function draw_040301($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number_arr[$key],$value) !== false){
                $total_number++;
            }
        }
        $total_number = $total_number == 4?1:0;
        return $total_number;
    }




    /**
     * 作用：前三 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04,04 05 06 07,08 09 10"
     * @param $draw_number   "冠亚季军开奖号码例："10,02,03"
     * @return int  总注数
     */
    public function draw_040401($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number_arr[$key],$value) !== false){
                $total_number++;
            }
        }
        $total_number = $total_number == 3?1:0;
        return $total_number;
    }


    /**
     * 作用：前二 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04,04 05 06 07"
     * @param $draw_number   "冠亚军开奖号码例："01,02"
     * @return int  总注数
     */
    public function draw_040501($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number_arr[$key],$value) !== false){
                $total_number++;
            }
        }
        $total_number = $total_number == 2?1:0;
        return $total_number;
    }



    /**
     * 作用：前一 直选复式
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："01 02 03 04 05 06 07 08 09 10"
     * @param $draw_number   "冠军开奖号码例："10"
     * @return int  总注数
     */
    public function draw_040601($bet_number,$draw_number){
        if(strpos($bet_number,$draw_number) !== false){
            return 1;
        }
        return 0;
    }



    /**
     * 作用：龙虎冠军
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码例："龙,虎"
     * @param $draw_number   "冠军开奖号码冠军3 第十名10 例："03,10"
     * @return int  总注数
     */
    public function draw_040701($bet_number,$draw_number){
        $total_number   = 0;
        $first  = substr($draw_number,0,2);
        $second = substr($draw_number,3);
        if($first>$second)$flag = "龙";
        if($first<$second)$flag = "虎";
        if(strpos($bet_number,$flag) !== false){
            $total_number++;
        }
        return $total_number;
    }



    /**
     * 作用：大小单双
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number  "投注号码"大小单双,大小单双,大小单双,大小单双"
     * @param $draw_number  "开奖结果"大单,小单,小双,小单,大单"
     * @return int  总注数
     */
    public function draw_040801($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            $big_little = mb_substr($value,0,1,"utf-8");
            $one_double = mb_substr($value,1,strlen($value),"utf-8");
            $all        = [$big_little,$one_double];
            foreach ($all as $k=>$v){
                if(strpos($bet_number_arr[$key],$v) !== false){
                    $total_number++;
                }
            }
        }
        return $total_number;
    }



    /**
     * 作用：和值
     * 作者：信
     * 时间：2018/5/4
     * 修改：暂无
     * @param $bet_number   "投注号码"01,02,03,04"
     * @param $draw_number   "开奖和值"02"
     * @return int  总注数
     */
    public function draw_040901($bet_number,$draw_number){
        return substr_count($bet_number,$draw_number);
    }

}
