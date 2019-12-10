<?php

namespace App\Classes;

class Scc
{

    /*  时时彩	猜双面	大 */
    public function kj_010101($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]>=5? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜双面	小 */
    public function kj_010102($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]<5? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜双面	单 */
    public function kj_010103($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜双面	双 */
    public function kj_010104($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜双面	质 */
    public function kj_010105($kj,$ts){
        $kj = explode(',',$kj);
        if($kj[$ts] == 1) return 0;
        $this->check($kj[$ts])? $result = 0:$result = 1;
        return $result;
    }
    /*  时时彩	猜双面	合 */
    public function kj_010106($kj,$ts){
        $kj = explode(',',$kj);
        if($kj[$ts] == 1) return 0;
        $this->check($kj[$ts])? $result = 1:$result = 0;
        return $result;
    }
    /*质 和 方法*/
    public function check($a){
        $n=0;
        if($n>0 && $n<2){
            $n=1;
        }else {
            $max=$a/2;
            for ($i=2;$i<=$max;$i++) {
                if($a%$i==0){
                    $n++;
                    break;
                }
            }
        }
        return $n;
    }
    /*  时时彩	猜数字	0*/
    public function kj_010201($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 0? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	1*/
    public function kj_010202($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 1? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	2*/
    public function kj_010203($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 2? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	3*/
    public function kj_010204($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 3? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	4*/
    public function kj_010205($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 4? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	5*/
    public function kj_010206($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 5? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	6*/
    public function kj_010207($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 6? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	7*/
    public function kj_010208($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 7? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	8*/
    public function kj_010209($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 8? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜数字	9*/
    public function kj_010210($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 9? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜总和	小*/
    public function kj_010301($kj){
        $kj = explode(',',$kj);
        $kjNum = 0;
        foreach ($kj as $item){
            $kjNum += $item;
        }
        $kjNum<=22? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜总和	大*/
    public function kj_010302($kj){
        $kj = explode(',',$kj);
        $kjNum = 0;
        foreach ($kj as $item){
            $kjNum += $item;
        }
        $kjNum>=23? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜总和	单*/
    public function kj_010303($kj){
        $kj = explode(',',$kj);
        $kjNum = 0;
        foreach ($kj as $item){
            $kjNum += $item;
        }
        $kjNum%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜总和	双*/
    public function kj_010304($kj){
        $kj = explode(',',$kj);
        $kjNum = 0;
        foreach ($kj as $item){
            $kjNum += $item;
        }
        $kjNum%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜龙虎	龙*/
    public function kj_010401($kj){
        $kj = explode(',',$kj);
        $kj[0]>$kj[4]? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜龙虎	虎*/
    public function kj_010402($kj){
        $kj = explode(',',$kj);
        $kj[0]<$kj[4]? $result = 1:$result = 0;
        return $result;
    }
    /*  时时彩	猜龙虎	和*/
    public function kj_010403($kj){
        $kj = explode(',',$kj);
        $kj[0]==$kj[4]? $result = 1:$result = 0;
        return $result;
    }
    /* PK10	猜双面	大 */
    public function kj_020101($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]>=6? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	小 */
    public function kj_020102($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]<=5? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	单 */
    public function kj_020103($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	双 */
    public function kj_020104($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	大单 */
    public function kj_020105($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]>=6&&$kj[$ts]%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	大双 */
    public function kj_020106($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]>=6&&$kj[$ts]%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	小单 */
    public function kj_020107($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]<=5&&$kj[$ts]%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜双面	小双 */
    public function kj_020108($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]<=5&&$kj[$ts]%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	1*/
    public function kj_020201($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 1? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	2*/
    public function kj_020202($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 2? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	3*/
    public function kj_020203($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 3? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	4*/
    public function kj_020204($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 4? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	5*/
    public function kj_020205($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 5? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	6*/
    public function kj_020206($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 6? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	7*/
    public function kj_020207($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 7? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	8*/
    public function kj_020208($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 8? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	9*/
    public function kj_020209($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 9? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜车号	10*/
    public function kj_020210($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts] == 10? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜龙虎	龙*/
    public function kj_020301($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]>$kj[9-$ts]? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜龙虎	虎*/
    public function kj_020302($kj,$ts){
        $kj = explode(',',$kj);
        $kj[$ts]<$kj[9-$ts]? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜庄闲	庄*/
    public function kj_020401($kj){
        $kj = explode(',',$kj);
        $kj[0]>$kj[1]? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10	猜庄闲	闲*/
    public function kj_020402($kj){
        $kj = explode(',',$kj);
        $kj[0]<$kj[1]? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    猜冠亚*/
    public function kj_020501($kj,$ts,$bet){
        $kj = explode(',',$kj);
        $bet = explode(',',$bet);
        $flag = 0;
        foreach ($bet as $item){
            if($item == $kj[0]||$item == $kj[1]){
                $flag++;
            }
        }
        $flag == 2? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 大*/
    public function kj_020601($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum>=12? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 小*/
    public function kj_020602($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum<=11? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 单*/
    public function kj_020603($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 双*/
    public function kj_020604($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum%2==0? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 3*/
    public function kj_020605($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==3? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 4*/
    public function kj_020606($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==3? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 5*/
    public function kj_020607($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==5? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 6*/
    public function kj_020608($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==6? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 7*/
    public function kj_020609($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==7? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 8*/
    public function kj_020610($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==8? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 9*/
    public function kj_020611($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==9? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 10*/
    public function kj_020612($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==10? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 11*/
    public function kj_020613($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==11? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 12*/
    public function kj_020614($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==12? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 13*/
    public function kj_020615($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==13? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 14*/
    public function kj_020616($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==14? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 15*/
    public function kj_020617($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==15? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 16*/
    public function kj_020618($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==16? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 17*/
    public function kj_020619($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==17? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 18*/
    public function kj_020620($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==18? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 19*/
    public function kj_020621($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum==19? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 3~7*/
    public function kj_020622($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum>=3&&$kjNum<=7? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 8~14*/
    public function kj_020623($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum>=8&&$kjNum<=14? $result = 1:$result = 0;
        return $result;
    }
    /*  PK10    冠亚和 15~19*/
    public function kj_020624($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1];
        $kjNum>=15&&$kjNum<=19? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	大 */
    public function kj_030101($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum>=14? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	小 */
    public function kj_030102($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum<=13? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	单 */
    public function kj_030103($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	双 */
    public function kj_030104($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum%2==0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	极大 */
    public function kj_030105($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum>=22? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	大单 */
    public function kj_030106($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum>=14&&$kjNum%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	小单 */
    public function kj_030107($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum<=13&&$kjNum%2!=0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	大双 */
    public function kj_030108($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum>=14&&$kjNum%2==0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	小双 */
    public function kj_030109($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum<=13&&$kjNum%2==0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜双面	极小 */
    public function kj_030110($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum<=5? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	0 */
    public function kj_030201($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==0? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	1*/
    public function kj_030202($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==1? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	2*/
    public function kj_030203($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==2? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	3*/
    public function kj_030204($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==3? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	4*/
    public function kj_030205($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==4? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	5*/
    public function kj_030206($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==5? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	6*/
    public function kj_030207($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==6? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	7*/
    public function kj_030208($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==7? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	8*/
    public function kj_030209($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==8? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	8*/
    public function kj_030210($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==9? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	10*/
    public function kj_030211($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==10? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	11*/
    public function kj_030212($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==11? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	12*/
    public function kj_030213($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==12? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	13*/
    public function kj_030214($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==13? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	14*/
    public function kj_030215($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==14? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	15*/
    public function kj_030216($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==15? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	16*/
    public function kj_030217($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==16? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	17*/
    public function kj_030218($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==17? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	18*/
    public function kj_030219($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==18? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	19*/
    public function kj_030220($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==19? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	20*/
    public function kj_030221($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==20? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	21*/
    public function kj_030222($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==21? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	22*/
    public function kj_030223($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==22? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	23*/
    public function kj_030224($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==23? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	24*/
    public function kj_030225($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==24? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	25*/
    public function kj_030226($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==25? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	26*/
    public function kj_030227($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==26? $result = 1:$result = 0;
        return $result;
    }
    /* 28	猜数字	27*/
    public function kj_030228($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $kjNum==27? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 倒顺*/
    public function kj_030301($kj){
        $kj = explode(',',$kj);
        $kj[0]-1==$kj[1]&&$kj[0]-2==$kj[2]? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 半顺*/
    public function kj_030302($kj){
        $kj = explode(',',$kj);
        sort($kj);
        $kj[0]+1==$kj[1]||$kj[1]+1==$kj[2]? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 乱顺*/
    public function kj_030303($kj){
        $kj = explode(',',$kj);
        sort($kj);
        $kj[0]+1==$kj[1]&&$kj[0]+2==$kj[2]? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 红*/
    public function kj_030304($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $arr = [3,6,9,12,15,18,21,24,27];
        array_intersect([$kjNum],$arr)? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 绿*/
    public function kj_030305($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $arr = [1,4,7,10,16,19,22,25];
        array_intersect([$kjNum],$arr)? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 蓝*/
    public function kj_030306($kj){
        $kj = explode(',',$kj);
        $kjNum = $kj[0]+$kj[1]+$kj[2];
        $arr = [2,5,8,11,17,20,23,26];
        array_intersect([$kjNum],$arr)? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 豹子*/
    public function kj_030307($kj){
        $kj = explode(',',$kj);
        $kj[0]==$kj[1] && $kj[0]==$kj[2]? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 正顺*/
    public function kj_030308($kj){
        $kj = explode(',',$kj);
        $kj[0]+1==$kj[1]&&$kj[0]+2==$kj[2]? $result = 1:$result = 0;
        return $result;
    }
    /* 28	特殊玩法 对子*/
    public function kj_030309($kj){
        $kj = explode(',',$kj);
        sort($kj);
        $kj[0]==$kj[1]||$kj[1]==$kj[2]? $result = 1:$result = 0;
        return $result;
    }

}