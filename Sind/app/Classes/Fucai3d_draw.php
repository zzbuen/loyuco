<?php
/**
 * 福彩3D开奖中奖注数计算
 * Created by 信.
 * Date: 2018/5/3
 */
namespace App\Classes;
use App\Models\User;

class Fucai3d_draw{

    /**
     * 作用：三星 直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "12,235,345";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050101($bet_number,$draw_number){
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
     * 作用：三星 直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "123,235,345";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050102($bet_number,$draw_number){
        $draw_number = str_replace(",","",$draw_number);
        if(strpos($bet_number,$draw_number) !== false){
            return 1;
        }
        return 0;
    }



    /**
     * 作用：三星 直选和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "1,2,3";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050103($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_total  = array_sum($draw_number_arr);
        if(in_array($draw_number_total,$bet_number_arr)){
            return 1;
        }
        return 0;
    }


    /**
     * 作用：三星 组三复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,9";
     * @return int
     */
    public function draw_050104($bet_number,$draw_number){
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        $draw_number_total  = 0;
        if(count($draw_number_arr) != 2)return 0;
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number,$value) !== false){
                $draw_number_total++;
            }
        }
        if($draw_number_total == 2){
            return 1;
        }
        return 0;
    }



    /**
     * 作用：三星 组三单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "122,233";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,9";
     * @return int
     */
    public function draw_050105($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        if(count($draw_number_arr) != 2)return 0;
        foreach ($bet_number_arr as $key=>$value){
            if(strpos($value,$draw_number_arr[0])!==false && strpos($value,$draw_number_arr[1])!==false){
                return 1;
            }
        }
        return 0;
    }



    /**
     * 作用：三星 组六复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012345";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050106($bet_number,$draw_number){
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        $draw_number_total  = 0;
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number,$value) !== false){
                $draw_number_total++;
            }
        }
        if($draw_number_total ==  3){
            return 1;
        }
        return 0;
    }



    /**
     * 作用：三星 组六单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "123,345";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050107($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        foreach ($bet_number_arr as $key=>$value){
            if(strpos($value,$draw_number_arr[0])!==false && strpos($value,$draw_number_arr[1])!==false && strpos($value,$draw_number_arr[2])!==false){
                return 1;
            }
        }
        return 0;
    }



    /**
     * 作用：三星 混合组选
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "123,344";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050108($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        /*组三中奖*/
        if(count($draw_number_arr) == 2){
            foreach ($bet_number_arr as $key=>$value){
                if(strpos($value,$draw_number_arr[0])!==false && strpos($value,$draw_number_arr[1])!==false){
                    return "组三中奖";
                }
            }
        }
        /*组六中奖*/
        if(count($draw_number_arr) == 3){
            foreach ($bet_number_arr as $key => $value){
                if (strpos($value, $draw_number_arr[0]) !== false && strpos($value, $draw_number_arr[1]) !== false && strpos($value, $draw_number_arr[2]) !== false) {
                    return "组六中奖";
                }
            }
        }
        return 0;
    }


    /**
     * 作用：三星 组选和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "1,12,13,14,25";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050109($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr_dif    = array_unique($draw_number_arr);
        /*组三中奖*/
        if(count($draw_number_arr_dif) == 2){
            $draw_number_arr_sum = array_sum($draw_number_arr);
            if(in_array($draw_number_arr_sum,$bet_number_arr)){
                return "组三中奖";
            }

        }
        /*组六中奖*/
        if(count($draw_number_arr_dif) == 3){
            $draw_number_arr_sum = array_sum($draw_number_arr);
            if(in_array($draw_number_arr_sum,$bet_number_arr)){
                return "组六中奖";
            }
        }
        return 0;
    }


    /**
     * 作用：二星 前二直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050201($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        if(strpos($bet_number_arr[0],$draw_number_arr[0]) !== false && strpos($bet_number_arr[1],$draw_number_arr[1]) !== false){
            return 1;
        }
        return 0;
    }


    /**
     * 作用：二星 前二直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050202($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($bet_number_arr as $key=>$value){
            $first  = substr($value,0,1);
            $second = substr($value,1);
            if($first == $draw_number_arr[0] && $second == $draw_number_arr[1]){
                return 1;
            }
        }
        return 0;
    }



    /**
     * 作用：二星 后二直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050203($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        if(strpos($bet_number_arr[0],$draw_number_arr[1]) !== false && strpos($bet_number_arr[1],$draw_number_arr[2]) !== false){
            return 1;
        }
        return 0;
    }



    /**
     * 作用：二星 后二直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050204($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($bet_number_arr as $key=>$value){
            $second     = substr($value,0,1);
            $third      = substr($value,1);
            if($second == $draw_number_arr[1] && $third == $draw_number_arr[2]){
                return 1;
            }
        }
        return 0;
    }



    /**
     * 作用：二星 前二组选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012;
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050205($bet_number,$draw_number){
        $draw_number_arr    = explode(",",$draw_number);
        /*if($draw_number_arr[0] == $draw_number_arr[1])return 0;*/
        if(strpos($bet_number,$draw_number_arr[0]) !== false && strpos($bet_number,$draw_number_arr[1]) !== false){
            return 1;
        }
        return 0;
    }


    /**
     * 作用：二星 前二组选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "12,23;
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050206($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        /*if($draw_number_arr[0] == $draw_number_arr[1])return 0;*/
        foreach ($bet_number_arr as $key=>$value){
            if(strpos($value,$draw_number_arr[0]) !== false && strpos($value,$draw_number_arr[1]) !== false){
                return 1;
            }
        }
        return 0;
    }



    /**
     * 作用：二星 后二组选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "456";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050207($bet_number,$draw_number){
        $draw_number_arr    = explode(",",$draw_number);
        /*if($draw_number_arr[1] == $draw_number_arr[2])return 0;*/
        if(strpos($bet_number,$draw_number_arr[1]) !== false && strpos($bet_number,$draw_number_arr[2]) !== false){
            return 1;
        }
        return 0;
    }


    /**
     * 作用：二星 后二组选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "12,23";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050208($bet_number,$draw_number){
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        /*if($draw_number_arr[1] == $draw_number_arr[2])return 0;*/
        foreach ($bet_number_arr as $key=>$value){
            if(strpos($value,$draw_number_arr[1]) !== false && strpos($value,$draw_number_arr[2]) !== false){
                return 1;
            }
        }
        return 0;
    }


    /**
     * 作用：定位胆
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012,123,345;
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050301($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number_arr[$key],$value) !== false){
                $total_number++;
            }
        }
        return $total_number;

    }


    /**
     * 作用：不定位 一码不定位(可中多注奖励)
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123456789;
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050401($bet_number,$draw_number){
        $total_number       = 0;
        $draw_number_arr    = explode(",",$draw_number);
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number,$value) !== false){
                $total_number++;
            }
        }
        return $total_number;
    }



    /**
     * 作用：不定位 二码不定位
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123456789";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050402($bet_number,$draw_number){
        $total_number       = 0;
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr    = array_unique($draw_number_arr);
        if(count($draw_number_arr) == 1) return 0;
        foreach ($draw_number_arr as $key=>$value){
            if(strpos($bet_number,$value) !== false){
                $total_number++;
            }
        }
        if($total_number<2)return 0;
        return 1;
    }



    /**
     * 作用：大小单双 前二大小单双
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大小单双,大小单双";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050501(){
        $bet_number = "大小单双,大小单双";
        $draw_number = "4,9,8";
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $first      = $draw_number_arr[0]>=5?"大":"小";
        $first2     = $draw_number_arr[0]%2==0?"双":"单";
        $second     = $draw_number_arr[1]>=5?"大":"小";
        $second2    = $draw_number_arr[1]%2==0?"双":"单";
        if(strpos($bet_number_arr[0],$first) !== false || strpos($bet_number_arr[0],$first2) !== false){
            $total_number++;
        }
        if(strpos($bet_number_arr[1],$second) !== false || strpos($bet_number_arr[1],$second2) !== false){
            $total_number++;
        }
        $total_number = $total_number==2?1:0;
        return $total_number;
    }


    /**
     * 作用：大小单双 后二大小单双
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大小单双,大小单双";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050502($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $first      = $draw_number_arr[1]>=5?"大":"小";
        $first2     = $draw_number_arr[1]%2==0?"双":"单";
        $second     = $draw_number_arr[2]>=5?"大":"小";
        $second2    = $draw_number_arr[2]%2==0?"双":"单";
        if(strpos($bet_number_arr[0],$first) !== false || strpos($bet_number_arr[0],$first2) !== false){
            $total_number++;
        }
        if(strpos($bet_number_arr[1],$second) !== false || strpos($bet_number_arr[1],$second2) !== false){
            $total_number++;
        }
        $total_number = $total_number==2?1:0;
        return $total_number;
    }


    /**
     * 作用：和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大,小,单,双,1,2,3";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050601($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $draw_number_arr_sum    = array_sum($draw_number_arr);
        $one_or_double          = $draw_number_arr_sum%2==0?"双":"单";
        $big_or_little          = $draw_number_arr_sum>=14?"大":"小";
        if(in_array($draw_number_arr_sum,$bet_number_arr)){
            $total_number++;
        }
        if(in_array($one_or_double,$bet_number_arr)){
            $total_number++;
        }
        if(in_array($big_or_little,$bet_number_arr)){
            $total_number++;
        }
        return $total_number;
    }



    /**
     * 作用：跨度
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大,小,单,双,1,2,3";
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050701($bet_number,$draw_number){
        $total_number       = 0;
        $bet_number_arr     = explode(",",$bet_number);
        $draw_number_arr    = explode(",",$draw_number);
        $kuadu              = max($draw_number_arr)-min($draw_number_arr);
        $big_or_little      = $kuadu>=15?"大":"小";
        $one_or_double      = $kuadu%2==0?"双":"单";
        if(in_array($kuadu,$bet_number_arr)){
            $total_number++;
        }
        if(in_array($one_or_double,$bet_number_arr)){
            $total_number++;
        }
        if(in_array($big_or_little,$bet_number_arr)){
            $total_number++;
        }
        return $total_number;
    }


    /**
     * 作用：龙和虎
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "龙,和,虎;
     * @param $draw_number  开奖号码例： $draw_number = "4,9,8";
     * @return int
     */
    public function draw_050801($bet_number,$draw_number){
        $draw_number_arr    = explode(",",$draw_number);
        $first              = $draw_number_arr[0];
        $second             = $draw_number_arr[2];
        if($first == $second)$res = "和";
        if($first > $second)$res = "龙";
        if($first < $second)$res = "虎";
        if(strpos($bet_number,$res) !== false){
            return 1;
        }
        return 0;
    }

}
