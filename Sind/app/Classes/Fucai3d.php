<?php
/**
 * 福彩3D注数计算
 * Created by 信.
 * Date: 2018/5/3
 */
namespace App\Classes;
class Fucai3d{

    /**
     * 作用：三星 直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "12,235,345";
     * @return int
     */
    public function bn_050101($bet_number){
        $total_number   = 1;
        $bet_number_arr = explode(",",$bet_number);
        if(count($bet_number_arr) != 3)return 0;
        foreach ($bet_number_arr as $key=>$value){
            if($value!=""){
                $length = strlen($value);
                $total_number *= $length;
            }
        }
        return $total_number;
    }



    /**
     * 作用：三星 直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
    * @param $bet_number  投注号码例： $bet_number = "123,235,345";
     * @return int
     */
    public function bn_050102($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：三星 直选和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "1,2,3";
     * @return int
     */
    public function bn_050103($bet_number){
        $check=array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27');
        $sele_count=array('1','3','6','10','15','21','28','36','45','55','63','69','73','75','75','73','69','63','55','45','36','28','21','15','10','6','3','1');
        $bet1   = explode(',',$bet_number);
        $a      = array_unique($bet1);
        $endnum = 0;
        if(count($bet1)!=count($a) || count($bet1)<1 || count($bet1)>28) return 0;
        foreach($bet1 as $bets){
            if(!in_array($bets,$check)) return 0;
        }
        for($i=0;$i<count($bet1);$i++){
            $num=$bet1[$i];
            $endnum=$endnum+intval($sele_count[$num]);
        }
        return $endnum;
    }


    /**
     * 作用：三星 组三复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123";
     * @return int
     */
    public function bn_050104($bet_number){
        return pow(strlen($bet_number),2)-strlen($bet_number);
    }



    /**
     * 作用：三星 组三单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "122,233";
     * @return int
     */
    public function bn_050105($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：三星 组六复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012345";
     * @return int
     */
    public function bn_050106($bet_number){
        $check      = array(0,0,1,4,10,20,35,56,84,120);
        return $check[strlen($bet_number)-1];
    }



    /**
     * 作用：三星 组六单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "123,345";
     * @return int
     */
    public function bn_050107($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：三星 混合组选
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "123,345";
     * @return int
     */
    public function bn_050108($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }


    /**
     * 作用：三星 组选和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "1,2,3,4,5";
     * @return int
     */
    public function bn_050109($bet_number){
        $check=array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26');
        $sele_count=array('1','2','2','4','5','6','8','10','11','13','14','14','15','15','14','14','13','11','10','8','6','5','4','2','2','1');
        $bet1=explode(',', $bet_number);$a=array_unique($bet1);$endnum=0;
        if(count($bet1)!=count($a) || count($bet1)<1 || count($bet1)>27) return 0;
        foreach($bet1 as $bets){
            if(!in_array($bets,$check)) return 0;
        }
        for($i=0;$i<count($bet1);$i++){$num=$bet1[$i]-1;$endnum=$endnum+intval($sele_count[$num]);}
        return $endnum;
    }


    /**
     * 作用：二星 前二直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @return int
     */
    public function bn_050201($bet_number){
        $total_number   = 1;
        $bet_number_arr = explode(",",$bet_number);
        if(count($bet_number_arr) != 2){
            return 0;
        };
        foreach ($bet_number_arr as $key=>$value){
            if($value!=""){
                $length = strlen($value);
                $total_number *= $length;
            }
        }
        return $total_number;
    }


    /**
     * 作用：二星 前二直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @return int
     */
    public function bn_050202($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：二星 后二直选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @return int
     */
    public function bn_050203($bet_number){
        $total_number   = 1;
        $bet_number_arr = explode(",",$bet_number);
        if(count($bet_number_arr) != 2){
            return 0;
        };
        foreach ($bet_number_arr as $key=>$value){
            if($value!=""){
                $length = strlen($value);
                $total_number *= $length;
            }
        }
        return $total_number;
    }



    /**
     * 作用：二星 后二直选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "23,34";
     * @return int
     */
    public function bn_050204($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：二星 前二组选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012;
     * @return int
     */
    public function bn_050205($bet_number){
        $check      = array(0,1,3,6,10,15,21,28,36,45);
        return $check[strlen($bet_number)-1];
    }


    /**
     * 作用：二星 前二组选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "12,23;
     * @return int
     */
    public function bn_050206($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：二星 后二组选复式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012;
     * @return int
     */
    public function bn_050207($bet_number){
        $check = array(0,1,3,6,10,15,21,28,36,45);
        return $check[strlen($bet_number)-1];
    }


    /**
     * 作用：二星 后二组选单式
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012;
     * @return int
     */
    public function bn_050208($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }


    /**
     * 作用：定位胆
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "012,123,345;
     * @return int
     */
    public function bn_050301($bet_number){
        $total_number = 0;
        $bet_number_arr = explode(",",$bet_number);
        foreach ($bet_number_arr as $key=>$value){
            $total_number += strlen($value);
        }
        return $total_number;
    }


    /**
     * 作用：不定位 一码不定位
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123456789;
     * @return int
     */
    public function bn_050401($bet_number){
       return strlen($bet_number);
    }



    /**
     * 作用：不定位 二码不定位
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "0123456789;
     * @return int
     */
    public function bn_050402($bet_number){
        $check = array(0,1,3,6,10,15,21,28,36,45);
        return $check[strlen($bet_number)-1];
    }



    /**
     * 作用：大小单双 前二大小单双
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大小单双,大小单双";
     * @return int
     */
    public function bn_050501($bet_number){
        $bet_number_arr = explode(",",$bet_number);
        if($bet_number_arr[0] == ""|| $bet_number_arr[1] == "")return 0;
        return mb_strlen($bet_number_arr[0])*mb_strlen($bet_number_arr[1]);
    }


    /**
     * 作用：大小单双 后二大小单双
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大小单双,大小单双";
     * @return int
     */
    public function bn_050502($bet_number){
        $bet_number_arr = explode(",",$bet_number);
        if($bet_number_arr[0] == ""|| $bet_number_arr[1] == "")return 0;
        return mb_strlen($bet_number_arr[0])*mb_strlen($bet_number_arr[1]);
    }


    /**
     * 作用：和值
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大,小,单,双,1,2,3";
     * @return int
     */
    public function bn_050601($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }



    /**
     * 作用：跨度
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "大,小,单,双,1,2,3";
     * @return int
     */
    public function bn_050701($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }


    /**
     * 作用：龙虎斗
     * 作者：信
     * 时间：2018/5/8
     * 修改：暂无
     * @param $bet_number  投注号码例： $bet_number = "龙,和,虎;
     * @return int
     */
    public function bn_050801($bet_number){
        if($bet_number=="")return 0;
        return substr_count($bet_number,",")+1;
    }

}
